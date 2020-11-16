<?php

namespace App\Controller;

use App\Repository\FonctionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(UserRepository $userRepo,FonctionRepository $fonctionRepo): Response
    {
       
        return $this->render('home/index.html.twig', [
            'nbEleve' => count($userRepo->findByFunction($fonctionRepo::FONCTION_ELEVE)),
            'nbFormateur' => count($userRepo->findByFunction($fonctionRepo::FONCTION_FORMATEUR)),
            'nbComptable' => count($userRepo->findByFunction($fonctionRepo::FONCTION_COMPTABLE)),
            'nbAdmin' => count($userRepo->findByFunction($fonctionRepo::FONCTION_ADMIN)),
        ]);
    }
}
