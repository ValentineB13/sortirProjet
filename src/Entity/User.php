<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity("pseudo", message="Ce pseudo existe déjà")
 * @UniqueEntity ("email", message="Cet email est déjà utilisé")
 * @ORM\Table(name="PARTICIPANTS")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository", repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Merci de remplir ce champ")
     * @ORM\Column (type="string", length=30)
     */
    private $pseudo;

    /**
     * @Assert\NotBlank(message="Merci de remplir ce champ")
     * @ORM\Column (type="string", length=30)
     */
    private $nom;

    /**
     * @Assert\NotBlank(message="Merci de remplir ce champ")
     * @ORM\Column (type="string", length=30)
     */
    private $prenom;


    /**
     * @ORM\Column (type="integer", length=15)
     */
    private $telephone;

    /**
     * @ORM\Column (type="string", length=255)
     */
    private $email;

    /**
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit contenir au moins 8 caractères")
     * @ORM\Column (type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column (type="boolean", nullable=true)
     */
    private $administrateur = false;

    /**
     * @ORM\Column (type="boolean", nullable=true)
     */
    private $actif;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus", inversedBy="user")
     */
    private $campus;

    /**
     * @ORM\Column (type="json", nullable=true)
     */
    private $roles = ["ROLE_USER"];

    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sortie", mappedBy="organisateur")
     */
    private $sortiesOrganisees;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Sortie", mappedBy="membresInscrits")
     */
    private $inscriptionsSortie;

    public function __construct()
    {
        $this->sortiesOrganisees = new ArrayCollection();
        $this->inscriptionsSortie = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getSortiesOrganisees(): ArrayCollection
    {
        return $this->sortiesOrganisees;
    }

    /**
     * @param ArrayCollection $sortiesOrganisees
     */
    public function setSortiesOrganisees(ArrayCollection $sortiesOrganisees): void
    {
        $this->sortiesOrganisees = $sortiesOrganisees;
    }

    /**
     * @return ArrayCollection
     */
    public function getInscriptionsSortie(): ArrayCollection
    {
        return $this->inscriptionsSortie;
    }

    /**
     * @param ArrayCollection $inscriptionsSortie
     */
    public function setInscriptionsSortie(ArrayCollection $inscriptionsSortie): void
    {
        $this->inscriptionsSortie = $inscriptionsSortie;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $pseudo
     */
    public function setPseudo($pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getAdministrateur()
    {
        return $this->administrateur;
    }

    /**
     * @param mixed $administrateur
     */
    public function setAdministrateur($administrateur): void
    {
        $this->administrateur = $administrateur;
    }

    /**
     * @return mixed
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * @param mixed $actif
     */
    public function setActif($actif): void
    {
        $this->actif = $actif;
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
    public function setCampus($campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
    }


    //inutile pour nous
    public function getSalt()
    {
        return null;
    }

    //inutile pour nous
    public function eraseCredentials()
    {
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->pseudo;
    }

    public function addInscrit(Sortie $inscrit): self
    {
        if (!$this->inscriptionsSortie->contains($inscrit)) {
            $this->inscriptionsSortie[] = $inscrit;
        }

        return $this;
    }

    public function removeInscrit(Sortie $inscrit): self
    {
        if ($this->inscriptionsSortie->contains($inscrit)) {
        $this->inscriptionsSortie->removeElement($inscrit);
    }

        return $this;
    }


}
