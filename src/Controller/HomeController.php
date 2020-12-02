<?php

namespace App\Controller;

use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use App\Repository\FonctionRepository;
use App\Repository\EventPlanningRepository;
use App\Repository\MatiereRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */

    public function index(): Response
    {

        $roles = $this->getUser()->getRoles();

        foreach($roles as $role) {
            if($role != 'ROLE_USER') {
                $role_user = $role;
            }
        }

        if($role_user == 'ROLE_ELEVE') {
            return $this->redirectToRoute('home_eleve');
        } else {
            return $this->redirectToRoute('home_admin');
        }
    
    }

     /**
     * @Route("/homeeleve", name="home_eleve")
     */

    public function indexEleve(NoteRepository $noteRepo,EventPlanningRepository $eventRepo,MatiereRepository $matiereRepo): Response
    {
       
        $moyenne = $this->calculeMoyennePonderee($noteRepo->findByEleves($this->getUser()));
        $matieres = $matiereRepo->findMatieresByUser($this->getUser());
        $events = $eventRepo->findEventsByUser($this->getUser());

        foreach ($matieres as $matiere) {
            $notesMatiere = $noteRepo->findNotesByUserAndMatiere($this->getUser(),$matiere);
            $tabLabelsLibelle[] = $matiere->getLibelleMatiere();
            $tabDataMoyenne[] = $this->calculeMoyennePonderee($notesMatiere);

            if ($this->calculeMoyennePonderee($notesMatiere) <= 10) {
                $bgColor = 'rgba(255, 0, 0, 0.2)';
                $borderColor = 'rgba(255, 0, 0, 1)';
            } else {
                $bgColor = 'rgba(0, 115, 255, 0.2)';
                $borderColor = 'rgba(0, 115, 255, 1)';
            }

            $tabBgColor[] = $bgColor;
            $tabBorderColor[] = $borderColor;
        }
        return $this->render('home/index.html.twig', [
            'moyenne' => $moyenne,
            'events' => $events,
            'labelsMatieres' => json_encode($tabLabelsLibelle),
            'dataMoyenne' => json_encode($tabDataMoyenne),
            'bgColors' => json_encode($tabBgColor),
            'borderColors' => json_encode($tabBorderColor)
        ]);
    }

    /**
     * @Route("/homeadmin", name="home_admin")
     */

    public function indexAdmin(UserRepository $userRepo,FonctionRepository $fonctionRepo): Response
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
 * permet de calculer une myenne pondérée à partir du tableau d'enregistrements 
 * todo demander le type de ce que j'ai mis en paramètre et voir pour mettre ca dans un metier de fonction static
 * pour éviter de pourrir le controleur
 * @param obj $objet_note
 * @return float
 */
    public function calculeMoyennePonderee($tab_objet_note) 
    {
        $total_note = 0;
        $coefficient = 0;

        foreach ($tab_objet_note as $note) {
            $total_note += $note->getNote() * $note->getCoefficient();
            $coefficient += $note->getCoefficient();
        }

        if(count($tab_objet_note) > 0) {
            return round($total_note / $coefficient,2);
        } else {
            return 0;
        }
    }
}
