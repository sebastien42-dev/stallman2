<?php

namespace App\Controller;

use DateTime;
use App\Entity\BillState;
use Doctrine\ORM\Query\Expr\Func;
use App\Repository\BillRepository;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use App\Repository\MatiereRepository;
use App\Repository\MessageRepository;
use App\Repository\BillLignRepository;
use App\Repository\FonctionRepository;
use App\Repository\BillStateRepository;
use Symfony\Component\BrowserKit\Request;
use App\Repository\EventPlanningRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $billRepo;
    private $billLignRepo;

    function __construct(BillRepository $billRepo,BillLignRepository $billLignRepo)
    {
        $this->billRepo = $billRepo;
        $this->billLignRepo = $billLignRepo;
    }

    /**
     * @Route("/home", name="home")
     */

    public function index(MessageRepository $messageRepo,BillRepository $billRepo, BillStateRepository $billStateRepo): Response
    {

        $roles = $this->getUser()->getRoles();

        foreach($roles as $role) {
            if($role != 'ROLE_USER') {
                $role_user = $role;
            }
        }

        $bills = $billRepo->findByBillState($billStateRepo::STATE_CREATE);

        $currentDate = new DateTime();
       
        $billStateWait = $billStateRepo->findOneById($billStateRepo::STATE_WAIT);        

        foreach ($bills as $bill) {
            if(date_format($bill->getCreatedAt(),'Y-m-d H:i:s') < date('Y-m-1 H:i:s') && date_format($bill->getCreatedAt(),'m') < date('m')) {
                $bill->setBillState($billStateWait);
                $bill->setUpdatedAt($currentDate);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($bill);
                $entityManager->flush();
            }
        }

        $messages = $messageRepo->findByUserTo($this->getUser()->getId());


        if($role_user == 'ROLE_ELEVE') {
            return $this->redirectToRoute('home_eleve');
        } elseif($role_user == 'ROLE_ADMIN') {
            return $this->redirectToRoute('home_admin');
        } elseif($role_user == 'ROLE_PROF') {
            return $this->redirectToRoute('home_prof');
        } elseif($role_user == 'ROLE_COMPTA') {
            return $this->redirectToRoute('home_compta');
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

        $tabLabelsLibelle = array();
        $tabDataMoyenne = array();
        $tabBgColor = array();
        $tabBorderColor = array();

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
     * @Route("/homeprof", name="home_prof")
     */

    public function indexProf(NoteRepository $noteRepo,EventPlanningRepository $eventRepo,MatiereRepository $matiereRepo): Response
    {
       
        $moyenne = $this->calculeMoyennePonderee($noteRepo->findByEleves($this->getUser()));
        $matieres = $matiereRepo->findMatieresByUser($this->getUser());
        $events = $eventRepo->findByFormateur($this->getUser());
        $tabLabelsLibelle = array();
        $tabDataMoyenne = array();
        $tabBgColor = array();
        $tabBorderColor = array();
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

        $globalArray = $this->getGlobalBillLignForChart($this->getUser());

        $totalPackage[] = $globalArray['package'];
        $totalOutPackage[] = $globalArray['out_package'];

        $totalBill = $globalArray['package'] + $globalArray['out_package'];

        return $this->render('home/index.html.twig', [
            'moyenne' => $moyenne,
            'events' => $events,
            'labelsMatieres' => json_encode($tabLabelsLibelle),
            'dataMoyenne' => json_encode($tabDataMoyenne),
            'bgColors' => json_encode($tabBgColor),
            'borderColors' => json_encode($tabBorderColor),
            'totalPackage' => json_encode($totalPackage),
            'totalOutPackage' => json_encode($totalOutPackage),
            'totalBill' => $totalBill
        ]);
    }

    /**
     * @Route("/home/compta", name="home_compta")
     */

    public function indexCompta(UserRepository $userRepo,FonctionRepository $fonctionRepo): Response
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
/**
 * affiche les messages dans la popup du dashboard
 * @Route("/displaymessagepopup", name="message_popup")
 *
 */
    public function displayMessagePopUp(MessageRepository $messageRepo) 
    {   
        $messages = $messageRepo->getMessagesUnread($this->getUser()->getId());
        
        $countMessages = count($messages);

        return $this->render('shared/shared_sidebar/_shared_message_top.html.twig', [
            'messages' => $messages,
            'count' => $countMessages
        ]);

    }

    public function getGlobalBillLignForChart($user)
    {
        $bills = $this->billRepo->findByUser($user);
        $total_package = 0;
        $total_out_package = 0;

        foreach ($bills as $bill) {
            $billLigns = $this->billLignRepo->findByBill($bill);
            foreach ($billLigns as $lign) {

                if ($lign->getPackage() != NULL) {
                    $total_package += $lign->getGlobalLignValue();
                } elseif($lign->getOutPackage() != NULL) {
                    $total_out_package += $lign->getGlobalLignValue();
                }
            }
        }
        
       $globalArray['package'] = $total_package;
       $globalArray['out_package'] = $total_out_package;
    
       return $globalArray;
    }
}
