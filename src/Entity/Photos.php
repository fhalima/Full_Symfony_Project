<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotosRepository")
 */
class Photos
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
    private $photo_1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo_2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo_3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo_4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo_5;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MenuDetaille", mappedBy="id_photos", cascade={"persist", "remove"})
     */
    private $menuDetaille;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoto1(): ?string
    {
        return $this->photo_1;
    }

    public function setPhoto1(string $photo_1): self
    {
        $this->photo_1 = $photo_1;

        return $this;
    }

    public function getPhoto2(): ?string
    {
        return $this->photo_2;
    }

    public function setPhoto2(string $photo_2): self
    {
        $this->photo_2 = $photo_2;

        return $this;
    }

    public function getPhoto3(): ?string
    {
        return $this->photo_3;
    }

    public function setPhoto3(?string $photo_3): self
    {
        $this->photo_3 = $photo_3;

        return $this;
    }

    public function getPhoto4(): ?string
    {
        return $this->photo_4;
    }

    public function setPhoto4(?string $photo_4): self
    {
        $this->photo_4 = $photo_4;

        return $this;
    }

    public function getPhoto5(): ?string
    {
        return $this->photo_5;
    }

    public function setPhoto5(?string $photo_5): self
    {
        $this->photo_5 = $photo_5;

        return $this;
    }

    public function getMenuDetaille(): ?MenuDetaille
    {
        return $this->menuDetaille;
    }

    public function setMenuDetaille(MenuDetaille $menuDetaille): self
    {
        $this->menuDetaille = $menuDetaille;

        // set the owning side of the relation if necessary
        if ($menuDetaille->getIdPhotos() !== $this) {
            $menuDetaille->setIdPhotos($this);
        }

        return $this;
    }
}
