<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Note;
use App\Form\NoteFormType;
use App\Repository\MenuRepository;
use App\Repository\CategoryRepository;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/menu", name="menu")
 */
class MenuController extends AbstractController
{
    /**
     * @Route("/{id}", name="_page")
     */
    public function MenusPage(Request $request,
                                     EntityManagerInterface $em,
                                     Security $security,
                                     NoteRepository $noteRepository,
                                     MenuRepository $menuRepository,
                                     Menu $menu)
    {

        if ($security->isGranted('ROLE_USER')) {
            // Rechercher une Note à modifier
            $note = $noteRepository->findOneBy([
                'menu' => $menu,
                'user' => $this->getUser()
            ]);

            // Pas de Note existante: initialisation
            if ($note === null) {
                $note = (new Note())
                    ->setMenu($menu)
                    ->setUser($this->getUser());
            }

            $noteForm = $this->createForm(NoteFormType::class, $note);
            $noteForm->handleRequest($request);

            if ($noteForm->isSubmitted() && $noteForm->isValid()) {
                $note = $noteForm->getData();

                $em->persist($note);
                $em->flush();

                $this->addFlash('success', 'Note enregistrée');

            }
        }


//        $id = $request->get('id');
//        $menu = $menuRepository->findOneBy(["id" => $id]);

        return $this->render('menu/menu_page.html.twig', [
            'menu' => $menu,
            'note_form' => isset($noteForm) ? $noteForm->createView() : null
        ]);
    }

    /**
     * @Route("/note-delete/{id}", name="_delete_note")
     * @IsGranted("NOTE_DELETE", subject="note")
     */
    public function deleteNote(Note $note)
    {
        dd('Vous avez le droit de suppression de la note !');
    }

    /**
     * @Route("/list/{id}", name="_list")
     */
    public function MenuList(
        Request $request,
                                     CategoryRepository $categoryRepository
    )
    {
      $id = $request->get('id');
        $category = $categoryRepository->findOneBy(["id" => $id]);

        return $this->render('menu/menu_list.html.twig', [
            'category' => $category,

        ]);
    }
}
