<?php

namespace App\Controller;

use App\Entity\MenuDetaille;
use App\Entity\Note;
use App\Form\NoteFormType;
use App\Repository\MenuDetailleRepository;
use App\Repository\MenuRepository;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/menu/detaille", name="menu_detaille")
 */
class MenuDetailleController extends AbstractController
{
    /**
     * @Route("{id}", name="_page")
     */
    public function MenuDetaillePage(Request $request,
                                     EntityManagerInterface $em,
                                     Security $security,
                                     NoteRepository $noteRepository,
                                     MenuDetailleRepository $menuDetailleRepository,
                                     MenuDetaille $menuDetaille)
    {
        if ($security->isGranted('ROLE_USER')) {
            // Rechercher une Note à modifier
            $note = $noteRepository->findOneBy([
                'menudetaille' => $menuDetaille,
                'user' => $this->getUser()
            ]);

            // Pas de Note existante: initialisation
            if ($note === null) {
                $note = (new Note())
                    ->setMenuDetaille($menuDetaille)
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
//        $menudetaille = $menuDetailleRepository->findOneBy(["id" => $id]);

        return $this->render('menu_detaille/menudetaille_page.html.twig', [
            'menudetaille' => $menuDetaille,
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
     * @Route("/list{id}", name="_list")
     */
    public function MenuDetailleList(Request $request,
                                     MenuRepository $menuRepository)
    {
        $id = $request->get('id');
        $menu = $menuRepository->findOneBy(["id" => $id]);
//dd($id);
        return $this->render('menu_detaille/menudetaille_list.html.twig', [
            'menu' => $menu,

        ]);
    }
}
