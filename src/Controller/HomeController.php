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
        $users = $userRepo->findAll();
        
        $nb_eleve = count($userRepo->findByFunction($fonctionRepo::FONCTION_ELEVE));
        $nb_formateur = count($userRepo->findByFunction($fonctionRepo::FONCTION_FORMATEUR));
        $nb_comptable = count($userRepo->findByFunction($fonctionRepo::FONCTION_COMPTABLE));
        $nb_admin = count($userRepo->findByFunction($fonctionRepo::FONCTION_ADMIN));

        return $this->render('home/index.html.twig', [
            'nbEleve' => $nb_eleve,
            'nbFormateur' => $nb_formateur,
            'nbComptable' => $nb_comptable,
            'nbAdmin' => $nb_admin,
        ]);
    }
}
