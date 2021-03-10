<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $nom_campus;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="campus", cascade="remove")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sortie", mappedBy="campus")
     */
    private $sorties;

    /**
     * @return ArrayCollection
     */
    public function getSorties(): ArrayCollection
    {
        return $this->sorties;
    }

    /**
     * @param ArrayCollection $sorties
     */
    public function setSorties(ArrayCollection $sorties): void
    {
        $this->sorties = $sorties;
    }

    /**
     * @return ArrayCollection
     */
    public function getUser(): ArrayCollection
    {
        return $this->user;
    }

    /**
     * @param ArrayCollection $user
     */
    public function setUser(ArrayCollection $user): void
    {
        $this->user = $user;
    }



    public function __construct(){
        $this->user = new ArrayCollection();
        $this->sorties = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCampus(): ?string
    {
        return $this->nom_campus;
    }

    public function setNomCampus(string $nom_campus): self
    {
        $this->nom_campus = $nom_campus;

        return $this;
    }

    /**
     * toString
     * @return string
     */
    public function __toString() :?string
    {
        return $this->getId();
    }
}
