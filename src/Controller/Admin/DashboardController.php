<?php

namespace App\Controller\Admin;

//use App\Entity\Annonce;
//use App\Entity\Categorie;
//use App\Entity\Note;
use App\Entity\Category;
use App\Entity\Menu;
use App\Entity\Photos;
use App\Entity\Presentation;
use App\Entity\User;

//use App\Form\AnnonceFormType;
//use App\Form\CategorieFormType;
use App\Form\DashboardNoteFormType;
use App\Form\MenuFormType;
use App\Form\CategoryFormType;
use App\Form\UserProfileFormType;
use App\Form\DashboardUserFormType;

//use App\Repository\AnnonceRepository;
////use App\Repository\CategorieRepository;
////use App\Repository\CommentaireRepository;
////use App\Repository\NoteRepository;
////use App\Repository\PhotoRepository;
use App\Repository\MenuRepository;
use App\Repository\CategoryRepository;
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

    private $categoryList;
    private $categoryTitreList;
    private $userList;
    private $commentaireList;
    private $noteList;
    private $photoList;
    private $menuList;
    private $categoryform;
    private $category_curr;
    private $menuform;
    private $menu_curr;
    private $presentationList;

    public function __construct(CategoryRepository $categoryRepository,
                                MenuRepository $menuRepository,
                                UserRepository $userRepository,
                                PresentationRepository $presentationRepository,
//                                ,CommentaireRepository $commentaireRepository,
                                NoteRepository $noteRepository
//                                PhotoRepository $photoRepository
    )
    {

        $this->categoryList = $categoryRepository->findAll();
        $this->categoryTitreList = $categoryRepository->SelectCategoryTitles($categoryRepository->findAll());
        $this->menuList = $menuRepository->findAll();
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

//    //------------------------------------------- Dashboard Category -----------------------------------------------//

    /**
     * @Route("/category", name="category")
     */
    public function CategoryDashboard(Request $request,
                                      CategoryRepository $annonceRepository,
                                      EntityManagerInterface $entityManager)
    {
        $category_curr = new Category();
        $this->categoryform = $this->createForm(CategoryFormType::class, $category_curr);
        $this->categoryform->handleRequest($request);

        if ($this->categoryform->isSubmitted() && $this->categoryform->isValid()) {
            $category_curr = $this->categoryform->getData();
            $this->SetCategoryPhoto($category_curr);


            $entityManager->persist($category_curr);
            $entityManager->flush();
//
            $this->addFlash('success', 'La catégorie ' . $category_curr->getTitre() . ' a bien était ajoutée.');
            return $this->redirectToRoute('admin_category');

        }

        return $this->render('admin/category.html.twig', ['categoryList' => $this->categoryList,
            'category_button' => "Ajouter",
            'category_curr' => $category_curr,
            'category_form' => $this->categoryform->createView()]);
    }

    /**
     * @Route("/category/update{id?}", name="category_update")
     */
    public
    function CategoryUpdateDashboard(Request $request,
                                     CategoryRepository $categoryRepository,
                                     EntityManagerInterface $entityManager)
    {
        $id = $request->get('id');
        $category_curr = $categoryRepository->findOneBy(["id" => $id]);
        $this->categoryform = $this->createForm(CategoryFormType::class, $category_curr);
        $this->categoryform->handleRequest($request);

        if ($this->categoryform->isSubmitted() && $this->categoryform->isValid()) {
            $category_curr = $this->categoryform->getData();
            $this->SetCategoryPhoto($category_curr);
//        dd($menu_curr);
            $entityManager->persist($category_curr);
            $entityManager->flush();
            $this->addFlash('success', 'La catégorie numéro ' . $category_curr->getId() . ' a était mis à jour.');
            return $this->redirectToRoute('admin_category');
        }

        return $this->render('admin/category.html.twig', [
            'categoryList' => $this->categoryList,
            'category_button' => "Mettre à jour",
            'category_curr' => $category_curr,
            'category_form' => $this->categoryform->createView()

        ]);
    }

    /**
     * @Route("/category/delete/{id?}", name="category_delete")
     */
    public
    function CategoryDeleteDashboard(Request $request,
                                     CategoryRepository $categoryRepository,
                                     EntityManagerInterface $entityManager)
    {
        $id = $request->get('id');
        $category_curr = $categoryRepository->findOneBy(["id" => $id]);

        $categoryform = $this->createForm(CategoryFormType::class, $category_curr);
        $categoryform->handleRequest($request);

        if ($categoryform->isSubmitted() && $categoryform->isValid()) {
//            $annonce_curr = $annonceform->getData();
            $entityManager->remove($category_curr);
            $entityManager->flush();
            $this->addFlash('success', 'La catégorie ' . $category_curr->getTitre() . ' a bien était supprimée.');
            return $this->redirectToRoute('admin_category');
        }

        return $this->render('admin/category.html.twig', [
            'categoryList' => $this->categoryList,
            'category_button' => "supprimer",
            'category_curr' => $category_curr,
            'category_form' => $categoryform->createView()

        ]);
    }

    public function SetCategoryPhoto(Category $category_curr)
    {
//        dd($this->categoryform['photo']->getData());
        $imageFile = $this->categoryform['photo']->getData();
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
//            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
            $newFilename = $safeFilename . '.' . $imageFile->guessExtension();

            // Move the file to the directory where brochures are stored
            if (!file_exists($this->getParameter('images_directory') . $imageFile)) {
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );

                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }
            $category_curr->setPhoto($newFilename);

        }
    }

    //------------------------------------------- Dashboard Formules/Menus-Détaillés -----------------------------------------------//

    /**
     * @Route("/menu", name="menu")
     */
    public function MenuDashboard(Request $request,
                                  MenuRepository $menuRepository,
                                  PresentationRepository $presentationRepository,
                                  CategoryRepository $categoryRepository,
                                  EntityManagerInterface $entityManager)
    {
        $menu_curr = new Menu();
        $menu_curr->setIdPhotos(new Photos());
        $menu_curr->setPresentation(new Presentation());
        $menu_curr->setIdCategory(new Category());
//        dd($this->presentationList);
        $this->menuform = $this->createForm(MenuFormType::class, $menu_curr, ['presentationList' => $this->presentationList,
                                                                                    'categoryTitreList' => $this->categoryTitreList]);
        $this->menuform->handleRequest($request);

        if ($this->menuform->isSubmitted() && $this->menuform->isValid()) {

            $menu_curr = $this->menuform->getData();
            $this->SetMenuPhoto($menu_curr);
            $menu_curr->setPresentation($presentationRepository->findOneBy(['Nom'=>$this->menuform['presentation']->getData()]));
            $menu_curr->setIdCategory($categoryRepository->findOneBy(['titre'=>$this->menuform['categorie']->getData()]));
            $menu_curr->setRubrique($this->menuform['rubrique']->getData());
            $entityManager->persist($menu_curr);
//            dd($menu_curr);
            $entityManager->flush();

            $this->addFlash('success', 'La Formule/Menu Détaillé ' . $menu_curr->getTitre() . ' a bien était ajouté.');
            return $this->redirectToRoute('admin_menu');

        }

        return $this->render('admin/menu.html.twig', [
            'menuList' => $this->menuList,
            'menu_button' => "Ajouter",
            'menu_curr' => $menu_curr,
            'menu_form' => $this->menuform->createView()

        ]);
    }

    /**
     * @Route("/menu/update{id?}", name="menu_update")
     */
    public function MenuUpdateDashboard(Request $request,
                                        MenuRepository $menuRepository,
                                        PresentationRepository $presentationRepository,
                                        CategoryRepository $categoryRepository,
                                        EntityManagerInterface $entityManager)
    {
        $id = $request->get('id');
        $menu_curr = $menuRepository->findOneBy(["id" => $id]);
//        dd($menu_curr->getIdPhotos());
//        $menu_curr->setIdPhotos(new Photos());
//        dd($menu_curr);
        $this->menuform = $this->createForm(MenuFormType::class, $menu_curr, ['presentationList' => $this->presentationList,
            'categoryTitreList' => $this->categoryTitreList]);

        $this->menuform->handleRequest($request);

        if ($this->menuform->isSubmitted() && $this->menuform->isValid()) {
            $menu_curr = $this->menuform->getData();
            $this->SetMenuPhoto($menu_curr);
//        dd($menu_curr);
            $menu_curr->setPresentation($presentationRepository->findOneBy(['Nom'=>$this->menuform['presentation']->getData()]));
            $menu_curr->setIdCategory($categoryRepository->findOneBy(['titre'=>$this->menuform['categorie']->getData()]));
            $menu_curr->setRubrique($this->menuform['rubrique']->getData());

            $entityManager->persist($menu_curr);
            $entityManager->flush();
            $this->addFlash('success', 'La Formule/Menu Détaillé numéro ' . $menu_curr->getId() . ' a était mis à jour.');
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
    public function MenuDeleteDashboard(Request $request,
                                        MenuRepository $menuRepository,
                                        EntityManagerInterface $entityManager)
    {
        $id = $request->get('id');
        $menu_curr = $menuRepository->findOneBy(["id" => $id]);

        $menuform = $this->createForm(MenuFormType::class, $menu_curr, ['presentationList' => $this->presentationList,
            'categoryTitreList' => $this->categoryTitreList]);
        $menuform->handleRequest($request);
        if ($menuform->isSubmitted() && $menuform->isValid()) {
            $entityManager->remove($menu_curr);
            $entityManager->flush();
            $this->addFlash('success', 'La Formule/Menu Détaillé ' . $menu_curr->getTitre() . ' a bien était supprimée.');
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
        $imageFile = $this->menuform['photo']->getData();
        if ($imageFile == !null)
            $menu_curr->setPhoto($this->SaveImageFile($imageFile));

        $idPhotos = $menu_curr->getIdPhotos();

        for ($i = 1; $i <= 4; $i++) {

            $photo = $this->menuform['photo' . $i]->getData();
            if ($photo == !null) {
                $set = 'setPhoto' . $i;
                $idPhotos->$set($this->SaveImageFile($photo));

            }

        }
        $menu_curr->setIdPhotos($idPhotos);
    }

    public function SaveImageFile(UploadedFile $imageFile): ?string
    {
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
//            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
            $newFilename = $safeFilename . '.' . $imageFile->guessExtension();
            // Move the file to the directory where brochures are stored
            if (!file_exists($this->getParameter('images_directory') . $imageFile)) {
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
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
            'note_curr' => $note_curr
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