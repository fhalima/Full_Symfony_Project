<?php

namespace App\Controller\Admin;

//use App\Entity\Annonce;
//use App\Entity\Categorie;
//use App\Entity\Note;
use App\Entity\Menu;
use App\Entity\MenuDetaille;
use App\Entity\Photos;
use App\Entity\Presentation;
use App\Entity\User;

//use App\Form\AnnonceFormType;
//use App\Form\CategorieFormType;
use App\Form\DashboardNoteFormType;
use App\Form\MenuDetailleFormType;
use App\Form\MenuFormType;
use App\Form\UserProfileFormType;
use App\Form\DashboardUserFormType;

//use App\Repository\AnnonceRepository;
////use App\Repository\CategorieRepository;
////use App\Repository\CommentaireRepository;
////use App\Repository\NoteRepository;
////use App\Repository\PhotoRepository;
use App\Repository\MenuDetailleRepository;
use App\Repository\MenuRepository;
use App\Repository\NoteRepository;
use App\Repository\PresentationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\Switch_;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin", name="admin_")
 * @IsGranted("ROLE_ADMIN")
 */
class DashboardController extends AbstractController
{

    private $menuList;
    private $userList;
    private $commentaireList;
    private $noteList;
    private $photoList;
    private $menudetailleList;
    private $menuform;
    private $menu_curr;
    private $menudetailleform;
    private $menudetaille_curr;
    private $presentationList;

    public function __construct(MenuRepository $menuRepository,
                                MenuDetailleRepository $menuDetailleRepository,
                                UserRepository $userRepository,
                                PresentationRepository $presentationRepository,
//                                ,CommentaireRepository $commentaireRepository,
                                NoteRepository $noteRepository
//                                PhotoRepository $photoRepository
    )
    {

        $this->menuList = $menuRepository->findAll();
        $this->menudetailleList = $menuDetailleRepository->findAll();
        $this->userList = $userRepository->findAll();
        $this->presentationList = $presentationRepository->SelectNames($presentationRepository->findAll());
//        $this->commentaireList = $commentaireRepository->findAll();
        $this->noteList = $noteRepository->findAll();
//        $this->photoList = $photoRepository->findAll();
//        $this->annonceList = $annonceRepository->findAll();

    }


    /**
     * @Route("/", name="dashboard")
     */
    public function index()
    {
        return $this->redirectToRoute('admin_user');
    }

    //------------------------------------------- Dashboard User -----------------------------------------------//

    /**
     * @Route("/user", name="user")
     */
    public function UserDashboard(Request $request,
                                  UserRepository $userRepository,
                                  EntityManagerInterface $entityManager,
                                  UserPasswordEncoderInterface $userPasswordEncoder)
    {

        $user_curr = new User();
        $userform = $this->createForm(DashboardUserFormType::class, $user_curr);
        $userform->handleRequest($request);

        if ($userform->isSubmitted() && $userform->isValid()) {
            $user_curr = $userform->getData();

            $password = $userPasswordEncoder->encodePassword($user_curr, $userform['plainPassword']->getData());
            $user_curr->setPassword($password);
            $user_curr->setRole($userform['role']->getData());

            $entityManager->persist($user_curr);
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur' . $user_curr->getPseudo() . ' a bien était ajoutée.');
            return $this->redirectToRoute('admin_user');

        }

        return $this->render('admin/user.html.twig', [
            'userList' => $this->userList,
            'user_button' => "Ajouter",
            'user_curr' => $user_curr,
            'user_form' => $userform->createView()

        ]);
    }

