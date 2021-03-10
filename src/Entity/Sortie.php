<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
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
    private $nom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duree;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCloture;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbInscriptionsMax;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $descriptionInfos;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Etat", inversedBy="sorties", cascade="persist")
     */
    private $etat;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sortiesOrganisees")
     */
    private $organisateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus", inversedBy="sorties")
     */
    private $campus;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="inscriptionsSortie")
     */
    private $membresInscrits;

    /**
     * @return mixed
     */
    public function getMembresInscrits()
    {
        return $this->membresInscrits;
    }

    /**
     * @param mixed $membresInscrits
     */
    public function setMembresInscrits($membresInscrits): void
    {
        $this->membresInscrits = $membresInscrits;
    }


    /**
     * @return mixed
     */
    public function getCampus()
    {
        return $this->campus;
    }

    /**
     * @param mixed $campus
     */
    public function setCampus($campus)
    {
        $this->campus = $campus;
    }

    /**
     * @return mixed
     */
    public function getOrganisateur()
    {
        return $this->organisateur;
    }

    /**
     * @param mixed $organisateur
     */
    public function setOrganisateur($organisateur): void
    {
        $this->organisateur = $organisateur;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateCloture(): ?\DateTimeInterface
    {
        return $this->dateCloture;
    }

    public function setDateCloture(\DateTimeInterface $dateCloture): self
    {
        $this->dateCloture = $dateCloture;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(int $nbInscriptionsMax): self
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    public function getDescriptionInfos(): ?string
    {
        return $this->descriptionInfos;
    }

    public function setDescriptionInfos(?string $descriptionInfos): self
    {
        $this->descriptionInfos = $descriptionInfos;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat): void
    {
        $this->etat = $etat;
    }


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lieu", inversedBy="sorties", cascade={"persist"})
     */
    private $lieuSortie;

    /**
     * @return mixed
     */
    public function getLieuSortie()
    {
        return $this->lieuSortie;
    }

    /**
     * @param mixed $lieuSortie
     */
    public function setLieuSortie($lieuSortie): void
    {
        $this->lieuSortie = $lieuSortie;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->membresInscrits->contains($participant)) {
            $this->membresInscrits[] = $participant;
            $participant->addInscrit($this);
        }

        return $this;

    }

    public function removeParticipant(User $participant): self
    {
        if ($this->membresInscrits->contains($participant)) {
            $this->membresInscrits->removeElement($participant);
            $participant->removeInscrit($this);
        }

        return $this;
    }




}
