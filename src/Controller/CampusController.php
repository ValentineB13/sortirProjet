<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CampusController extends AbstractController {

    /**
     * @Route("/campus", name="liste_campus")
     */
    public function list(){
        //récupérer et afficher les campus en BDD
        $campusRepo = $this->getDoctrine()->getRepository(\App\Entity\Campus::class);
        $campus = $campusRepo->findAll();

        return $this->render('campus/campus.html.twig', ["campus" => $campus]);
    }






}