    /**
     * @Route("/user/update{id?}", name="user_update")
     */
    public function UserUpdateDashboard(Request $request,
                                        UserRepository $userRepository,
                                        EntityManagerInterface $entityManager)
    {
        $id = $request->get('id');
        $user_curr = $userRepository->findOneBy(["id" => $id]);
        $password = $user_curr->getPassword();
        $userform = $this->createForm(UserProfileFormType::class, $user_curr);
        $userform->handleRequest($request);

        if ($userform->isSubmitted() && $userform->isValid()) {
//            $user_curr->setElements($userform);
            $user_curr = $userform->getData();
            $user_curr->setPassword($password);
            $entityManager->persist($user_curr);
            $entityManager->flush();
            $this->addFlash('success', 'L\'utilisateur numéro ' . $user_curr->getId() . ' a était mis à jour.');
            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/user.html.twig', [
            'userList' => $this->userList,
            'user_button' => "Mettre à jour",
            'user_curr' => $user_curr,
            'user_form' => $userform->createView()

        ]);
    }

    /**
     * @Route("/user/delete/{id?}", name="user_delete")
     */
    public function UserDeleteDashboard(Request $request,
                                        UserRepository $userRepository,
                                        EntityManagerInterface $entityManager)
    {
        $id = $request->get('id');
        $user_curr = $userRepository->findOneBy(["id" => $id]);

        $userform = $this->createForm(UserProfileFormType::class, $user_curr);
        $userform->handleRequest($request);

        if ($userform->isSubmitted()) {
//            $annonce_curr = $annonceform->getData();
//            dd($user_curr);
            $entityManager->remove($user_curr);
            $entityManager->flush();
            $this->addFlash('success', 'L\'annonce ' . $user_curr->getId() . ' a bien était supprimée.');
            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/user.html.twig', [
            'userList' => $this->userList,
            'user_button' => "supprimer",
            'user_curr' => $user_curr,
            'user_form' => $userform->createView()

        ]);
    }

