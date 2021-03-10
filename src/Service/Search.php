<?php


namespace App\Service;


use Doctrine\ORM\Mapping as ORM;

class Search
{
    public $searchIndice = '';
     /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus", inversedBy="sorties", cascade="persist")
     */
     public $campus;

     public $searchDateDebut;

     public $searchDateFin;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sortiesOrganisees", cascade="persist")
     */
     public $organisateur;
     

}