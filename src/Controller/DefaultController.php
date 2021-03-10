<?php
namespace App\Controller;

use App\Entity\FindSortie;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\RegisterType;
use App\Form\SearchSortieType;
use App\Repository\SortieRepository;
use App\Service\Search;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(SortieRepository $sorties, Request $request)
    {
      $user = $this ->getUser();

        $search = new Search();
        $form = $this->createForm(SearchSortieType::class, $search);
        $form->handleRequest($request);
        $user = $this->getUser();

        //afficher les sorties présentes en BDD
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        if ($form->isSubmitted()&& $form->isValid())
        {
        $sortie = $sortieRepo->findAllSortie($search);
        }
        else
         $sortie = $sortieRepo->findAll();

        //$sortie = $sortieRepo->findAllSortie($search);

        //afficher les sorties présentes en BDD

       //$sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        //$sortie = $sortieRepo->findAll();

        return $this->render("default/home.html.twig", ["sortie" => $sortie,
       "user" => $user,
            "form" => $form->createView()

        ]);

    }





}
