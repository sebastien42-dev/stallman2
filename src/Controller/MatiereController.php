<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use App\Repository\FonctionRepository;
use App\Repository\UserRepository;
use App\Repository\MatiereRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/matiere")
 */
class MatiereController extends AbstractController
{
    /**
     * @Route("/", name="matiere_index", methods={"GET"})
     */
    public function index(MatiereRepository $matiereRepository): Response
    {
        return $this->render('matiere/index.html.twig', [
            'matieres' => $matiereRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="matiere_new")
     */
    public function new(Request $request,FonctionRepository $fonctionRepo,UserRepository $userRepo): Response
    {
       
        $formateurs = $userRepo->findByFunction($fonctionRepo::FONCTION_FORMATEUR);
    
        return $this->render('matiere/new.html.twig', [
            'formateurs' => $formateurs
        ]);
    }

    /**
     * @Route("/newsave", name="matiere_new_save", methods={"GET","POST"})
     */
    public function newSave(Request $request,UserRepository $userRepo): Response
    {
        
        $tab_formateur = $request->request->get("formateur_matiere");
        $libelle_matiere = $request->request->get("libelle_matiere");
        $coef = $request->request->get("coefficient");

        $matiere = new Matiere;
        $matiere->setLibelleMatiere($libelle_matiere);
        $matiere->setCoefficient($coef);
       
        foreach($tab_formateur as $formateur) {
            
            $user = $userRepo->findOneById($formateur);
            $matiere->addUser($user);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($matiere);
        $entityManager->flush();

        return $this->redirectToRoute('matiere_index');
    
    }

    /**
     * @Route("/{id}", name="matiere_show", methods={"GET"})
     */
    public function show(Matiere $matiere): Response
    {
        return $this->render('matiere/show.html.twig', [
            'matiere' => $matiere,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="matiere_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Matiere $matiere): Response
    {
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('matiere_index');
        }

        return $this->render('matiere/edit.html.twig', [
            'matiere' => $matiere,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="matiere_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Matiere $matiere): Response
    {
        if ($this->isCsrfTokenValid('delete'.$matiere->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($matiere);
            $entityManager->flush();
        }

        return $this->redirectToRoute('matiere_index');
    }
}
