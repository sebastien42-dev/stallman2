<?php

namespace App\Controller;

use App\Repository\FonctionRepository;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */

    public function index(UserRepository $userRepo,FonctionRepository $fonctionRepo, NoteRepository $noteRepo): Response
    {
        $tab_nom_categorie[]=$fonctionRepo::STR_FONCTION_ELEVE;
        $tab_nom_categorie[]=$fonctionRepo::STR_FONCTION_FORMATEUR;
        $tab_nom_categorie[]=$fonctionRepo::STR_FONCTION_COMPTABLE;
        $tab_nom_categorie[]=$fonctionRepo::STR_FONCTION_ADMIN;
        $tab_nb_user[]=count($userRepo->findByFunction($fonctionRepo::FONCTION_ELEVE));
        $tab_nb_user[]=count($userRepo->findByFunction($fonctionRepo::FONCTION_FORMATEUR));
        $tab_nb_user[]=count($userRepo->findByFunction($fonctionRepo::FONCTION_COMPTABLE));
        $tab_nb_user[]=count($userRepo->findByFunction($fonctionRepo::FONCTION_ADMIN));

        $total_note=0;
        $coefficient =0;
        $notes = $noteRepo->findByEleves($this->getUser()->getId());
        foreach ($notes as $note) {
            $total_note += $note->getNote()*$note->getCoefficient();
            $coefficient += $note->getCoefficient();
        }
        if(count($notes) > 0) {
            $moyenne = $total_note/$coefficient;
        }else{
            $moyenne = 0;
        }
        

        return $this->render('home/index.html.twig', [
            'nbUser' => json_encode($tab_nb_user),
            'labelCatgorie' => json_encode($tab_nom_categorie),
            'moyenne' => $moyenne
        ]);
    }
}
