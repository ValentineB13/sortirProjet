<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\User;
use App\Form\EditType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{

    /**
     * @Route("/register", name="user_register")
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $registerForm = $this->createForm(RegisterType::class, $user);

        $registerForm->handleRequest($request);
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            //hasher le mdp OBLIGATOIRE
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre compte a bien été créé');
            $this->redirectToRoute('login');

        }

        return $this->render("user/register.html.twig", [
            "registerForm" => $registerForm->createView()

        ]);
    }

    /**
     * @Route("/profil", name="profil")
     */
    public function profil()
    {

        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->findAll();

        return $this->render('user/profil.html.twig', [
            "user" => $user
        ]);
    }

    /**
     * @Route("/user/login", name="login")
     */
    public function login()
    {


        return $this->render('user/login.html.twig', []);

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        //symfony gère entierement cette route
    }

    /**
     * @Route("/user/edituser", name="edit_user")
     * Method({"GET", "POST"})
     */

    public function edit(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {
        $user = $this->getUser();

        $form = $this->createForm(EditType::class , $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //hasher le mdp OBLIGATOIRE
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Votre profil a été modifié avec succès');
            return $this->redirectToRoute('home');
        }
        return $this->render('user/edituser.html.twig',[
            // 'id' => $id,
            'editform' => $form->createView()
        ]);
    }

    /**
     * @Route("/afficherOrganisateur/{id}", name="afficherOrganisateur")
     * Method({"GET", "POST"})
     */
    public function afficherOrganisateur($id)
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        return $this->render('user/afficherOrganisateur.html.twig',
            ["sortie" => $sortie,
            ]);
    }


}
