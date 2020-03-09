<?php

namespace App\DataFixtures;



use App\Entity\Menu;
use App\Entity\MenuDetaille;
use App\Entity\Note;
use App\Entity\Photos;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class MenuDetailleFixtures extends BaseFixture implements DependentFixtureInterface
{
    private $encoder;


    public function loadData(ObjectManager $manager)
    {

            $this->createMany(20, 'menudetaille', function ($num) {
                // Construction de l'entité categorie
                $menudetaille = (new MenuDetaille())
                    ->setIdMenu($this->getRandomReference('menu'))
                    ->setNbrPersonnes($this->faker->numberBetween(1,15))
                    ->setDureePrepare($this->faker->numberBetween(1,15))
                    ->setTemperatureMin($this->faker->numberBetween(-4,4))
                    ->setTemperatureMin($this->faker->numberBetween(5,20))
                    ->setPresentation($this->getRandomReference('presentation'))
                    ->setTitre($this->faker->sentence(10))
                    ->setIngredients($this->faker->sentence(10))
                    ->setSuggestions($this->faker->sentence(10))
                    ->setPhoto($this->faker->imageUrl(600, 600))
                    ->setDescriptionCourte($this->faker->sentence(10))
                    ->setDescriptionLongue($this->faker->sentence(20))
                    ->setDateEnregistrement($this->faker->dateTimeBetween('-1 year'))
                    ->setPrixUnit($this->faker->randomFloat(0, 100))
                ;



                $photo = (new Photos())
                    ->setPhoto1($this->faker->imageUrl(600, 600))
                    ->setPhoto2($this->faker->imageUrl(600, 600))
                    ->setPhoto3($this->faker->imageUrl(600, 600))
                ;
                if ($this->faker->boolean(75)) {
                    $photo->setPhoto4($this->faker->imageUrl(200, 200));
                    if ($this->faker->boolean(75)) {
                        $photo->setPhoto5($this->faker->imageUrl(200, 200));
                    }
                }
                $menudetaille->setIdPhotos($photo);

                // Création des notes de l'album

                // Récupération des utilisateurs et élimination des doublons
                $nbUsers = $this->faker->numberBetween(0, 10);
                $users = $this->getRandomReferences('user', $nbUsers);
                $users = array_unique($users);

                // Création des notes
                foreach ($users as $user) {
                    $date = $this->faker->dateTimeBetween($menudetaille->getDateEnregistrement());

                    $note = (new Note())
                        ->setUser($user)
                        ->setValue($this->faker->numberBetween(0, 10))
                        ->setDateEnregistrement($date)
                    ;

                    // 75% des notes ont un commentaire
                    if ($this->faker->boolean(75)) {
                        $note->setComment($this->faker->realText());
                    }

                    // Ajout de la note au menudetaille
                    $menudetaille->addNote($note);
                }


                // Toujours retourner l'entité
                return $menudetaille;
            });
            // Pour terminer, enregistrer
            $manager->flush();

        }
    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            MenuFixtures::class,
            PresentationFixtures::class
        ];
    }
}
