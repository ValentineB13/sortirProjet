<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FindSortie;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\SortieCancelType;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/create", name="create_sortie")
     */
    public function creerSortie(EntityManagerInterface $entityManager, Request $request)
    {

        $user = $this->getUser();
        $sortie = new Sortie();

        $sortie->setOrganisateur($user);
        $sortie->setCampus($user->getCampus());


        $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
        $etat = $etatRepo->find('2');

        $sortie->setEtat($etat);


        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            $entityManager->persist($sortie);

            $entityManager->flush();

            $this->addFlash('success', 'Événement ajouté !');
            return $this->redirectToRoute('home');
        }

        return $this->render('sortie/create.html.twig', ["sortieForm" => $sortieForm->createView()]);


    }



    /**
     * @Route("/sortie/afficherSortie/{id}", name="afficher_sortie")
     */
    public function afficherSortie($id)
    {

        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        return $this->render('sortie/afficherSortie.html.twig', ["sortie" => $sortie]);

    }

    /**
     * @Route("/sortie/sinscrire/{id}", name="inscription_sortie")
     */
    public function sinscrire($id, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sortie = $sortieRepository->find($id);
        $participants = $sortie->getMembresInscrits();

        // inscription impossible pour l'organisateur de la sortie
        if ($user->getId() === $sortie->getOrganisateur()->getId()) {
            $this->addFlash('danger', 'Echec inscription : vous êtes l\'organisateur de la sortie.');

            return $this->redirectToRoute('afficher_sortie', compact('id')); }


        // si le participant essaie de s'incrire une 2eme fois a la sortie
        foreach ($participants as $participant) {
            if ($participant->getId() === $user->getId()) {
                $this->addFlash('warning', 'Vous êtes déjà inscrit à cette sortie!');

                return $this->redirectToRoute('afficher_sortie', compact('id'));
            }

        }

        //si etat nest pas "ouvert"
        $etat = $sortie->getEtat();
        if ($etat->getLibelle() !== 'Ouverte') {
            $this->addFlash(
                'danger',
                'Il n\'est pas possible de s\'inscrire à cette sortie car elle a été annulée. '
            );

            return $this->redirectToRoute('afficher_sortie', compact('id'));
        }

        $sortie->addParticipant($user);
        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('success',
            'Votre inscription a été prise en compte!');

        return $this->redirectToRoute('afficher_sortie', compact('id'));
    }

    /**
     * @Route("/sortie/modifierSortie/{id}", name="modifierSortie")
     */
    public function modifSortie($id, EntityManagerInterface $entityManager, Request $request)
    {

        $user = $this->getUser();
    $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
    $sortie = $sortieRepo->find($id);

        $sortie->setOrganisateur($user);
        $sortie->setCampus($user->getCampus());


        $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
        $etat = $etatRepo->find('2');

        $sortie->setEtat($etat);


        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            $entityManager->persist($sortie);

            $entityManager->flush();

            $this->addFlash('success', 'Sortie modifiée avec succès !');
            return $this->redirectToRoute('home');
        }

        return $this->render('sortie/modifSortie.html.twig', ["sortieForm" => $sortieForm->createView(),
            "sortie" => $sortie]);


    }

    /**
     * @Route("/desister/{id}", name="desister", requirements={"id": "\d+"})
     */
    public function desister($id, EntityManagerInterface $entityManager)
    {

        $user = $this->getUser();

        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sortie = $sortieRepository->find($id);

        $membresInscrits = $sortie->getMembresInscrits();
        $dateDebutSortie = $sortie->getDateDebut();

        if (!($membresInscrits->contains($user))) {
            $this->addFlash('warning', 'Vous ne pouvez pas désister car vous ne participez pas à cette sortie!');
        } elseif ($dateDebutSortie <= new \DateTime('now')) {
            $this->addFlash('danger', 'Sortie est dèjà en cours! Impossible de désister!');
        } // verifier encore si le user est bien inscrit a cette sortie avant de le supprimer de la liste
        elseif ($membresInscrits->contains($user)) {
            // suppression de participant
            $sortie->removeParticipant($user);
        }
        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash('success', 'Votre désistement a été pris en compte!');

        return $this->redirectToRoute('afficher_sortie', compact('id'));
    }


    /**
     * @Route("/sortie/cancel/{id}", requirements={"id": "\d+"}, name="sortie_cancel")
     */
    public function index(Request $request, EntityManagerInterface $em)
    {
        /** @var Sortie $sortie */
        $sortie = $em->getRepository(Sortie::class)->find($request->get('id'));
        $sortie->setDescriptionInfos('');
        $form = $this->createForm(SortieCancelType::class, $sortie);
        /**
         * si validé on affecte le motif et on set le status a ANNULE
         */
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sortie->setEtat($em->getRepository(Etat::class)->find(6));
            $sortie->setDescriptionInfos($form->get('descriptionInfos')->getData());
            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render('sortie/sortieCancel.html.twig', [
            "sortie" => $sortie,
            "controller_name" => 'Annulation de la sortie ',
            'sortieCancel' => $form->createView()
        ]);
    }


}
