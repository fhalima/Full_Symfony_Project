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
     * @ORM\Column(type="datetime")
     */
    private $date_enregistrement;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MenuDetaille", mappedBy="id_menu", orphanRemoval=true)
     */
    private $menudetailles;

    public function __construct()
    {
        $this->menudetailles = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        //Définir la date de création par défaut
        if ($this->date_enregistrement === null) {
            $this->date_enregistrement = new \DateTime();
        }
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateEnregistrement(): ?\DateTimeInterface
    {
        return $this->date_enregistrement;
    }

    public function setDateEnregistrement(\DateTimeInterface $date_enregistrement): self
    {
        $this->date_enregistrement = $date_enregistrement;

        return $this;
    }

    /**
     * @return Collection|MenuDetaille[]
     */
    public function getMenuDetailles(): Collection
    {
        return $this->menudetailles;
    }

    public function addMenuDetailles(MenuDetaille $menudetaille): self
    {
        if (!$this->menudetailles->contains($menudetaille)) {
            $this->menudetailles[] = $menudetaille;
            $menudetaille->setIdMenu($this);
        }

        return $this;
    }

    public function removeMenuDetailles(MenuDetaille $menudetaille): self
    {
        if ($this->menudetailles->contains($menudetaille)) {
            $this->menudetailles->removeElement($menudetaille);
            // set the owning side to null (unless already changed)
            if ($menudetaille->getIdMenu() === $this) {
                $menudetaille->setIdMenu(null);
            }
        }

        return $this;
    }

   
}
