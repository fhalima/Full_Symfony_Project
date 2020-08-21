<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Form\UserContactFormType;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CategoryRepository $categoryRepository)
    {
        $categoryList=$categoryRepository->findAll();

        return $this->render('home.html.twig', [
            'categoryList' => $categoryList,
        ]);
    }

    /**
     * @Route("/category", name="categories")
     */
    public function Category(CategoryRepository $categoryRepository)
    {
        $categoryList=$categoryRepository->findAll();

        return $this->render('category/category_list.html.twig', [
            'categoryList' => $categoryList,
        ]);
    }

    /**
     * @Route("/cgv", name="cgv")
     */
    public function CGV(CategoryRepository $categoryRepository)
    {
        return $this->render('includes/cgv.html.twig');
    }

    /**
     * @Route("/qsn", name="qsn")
     */
    public function QSN()
    {
        return $this->render('includes/cgv.html.twig');
    }

    /**
     * @Route("/contact/{id}", name="user_contact")
     */
    public function UserContact(UserRepository $userRepository, Request $request, MailerInterface $mailer)
    {
        $id = $request->get('id');
        $user = $userRepository->findOneBy(["id" => $id]);
        $contactForm = $this->createForm(UserContactFormType::class);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $this->SendEmail($user->getEmail(), $mailer, 'ghazaloran2@gmail.com', $contactForm['objet']->getData(),
                'emails/message_contact.html.twig', [
                   'message' => $contactForm['message']->getData(),
                   'correspondant'=>$user->getEmail(),
                   'objet'=>$contactForm['objet']->getData()
               ]);

            return $this->redirect($request->getUri());

   }

        return $this->render('contact/user_contact.html.twig',[
            'contact_form'=>$contactForm->createView(),
            'user_contact'=>$user

            ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function Contact(Request $request, MailerInterface $mailer)
    {

        $contactForm = $this->createForm(ContactFormType::class);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {

            $this->SendEmail($contactForm['email']->getData(), $mailer, 'ghazaloran2@gmail.com', $contactForm['objet']->getData(),
                'emails/message_contact.html.twig', [
                    'message' => $contactForm['message']->getData(),
                    'correspondant'=>$contactForm['email']->getData(),
                    'objet'=>$contactForm['objet']->getData()
                ]);
            return $this->redirect($request->getUri());
        }

        return $this->render('contact/contact.html.twig',[
            'contact_form'=>$contactForm->createView()
            ]);
    }

public function SendEmail(String $from, MailerInterface $mailer,
                          String $to, String $subject, String $template,
                          array $context):TemplatedEmail{

    $email = (new TemplatedEmail())
        ->from($from)
        ->to($to)
        ->subject($subject)
        ->htmlTemplate($template)
        ->context($context)
    ;
    $mailer->send($email);
    $this->addFlash(
        'success',
        'Votre message a bien été envoyé.'
    );
       return $email;
}



}
