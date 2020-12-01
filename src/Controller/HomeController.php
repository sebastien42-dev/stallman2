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
        

        return $this->render('home/index.html.twig', [
            'nbUser' => json_encode($tab_nb_user),
            'labelCatgorie' => json_encode($tab_nom_categorie)
        ]);
    }

     /**
     * @Route("/homeeleve", name="home_eleve")
     */

    public function indexEleve(NoteRepository $noteRepo): Response
    {

        $moyenne = $this->calculeMoyennePonderee($noteRepo->findByEleves($this->getUser()->getId()));
        
        return $this->render('home/index.html.twig', [
            'moyenne' => $moyenne
        ]);
    }

/**
 * permet de calculer une myenne pondérée à partir du tableau d'enregistrements 
 * todo demander le type de ce que j'ai mis en paramètre et voir pour mettre ca dans un metier de fonction static
 * pour éviter de pourrir le controleur
 * @param obj $objet_note
 * @return float
 */
    public function calculeMoyennePonderee($tab_objet_note) 
    {
        $total_note=0;
        $coefficient =0;
        foreach ($tab_objet_note as $note) {
            $total_note += $note->getNote()*$note->getCoefficient();
            $coefficient += $note->getCoefficient();
        }
        if(count($tab_objet_note) > 0) {
            return round($total_note/$coefficient,2);
        }else{
            return 0;
        }
    }
}
