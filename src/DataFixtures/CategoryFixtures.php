<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Entity\Formule;
use App\Entity\Menu;
use App\Entity\User;
use App\Entity\Photo;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Categorie;

class CategoryFixtures extends BaseFixture
{



    public function loadData(ObjectManager $manager)
    {
//        $this->projectDir = $this->getParameter('kernel.project_dir');
//        $this->projectDir = realpath(str_replace("src","",\dirname(__DIR__)));
        // La fonction anonyme sera exécutée 10 fois

        $this->createMany(5, 'category', function ($num) {
            // Construction de l'entité Menu
            $category = (new Category())
                ->setTitre($this->faker->sentence(10))
//                ->setPhoto($this->projectDir."\assets\images\indien4-600x600.jpg")
                ->setPhoto(($num++).".jpg")
                ->setDescriptionCourte($this->faker->sentence(10))
                ->setDescriptionLongue($this->faker->sentence(20))
                ->setDateEnregistrement($this->faker->dateTimeBetween('-1 year'))
            ;
//            dd($this->projectDir);
            // Toujours retourner l'entité
            return $category;
        });
        // Pour terminer, enregistrer
        $manager->flush();

    }

}
