<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;

use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class BaseFixture extends Fixture
{
    /** @var String */
    public $projectDir;
    /** @var ObjectManager */
    private $manager;
    /** @var Generator */
    protected $faker;
    /** Liste des références aux entités générées par les fixtures */
    private $references = [];

    // Méthode à implémenter par les classes enfant
    // dans laquelle générer les fausses données
    abstract protected function loadData(ObjectManager $manager);


    // Méthode imposée par Doctrine
    public function load(\Doctrine\Persistence\ObjectManager $manager)
    {

        // Enregistrement du Manager et instanciation de Faker
        $this->manager = $manager;
        $this->faker = Factory::create('fr_FR');

        // Appel de la méthode pour générer les données
        $this->loadData($manager);

    }

    /**
     * Créer plusieurs entités
     * @param int $count        nombre d'entités à créer
     * @param string $groupName nom associé aux entités générées
     * @param callable $factory fonction pour créer 1 entité
     */
    protected function createMany(int $count, string $groupName, callable $factory)
    {
        // Exécuter $factory $count fois
        for ($i = 0; $i < $count; $i++) {
            // La $factory doit retourner l'entité créée
            $entity = $factory($i);

            if ($entity === null) {
                throw new \LogicException('Tu as oublié de retourner l\'entité !!!');
            }

            // Avertir Doctrine pour l'enregistrement de l'entité
            $this->manager->persist($entity);

            // Ajouter une référence pour l'entité
            $this->addReference(sprintf('%s_%d', $groupName, $i), $entity);
        }
    }

    /**
     * Obtenir une entité aléatoire d'un groupe
     * @throws \Exception
     */
    protected function getRandomReference(string $groupName)
    {
        // Si les references ne sont pas présentes dans la propriété:
        if (!isset($this->references[$groupName])) {
            $this->references[$groupName] = [];

            // Récupération des références
            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (strpos($key, $groupName . '_') === 0) {
                    $this->references[$groupName][] = $key;
                }
            }
        }

        // Vérifier que des références ont été enregistrées
        if (empty($this->references[$groupName])) {
            throw new \Exception(sprintf('Aucune référence trouvée pour "%s"', $groupName));
        }

        // Retourner une référence aléatoire
        $randomReferenceKey = $this->faker->randomElement($this->references[$groupName]);
        return $this->getReference($randomReferenceKey);
    }

    /**
     * Récupérer plusieurs entités
     * @throws \Exception
     */
    protected function getRandomReferences(string $groupName, int $amount)
    {
        $references = [];

        while (count($references) < $amount) {
            $references[] = $this->getRandomReference($groupName);
        }

        return $references;
    }
}