<?php

namespace App\Controller;

use App\Entity\Proof;
use App\Form\ProofType;
use App\Repository\ProofRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/proof")
 */
class ProofController extends AbstractController
{
    /**
     * @Route("/", name="proof_index", methods={"GET"})
     */
    public function index(ProofRepository $proofRepository): Response
    {
        return $this->render('proof/index.html.twig', [
            'proofs' => $proofRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="proof_new", methods={"GET","POST"})
     */
    public function new(Request $request,SluggerInterface $slugger): Response
    {
        $proof = new Proof();
        $form = $this->createForm(ProofType::class, $proof);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            ////////////////////////
                        $proofFile = $form->get('proofFile')->getData();

                        // this condition is needed because the 'brochure' field is not required
                        // so the PDF file must be processed only when a file is uploaded
                        if ($proofFile) {
                            $originalFilename = pathinfo($proofFile->getClientOriginalName(), PATHINFO_FILENAME);
                            // this is needed to safely include the file name as part of the URL
                            $safeFilename = $slugger->slug($originalFilename);
                            $newFilename = $safeFilename.'-'.uniqid().'.'.$proofFile->guessExtension();
            
                            // Move the file to the directory where brochures are stored
                            try {
                                $proofFile->move(
                                    $this->getParameter('proof_directory'),
                                    $newFilename
                                );
                            } catch (FileException $e) {
                                echo'toto';
                            }
            
                            // updates the 'brochureFilename' property to store the PDF file name
                            // instead of its contents
                            $proof->setProofFile($newFilename);
                        }
            //////////////////////////

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proof);
            $entityManager->flush();
            $this->addFlash("success","le justificatif a bien été enregistré");
            return $this->redirectToRoute('proof_index');
        }

        return $this->render('proof/new.html.twig', [
            'proof' => $proof,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proof_show", methods={"GET"})
     */
    public function show(Proof $proof): Response
    {
        return $this->render('proof/show.html.twig', [
            'proof' => $proof,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="proof_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Proof $proof): Response
    {
        $form = $this->createForm(ProofType::class, $proof);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('proof_index');
        }

        return $this->render('proof/edit.html.twig', [
            'proof' => $proof,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proof_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Proof $proof): Response
    {
        //TODO bien rajouter la suppresion du fichier dans le dossier upload
        if ($this->isCsrfTokenValid('delete'.$proof->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($proof);
            $entityManager->flush();
        }

        return $this->redirectToRoute('proof_index');
    }
}
