<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\MenuRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Menu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="menus")
     * @ORM\JoinColumn(nullable=true)
     */
    private $id_category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description_courte;

    /**
     * @ORM\Column(type="text")
     */
    private $description_longue;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Photos", inversedBy="menu", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_photos;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_enregistrement;

    /**
     * @ORM\Column(type="integer")
     */
    private $Nbr_Personnes;

    /**
     * @ORM\Column(type="integer")
     */
    private $duree_prepare;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $temperature_min;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $temperature_max;

    /**
     * @ORM\Column(type="float")
     */
    private $prix_unit;

    /**
     * @ORM\Column(type="text")
     */
    private $ingredients;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Presentation", inversedBy="menus", cascade={"persist"})
     */
    private $presentation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="menu", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"date_enregistrement"="DESC"})
     */
    private $notes;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $rubrique;

    /**
     * @ORM\PrePersist()
     *
     */
    public function prePersist()
    {
        //Définir la date de création par défaut
        if ($this->date_enregistrement === null) {
            $this->date_enregistrement = new \DateTime();
        }
    }

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCategory(): ?Category
    {
        return $this->id_category;
    }

    public function setIdCategory(?Category $id_category): self
    {
        $this->id_category = $id_category;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescriptionCourte(): ?string
    {
        return $this->description_courte;
    }

    public function setDescriptionCourte(string $description_courte): self
    {
        $this->description_courte = $description_courte;

        return $this;
    }

    public function getDescriptionLongue(): ?string
    {
        return $this->description_longue;
    }

    public function setDescriptionLongue(string $description_longue): self
    {
        $this->description_longue = $description_longue;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getIdPhotos(): ?Photos
    {
        return $this->id_photos;
    }

    public function setIdPhotos(Photos $id_photos): self
    {
        $this->id_photos = $id_photos;

        return $this;
    }

    public function getDateEnregistrement(): ?\DateTimeInterface
    {
        return $this->date_enregistrement;
    }

    public function setDateEnregistrement(\DateTimeInterface $date_enregistrement): self
    {
        $this->date_enregistrement = $date_enregistrement;

        return $this;
    }

    public function getNbrPersonnes(): ?int
    {
        return $this->Nbr_Personnes;
    }

    public function setNbrPersonnes(int $Nbr_Personnes): self
    {
        $this->Nbr_Personnes = $Nbr_Personnes;

        return $this;
    }

    public function getDureePrepare(): ?int
    {
        return $this->duree_prepare;
    }

    public function setDureePrepare(int $duree_prepare): self
    {
        $this->duree_prepare = $duree_prepare;

        return $this;
    }


    public function getTemperatureMin(): ?int
    {
        return $this->temperature_min;
    }

    public function setTemperatureMin(?int $temperature_min): self
    {
        $this->temperature_min = $temperature_min;

        return $this;
    }

    public function getTemperatureMax(): ?int
    {
        return $this->temperature_max;
    }

    public function setTemperatureMax(?int $temperature_max): self
    {
        $this->temperature_max = $temperature_max;

        return $this;
    }

    public function getPrixUnit(): ?float
    {
        return $this->prix_unit;
    }

    public function setPrixUnit(float $prix_unit): self
    {
        $this->prix_unit = $prix_unit;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(string $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getPresentation(): ?Presentation
    {
        return $this->presentation;
    }

    public function setPresentation(?Presentation $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setMenu($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getMenu() === $this) {
                $note->setMenu(null);
            }
        }

        return $this;
    }

    public function getAverageNote() : ?float
    {
        // Pas de note: null
        if ($this->notes->isEmpty()) {
            return null;
        }

        // Extraire les valeurs des notes
        $values = $this->notes->map(function (Note $note) {
            return $note->getValue();
        });

        // Somme des notes
        $sum = array_sum($values->getValues());
        // Moyenne
        $average = $sum / $this->notes->count();
        return $average;
    }

    public function getRubrique(): ?string
    {
        return $this->rubrique;
    }

    public function setRubrique(string $rubrique): self
    {
        $this->rubrique = $rubrique;

        return $this;
    }

}
