<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Formule;
use App\Entity\Menu;
use App\Entity\User;
use App\Entity\Photo;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Categorie;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function loadData(ObjectManager $manager)
    {
        // La fonction anonyme sera exécutée 50 fois
        $this->createMany(10, 'user', function ($num) {
            // Construction de l'entité categorie
            $user = new User();
            $user->setPseudo(sprintf('user%d', $num))
                ->setRoles(['ROLE_USER'])
                ->setNom($this->faker->firstName)
                ->setPrenom($this->faker->lastName)
                ->setTelephone($this->faker->phoneNumber)
                ->setEmail($this->faker->email)
                ->setCivilite($this->faker->randomElement(['f', 'm']))
//                ->setStatut($this->faker->randomElement(['0', '1']))
                ->setDateEnregistrement($this->faker->dateTimeBetween('-1 year'))
                ->setPassword($this->encoder->encodePassword($user, 'user' . $num));

            return $user;
        });

        $this->createMany(2, 'admin', function ($num) {
            // Construction de l'entité categorie
            $user = new User();
            $user->setPseudo(sprintf('admin%d', $num))
                ->setRoles(['ROLE_ADMIN'])
                ->setNom($this->faker->firstName)
                ->setPrenom($this->faker->lastName)
                ->setTelephone($this->faker->phoneNumber)
                ->setEmail($this->faker->email)
                ->setCivilite($this->faker->randomElement(['f', 'm']))
//                    ->setStatut($this->faker->randomElement(['0', '1']))
                ->setDateEnregistrement($this->faker->dateTimeBetween('-1 year'))
                ->setPassword($this->encoder->encodePassword($user, 'admin' . $num));

            // Toujours retourner l'entité
            return $user;
        });
        // Pour terminer, enregistrer
        $manager->flush();

    }

}
