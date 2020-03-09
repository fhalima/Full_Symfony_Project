<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Formule;
use App\Entity\Menu;
use App\Entity\Presentation;
use App\Entity\User;
use App\Entity\Photo;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Categorie;

class PresentationFixtures extends BaseFixture
{



    public function loadData(ObjectManager $manager)
    {
//        $this->projectDir = $this->getParameter('kernel.project_dir');
//        $this->projectDir = realpath(str_replace("src","",\dirname(__DIR__)));
        // La fonction anonyme sera exécutée 10 fois

        $this->createMany(5, 'presentation', function ($num) {
            // Construction de l'entité Menu
            $presentation = (new Presentation())
                ->setNom($this->faker->sentence(2))
                ->setImage('sur-plateau.jpg')
            ;

            return $presentation;
        });
        // Pour terminer, enregistrer
        $manager->flush();

    }

}