//    //------------------------------------------- Dashboard menu -----------------------------------------------//

    /**
     * @Route("/menu", name="menu")
     */
    public function MenuDashboard(Request $request,
                                  MenuRepository $annonceRepository,
                                  EntityManagerInterface $entityManager)
    {
        $menu_curr = new Menu();
        $this->menuform = $this->createForm(MenuFormType::class, $menu_curr);
        $this->menuform->handleRequest($request);

        if ($this->menuform->isSubmitted() && $this->menuform->isValid()) {
            $menu_curr = $this->menuform->getData();
            $this->SetMenuPhoto($menu_curr);


            $entityManager->persist($menu_curr);
            $entityManager->flush();
//
            $this->addFlash('success', 'Le menu ' . $menu_curr->getTitre() . ' a bien était ajoutée.');
            return $this->redirectToRoute('admin_menu');

        }

        return $this->render('admin/menu.html.twig', ['menuList' => $this->menuList,
            'menu_button' => "Ajouter",
            'menu_curr' => $menu_curr,
            'menu_form' => $this->menuform->createView()]);
    }

    /**
     * @Route("/menu/update{id?}", name="menu_update")
     */
    public
    function MenuUpdateDashboard(Request $request,
                                 MenuRepository $menuRepository,
                                 EntityManagerInterface $entityManager)
    {
        $id = $request->get('id');
        $menu_curr = $menuRepository->findOneBy(["id" => $id]);
        $this->menuform = $this->createForm(MenuFormType::class, $menu_curr);
        $this->menuform->handleRequest($request);

        if ($this->menuform->isSubmitted() && $this->menuform->isValid()) {
            $menu_curr = $this->menuform->getData();
            $this->SetMenuPhoto($menu_curr);
//        dd($menu_curr);
            $entityManager->persist($menu_curr);
            $entityManager->flush();
            $this->addFlash('success', 'Le menu numéro ' . $menu_curr->getId() . ' a était mis à jour.');
            return $this->redirectToRoute('admin_menu');
        }

        return $this->render('admin/menu.html.twig', [
            'menuList' => $this->menuList,
            'menu_button' => "Mettre à jour",
            'menu_curr' => $menu_curr,
            'menu_form' => $this->menuform->createView()

        ]);
    }

    /**
     * @Route("/menu/delete/{id?}", name="menu_delete")
     */
    public
    function MenuDeleteDashboard(Request $request,
                                 MenuRepository $menuRepository,
                                 EntityManagerInterface $entityManager)
    {
        $id = $request->get('id');
        $menu_curr = $menuRepository->findOneBy(["id" => $id]);

        $menuform = $this->createForm(MenuFormType::class, $menu_curr);
        $menuform->handleRequest($request);

        if ($menuform->isSubmitted() && $menuform->isValid()) {
//            $annonce_curr = $annonceform->getData();
            $entityManager->remove($menu_curr);
            $entityManager->flush();
            $this->addFlash('success', 'Le menu ' . $menu_curr->getTitre() . ' a bien était supprimée.');
            return $this->redirectToRoute('admin_menu');
        }

        return $this->render('admin/menu.html.twig', [
            'menuList' => $this->menuList,
            'menu_button' => "supprimer",
            'menu_curr' => $menu_curr,
            'menu_form' => $menuform->createView()

        ]);
    }

    public function SetMenuPhoto(Menu $menu_curr)
    {
//        dd($this->menuform['photo']->getData());
        $imageFile = $this->menuform['photo']->getData();
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $menu_curr->setPhoto($newFilename);
        }
    }

    //------------------------------------------- Dashboard Formules/Menus-Détaillés -----------------------------------------------//

    /**
     * @Route("/menudetaille", name="menudetaille")
     */
    public function MenuDetailleDashboard(Request $request,
                                          MenuDetailleRepository $menuDetailleRepository,
                                          EntityManagerInterface $entityManager)
    {
        $menudetaille_curr = new MenuDetaille();
        $menudetaille_curr->setIdPhotos(new Photos());
        $menudetaille_curr->setPresentation(new Presentation());
//        dd($this->presentationList);
        $this->menudetailleform = $this->createForm(MenuDetailleFormType::class, $menudetaille_curr, ['presentationList' => $this->presentationList]);
        $this->menudetailleform->handleRequest($request);

        if ($this->menudetailleform->isSubmitted() && $this->menudetailleform->isValid()) {

            $menudetaille_curr = $this->menudetailleform->getData();
            $this->SetMenuDetaillePhoto($menudetaille_curr);
            $menudetaille_curr->getPresentation()->setNom($this->menudetailleform['presentation']->getData());
            $entityManager->persist($menudetaille_curr);
//            dd($menudetaille_curr);
            $entityManager->flush();

            $this->addFlash('success', 'La Formule/Menu Détaillé ' . $menudetaille_curr->getTitre() . ' a bien était ajouté.');
            return $this->redirectToRoute('admin_menudetaille');

        }

        return $this->render('admin/menudetaille.html.twig', [
            'menudetailleList' => $this->menudetailleList,
            'menudetaille_button' => "Ajouter",
            'menudetaille_curr' => $menudetaille_curr,
            'menudetaille_form' => $this->menudetailleform->createView()

        ]);
    }

    /**
     * @Route("/menudetaille/update{id?}", name="menudetaille_update")
     */
    public function MenuDetailleUpdateDashboard(Request $request,
                                                MenuDetailleRepository $menuDetailleRepository,
                                                EntityManagerInterface $entityManager)
    {
        $id = $request->get('id');
        $menudetaille_curr = $menuDetailleRepository->findOneBy(["id" => $id]);
//        dd($menudetaille_curr->getIdPhotos());
//        $menudetaille_curr->setIdPhotos(new Photos());
//        dd($menudetaille_curr);
        $this->menudetailleform = $this->createForm(MenuDetailleFormType::class, $menudetaille_curr, ['presentationList' => $this->presentationList]);

        $this->menudetailleform->handleRequest($request);

        if ($this->menudetailleform->isSubmitted() && $this->menudetailleform->isValid()) {
            $menudetaille_curr = $this->menudetailleform->getData();
            $this->SetMenuDetaillePhoto($menudetaille_curr);
//        dd($menu_curr);
            $entityManager->persist($menudetaille_curr);

            $entityManager->persist($menudetaille_curr);
            $entityManager->flush();
            $this->addFlash('success', 'La Formule/Menu Détaillé numéro ' . $menudetaille_curr->getId() . ' a était mis à jour.');
            return $this->redirectToRoute('admin_menudetaille');
        }

        return $this->render('admin/menudetaille.html.twig', [
            'menudetailleList' => $this->menudetailleList,
            'menudetaille_button' => "Mettre à jour",
            'menudetaille_curr' => $menudetaille_curr,
            'menudetaille_form' => $this->menudetailleform->createView()

        ]);
    }

    /**
     * @Route("/menudetaille/delete/{id?}", name="menudetaille_delete")
     */
    public function MenuDetailleDeleteDashboard(Request $request,
                                                MenuDetailleRepository $menuDetailleRepository,
                                                EntityManagerInterface $entityManager)
    {
        $id = $request->get('id');
        $menudetaille_curr = $menuDetailleRepository->findOneBy(["id" => $id]);

        $menudetailleform = $this->createForm(MenuDetailleFormType::class, $menudetaille_curr);
        $menudetailleform->handleRequest($request);

        if ($menudetailleform->isSubmitted() && $menudetailleform->isValid()) {
            $entityManager->remove($menudetaille_curr);
            $entityManager->flush();
            $this->addFlash('success', 'La Formule/Menu Détaillé ' . $menudetaille_curr->getTitre() . ' a bien était supprimée.');
            return $this->redirectToRoute('admin_menudetaille');
        }

        return $this->render('admin/menudetaille.html.twig', [
            'menudetailleList' => $this->menudetailleList,
            'menudetaille_button' => "supprimer",
            'menudetaille_curr' => $menudetaille_curr,
            'menudetaille_form' => $menudetailleform->createView()

        ]);
    }

    public function SetMenuDetaillePhoto(MenuDetaille $menudetaille_curr)
    {
        $imageFile = $this->menudetailleform['photo']->getData();
        if ($imageFile == !null)
            $menudetaille_curr->setPhoto($this->SaveImageFile($imageFile));

        $idPhotos = $menudetaille_curr->getIdPhotos();

        for ($i = 1; $i <= 4; $i++) {

            $photo = $this->menudetailleform['photo' . $i]->getData();
            if ($photo == !null) {
                $set = 'setPhoto' . $i;
                $idPhotos->$set($this->SaveImageFile($photo));

            }

        }
        $menudetaille_curr->setIdPhotos($idPhotos);
    }

    public function SaveImageFile(UploadedFile $imageFile): ?string
    {
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            return $newFilename;
        }
    }

