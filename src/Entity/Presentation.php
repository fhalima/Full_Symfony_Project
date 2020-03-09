<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PresentationRepository")
 */
class Presentation
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
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MenuDetaille", mappedBy="presentation")
     */
    private $menuDetailles;

    public function __construct()
    {
        $this->menuDetailles = new ArrayCollection();
        $this->setNom('');
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|MenuDetaille[]
     */
    public function getMenuDetailles(): Collection
    {
        return $this->menuDetailles;
    }

    public function addMenuDetaille(MenuDetaille $menuDetaille): self
    {
        if (!$this->menuDetailles->contains($menuDetaille)) {
            $this->menuDetailles[] = $menuDetaille;
            $menuDetaille->setPresentation($this);
        }

        return $this;
    }

    public function removeMenuDetaille(MenuDetaille $menuDetaille): self
    {
        if ($this->menuDetailles->contains($menuDetaille)) {
            $this->menuDetailles->removeElement($menuDetaille);
            // set the owning side to null (unless already changed)
            if ($menuDetaille->getPresentation() === $this) {
                $menuDetaille->setPresentation(null);
            }
        }

        return $this;
    }


}
