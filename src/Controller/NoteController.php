<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use App\Repository\ClasseRepository;
use App\Repository\MatiereRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/note")
 */
class NoteController extends AbstractController
{
    /**
     * @Route("/", name="note_index", methods={"GET"})
     */
    public function index(NoteRepository $noteRepository): Response
    {
        if($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_PROF')) {
                return $this->render('note/index.html.twig', [
                    'notes' => $noteRepository->findAll(),
                ]);
        }else{
                $note_user = $noteRepository->findByEleves($this->getUser());
                return $this->render('note/index.html.twig', [
                    'notes' => $note_user,
                ]);

        }
        
    }

     /**
     * @Route("/newprev", name="note_new_prev", methods={"GET","POST"})
     */
    public function newPrev(Request $request, UserRepository $userRepo, ClasseRepository $classeRepo): Response
    {
         $classes = $this->getUser()->getClasses();
         $matieres = $this->getUser()->getMatiere();

         if(count($classes)>0 && count($matieres)>0) {
            return $this->render('note/new_prev.html.twig',[
                'classes' => $classes,
                'matieres' => $matieres
            ]);
         }else{
            return $this->render('error/error_note_prev.html.twig');
         }
        
    }

    /**
     * @Route("/valideprevnote", name="valide_prev_note", methods={"GET","POST"})
     */
    public function ValidePrevNote(Request $request): Response
    {
        $date_note = \DateTime::createFromFormat('Y-m-d', $request->get('selected_date'));

        $request->getSession()->set('selected_classe',$request->get('selected_classe'));
        $request->getSession()->set('selected_matiere',$request->get('selected_matiere'));
        $request->getSession()->set('selected_date',$date_note);
        $request->getSession()->set('selected_libelle',$request->get('selected_libelle'));

        if ($request->get('selected_coefficient')!='') 
        {
            if ($request->get('selected_coefficient') < 9 && $request->get('selected_coefficient') > 1) 
            {
                $request->getSession()->set('selected_coefficient',$request->get('selected_coefficient'));
            }else{
                $request->getSession()->set('selected_coefficient',1);
            }
            
        }else{
            $request->getSession()->set('selected_coefficient',1);
        }
        
            return $this->redirectToRoute('note_new');
        
    }


    /**
     * @Route("/new", name="note_new", methods={"GET","POST"})
     */
    public function new(Request $request,ClasseRepository $classeRepo): Response
    {
        if($request->getSession()->get('selected_classe')!=null) {
            $classe = $classeRepo->findOneById($request->getSession()->get('selected_classe'));
            $eleves = $classe->getUsers();
            return $this->render('note/new.html.twig',[
                'eleves' =>$eleves
            ]);
        }else{
            return $this->render('note/new.html.twig');
        }
        
    }

     /**
     * @Route("/newsave", name="note_new_save", methods={"GET","POST"})
     */
    public function newSave(Request $request,MatiereRepository $matiereRepo,UserRepository $userRepo): Response
    {
       
        $notes = $request->get('notes');
        $commentaires = $request->get('commentaires');
        $eleves = $request->get('eleves');
        //TODO rajouter un try catch ou une gestion de l'erreur si $note vide
        for ($i=0;$i<count($notes);$i++) 
        {
            $notetmp = $notes[$i];
            $elevetmp = $eleves[$i];
            $commentairetmp = $commentaires[$i];
           
            $matiere = $matiereRepo->find((int) $request->getSession()->get('selected_matiere'));
            $eleve = $userRepo->find((int) $elevetmp );
            
            $note = new Note();
            $note->setMatieres($matiere);
            $note->setLibelleNote($request->getSession()->get('selected_libelle'));
            $note->setDateNote($request->getSession()->get('selected_date'));
            $note->setCoefficient($request->getSession()->get('selected_coefficient'));

            $note->setCommentaire($commentairetmp);
            $note->setNote($notetmp);
            $note->setEleves($eleve);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($note);
            $entityManager->flush();
            
        }

        return $this->redirectToRoute('note_index');
    }

    /**
     * @Route("/{id}", name="note_show", methods={"GET"})
     */
    public function show(Note $note): Response
    {
        return $this->render('note/show.html.twig', [
            'note' => $note,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="note_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Note $note): Response
    {
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('note_index');
        }

        return $this->render('note/edit.html.twig', [
            'note' => $note,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="note_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Note $note): Response
    {
        if ($this->isCsrfTokenValid('delete'.$note->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($note);
            $entityManager->flush();
        }

        return $this->redirectToRoute('note_index');
    }
}