//    //------------------------------------------- Dashboard Note -----------------------------------------------//
//
    /**
     * @Route("/note", name="note")
     */
    public function NoteDashboard(NoteRepository $noteRepository)
    {

        return $this->render('admin/note.html.twig',
            ['noteList' => $this->noteList]);
    }

    /**
     * @Route("/note/delete/{id?}", name="note_delete")
     */
    public function NoteDeleteDashboard(Request $request,
                                        NoteRepository $noteRepository,
                                        EntityManagerInterface $entityManager)
    {
        $id = $request->get('id');
        $note_curr = $noteRepository->findOneBy(["id" => $id]);
        $noteForm = $this->createForm(DashboardNoteFormType::class, $note_curr);

        $noteForm->handleRequest($request);

        if ($noteForm->isSubmitted()) {
            $entityManager->remove($note_curr);
            $entityManager->flush();
            $this->addFlash('success', 'La note ' . $note_curr->getId() . ' a bien était supprimée.');
            return $this->redirectToRoute('admin_note');

        }
        return $this->render('admin/note.html.twig', [
            'noteList' => $this->noteList,
            'note_form' => $noteForm->createView(),
            'note_curr'=>$note_curr
        ]);
    }

//    //------------------------------------------- Dashboard Commentaire -----------------------------------------------//
//
//    /**
//     * @Route("/commentaire", name="commentaire")
//     */
//    public
//    function CommentaireDashboard(CommentaireRepository $commentaireRepository)
//    {
//        return $this->render('admin/dashboard/commentaire.html.twig',
//            ['commentaireList' => $this->commentaireList]);
//    }
//
//
//    /**
//     * @Route("/commentaire/delete/{id?}", name="commentaire_delete")
//     */
//    public function CommentaireDeleteDashboard(Request $request,
//                                               CommentaireRepository $commentaireRepository,
//                                               EntityManagerInterface $entityManager)
//    {
//        $id = $request->get('id');
//        $commentaire_curr = $commentaireRepository->findOneBy(["id" => $id]);
//
//        $entityManager->remove($commentaire_curr);
//        $entityManager->flush();
//        $this->addFlash('success', 'Le commentaire ' . $commentaire_curr->getId() . ' a bien était supprimée.');
//        return $this->redirectToRoute('admin_commentaire');
//
//
//        return $this->render('admin/dashboard/commentaire.html.twig', [
//            'commentaireList' => $this->commentaireList
//
//        ]);
//    }
//
//    /**
//     * @Route("/statistique", name="statistique")
//     */
//    public function StatistiqueDashboard(Request $request,
//                                               UserRepository $userRepository,
//                                               AnnonceRepository $annonceRepository,
//                                               CategorieRepository $categorieRepository,
//                                               EntityManagerInterface $entityManager)
//    {
//       $stat1 = $userRepository->TopFiveBestNotedUsers();
//        $stat2 = $userRepository->TopFiveBestActifUsers();
//        $stat3 = $annonceRepository->TopOldestAnnounce();
//        $stat4 = $categorieRepository->TopFiveBigestAnnounceNumber();
////dd($stat1);
//        return $this->render('admin/dashboard/statistique.html.twig', [
//            'stat1' => $stat1,
//            'stat2' => $stat2,
//            'stat3' => $stat3,
//            'stat4' => $stat4
//
//        ]);
//    }
}