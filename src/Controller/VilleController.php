<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class VilleController extends AbstractController {

    /**
     * @Route("/ville", name="liste_villes")
     */
    public function list(){
        //récupérer et afficher les campus en BDD
        $villeRepo = $this->getDoctrine()->getRepository(Ville::class);
        $ville = $villeRepo->findAll();

        return $this->render('ville/villes.html.twig', ["ville" => $ville]);
    }






}
