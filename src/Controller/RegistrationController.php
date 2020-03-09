<?php /** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpDocMissingReturnTagInspection */


namespace App\Controller;


use App\Entity\User;
use App\Form\PasswordResetFormType;
use App\Form\UsernameFormType;
use App\Form\UserRegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * Page d'inscription
     * @Route("/register", name="user_registration")
     *
     * @param Request                      $request         Pour que le formulaire récupère les données POST
     * @param UserPasswordEncoderInterface $passwordEncoder Pour hasher le mot de passe de l'utilisateur
     * @param EntityManagerInterface       $entityManager   Pour enregistrer l'utilisateur en base de données
     * @param MailerInterface              $mailer          Pour envoyer un email de confirmation
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ) {
        $registerForm = $this->createForm(UserRegistrationFormType::class);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            // Le formulaire permet de récupérer l'entité User créée
            /** @var User $user */
            $user = $registerForm->getData();

            // Pour récupérer la valeur d'un champ dissocié par l'option "mapped"
            // il faut utiliser le formulaire comme un tableau:
            $password = $registerForm['plainPassword']->getData();

            // Définir le hash du mot de passe de l'utilisateur
            $user->setPassword($passwordEncoder->encodePassword($user, $password));
            // Enregistrer l'utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Envoi de l'email (voir plus bas la méthode sendConfirmationEmail() )
            $this->sendConfirmationEmail($mailer, $user);

            // Ajouter un message de succès et rediriger vers la page de connexion
            $this->addFlash('success', 'Vous êtes bien inscrit !');
            $this->addFlash('info', 'Vous devrez confirmez votre compte, un lien vous a été envoyé par email.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'register_form' => $registerForm->createView()
        ]);
    }

    /**
     * Confirmation du compte après inscription (lien envoyé par email)
     * @Route("/user-confirmation/{id}/{token}", name="user_confirmation")
     *
     * @param User                   $user          L'utilisateur qui tente de confirmer son compte
     * @param                        $token         Le jeton à vérifier pour confirmer le compte
     * @param EntityManagerInterface $entityManager Pour mettre à jour l'utilisateur
     */
    public function confirmAccount(User $user, $token, EntityManagerInterface $entityManager)
    {
        // L'utilisateur a déjà confirmé son compte
        if ($user->getIsConfirmed()) {
            $this->addFlash('warning', 'Votre compte est déjà confirmé, vous pouvez vous connecter.');
            return $this->redirectToRoute('app_login');
        }

        // Le jeton ne correspond pas à celui de l'utilisateur
        if ($user->getSecurityToken() !== $token) {
            $this->addFlash('danger', 'Le jeton de sécurité est invalide.');
            return $this->redirectToRoute('app_login');
        }

        // Le jeton est valide: mettre à jour le jeton et confirmer le compte
        $user->setIsConfirmed(true);
        $user->renewToken();

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Votre compte est confirmé, vous pouvez vous connecter.');
        return $this->redirectToRoute('app_login');
    }

    /**
     * Demander un renvoi du mail de confirmation
     * @Route("/send-confirmation", name="send_confirmation")
     *
     * @param Request         $request          Pour le formulaire
     * @param UserRepository  $userRepository   Pour rechercher l'utilisateur
     * @param MailerInterface $mailer           Pour renvoyer l'email de confirmation
     */
    public function sendConfirmation(Request $request, UserRepository $userRepository, MailerInterface $mailer)
    {
        // Création d'un formulaire demandant un email/pseudo
        $usernameForm = $this->createForm(UsernameFormType::class);
        $usernameForm->handleRequest($request);

        if ($usernameForm->isSubmitted() && $usernameForm->isValid()) {
            $username = $usernameForm->getData()['username'];

            // Récupérer un utilisateur par email ou pseudo
            // Note: vous pouviez choisir de récupérer par seulement l'email ou seulement le pseudo
            $user = $userRepository->findOneBy(['email' => $username])
                ?? $userRepository->findOneBy(['pseudo' => $username]);

            if ($user === null) {
                $this->addFlash('danger', 'Utilisateur inconnu');

            } elseif ($user->getIsConfirmed()) {
                $this->addFlash('warning', 'Votre compte est déjà confirmé.');
                return $this->redirectToRoute('app_login');

            } else {
                // Renvoi de l'email (voir plus bas la méthode sendConfirmationEmail() )
                $this->sendConfirmationEmail($mailer, $user);
                $this->addFlash('info', 'Un email de confirmation vous a été renvoyé.');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('registration/send_confirmation.html.twig', [
            'username_form' => $usernameForm->createView()
        ]);
    }

    /**
     * Code de l'envoi d'email de confirmation
     * Le code a été refactorisé en une méthode
     * car il est le même dans les méthodes register() & sendConfirmation()
     */
    private function sendConfirmationEmail(MailerInterface $mailer, User $user)
    {
        // Création de l'email de confirmation
        $email = (new TemplatedEmail())
            ->from('no-reply@kritik.fr')
            ->to($user->getEmail())
            ->subject('Confirmation du compte | KRITIK')
            /*
             * Indiquer le template de l'email puis les variables nécessaires
             */
            ->htmlTemplate('emails/confirmation.html.twig')
            ->context([
                'user' => $user
            ])
        ;
        // Envoi de l'email
        $mailer->send($email);
    }

    /**
     * Demander un lien de réinitialisation du mot de passe
     * @Route("/lost-password", name="lost_password")
     *
     * @param Request         $request          Pour le formulaire
     * @param UserRepository  $userRepository   Pour rechercher l'utilisateur
     * @param MailerInterface $mailer           Pour envoyer l'email de réinitialisation
     */
    public function lostPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer)
    {
        // Création d'un formulaire demandant un email/pseudo
        $usernameForm = $this->createForm(UsernameFormType::class);
        $usernameForm->handleRequest($request);

        if ($usernameForm->isSubmitted() && $usernameForm->isValid()) {
            $username = $usernameForm->getData()['username'];

            // Récupérer un utilisateur par email ou pseudo
            // Note: vous pouviez choisir de récupérer par seulement l'email ou seulement le pseudo
            $user = $userRepository->findOneBy(['email' => $username])
                ?? $userRepository->findOneBy(['pseudo' => $username]);

            if ($user === null) {
                $this->addFlash('danger', 'Utilisateur inconnu');

            } else {
                // Création de l'email de réinitialisation
                $email = (new TemplatedEmail())
                    ->from('no-reply@kritik.fr')
                    ->to($user->getEmail())
                    ->subject('Réinitialisation du mot de passe | KRITIK')
                    /*
                     * Indiquer le template de l'email puis les variables nécessaires
                     */
                    ->htmlTemplate('emails/password_reset.html.twig')
                    ->context([
                        'user' => $user
                    ])
                ;
                // Envoi de l'email
                $mailer->send($email);

                $this->addFlash('info', 'Un email de réinitialisation vous a été renvoyé.');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('registration/lost_password.html.twig', [
            'username_form' => $usernameForm->createView()
        ]);
    }

    /**
     * Réinitialiser le mot de passe
     * @Route("/reset-password/{id}/{token}", name="reset_password")
     *
     * @param User                          $user            L'utilisateur qui souhaite réinitialiser son mot de passe
     * @param                               $token           Le jeton à vérifier pour la réinitialisation
     * @param Request                       $request         Pour le formulaire de réinitialisation
     * @param EntityManagerInterface        $entityManager   Pour mettre à jour l'utilisateur
     * @param UserPasswordEncoderInterface $passwordEncoder Pour hasher le nouveau mot de passe
     */
    public function resetPassword(
        User $user,
        $token,
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        // Le jeton ne correspond pas à celui de l'utilisateur
        if ($user->getSecurityToken() !== $token) {
            $this->addFlash('danger', 'Le jeton de sécurité est invalide.');
            return $this->redirectToRoute('app_login');
        }

        // Création du formulaire de réinitialisation du mot de passe
        $resetForm = $this->createForm(PasswordResetFormType::class);
        $resetForm->handleRequest($request);

        if ($resetForm->isSubmitted() && $resetForm->isValid()) {
            $password = $resetForm->getData()['plainPassword'];

            // Mettre à jour l'utilisateur
            $user->setPassword($passwordEncoder->encodePassword($user, $password));
            $user->renewToken();

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été mis à jour.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/reset_password.html.twig', [
            'reset_form' => $resetForm->createView()
        ]);
    }
}