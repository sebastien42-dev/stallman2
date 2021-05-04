<?php

namespace App\Controller;

use App\Entity\Proof;
use App\Form\ProofType;
use App\Repository\BillLignRepository;
use App\Repository\BillRepository;
use App\Repository\OutPackageRepository;
use App\Repository\ProofRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/proof")
 */
class ProofController extends AbstractController
{
    /**
     * @Route("/", name="proof_index", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA') or is_granted('ROLE_PROF')")
     */
    public function index(ProofRepository $proofRepository,BillRepository $billRepo,BillLignRepository $billLignRepo,OutPackageRepository $outPackageRepo): Response
    {
        $roles = $this->getUser()->getRoles();

        foreach($roles as $role) {
            if($role != 'ROLE_USER') {
                $role_user = $role;
            }
        }
        $proofs=array();
        if($role_user == "ROLE_ADMIN" || $role_user == "ROLE_COMPTA") {
            $proofs = $proofRepository->findAll();
        } else {
            $bills = $billRepo->findByUser($this->getUser());
            $billLigns = $billLignRepo->findByBill($bills);
            
            foreach ($billLigns as $lign) {
               if( $lign->getOutPackage() != null) {
                    if($lign->getOutPackage()->getProof() != null ) {
                        $proofs[]=$lign->getOutPackage()->getProof();
                    }
                }
                
            }
        }
        return $this->render('proof/index.html.twig', [
            'proofs' => $proofs,
        ]);
    }

    /**
     * @Route("/new/{id_out_package}", name="proof_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA') or is_granted('ROLE_PROF')")
     */
    public function new(Request $request,SluggerInterface $slugger,$id_out_package,OutPackageRepository $outPackageRepo): Response
    {
        $proof = new Proof();
        $form = $this->createForm(ProofType::class, $proof);
        $form->handleRequest($request);

        $outPackage = $outPackageRepo->find($id_out_package);

        

        if ($form->isSubmitted() && $form->isValid()) {

            if($outPackage->getProof() !== null) {

                return $this->render('error/error_proof_exist.html.twig');

            } else{
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
                
                $outPackage->setProof($proof);
                $entityManager->persist($outPackage);

                $entityManager->flush();
                $this->addFlash("success","le justificatif a bien été enregistré");
                return $this->redirectToRoute('proof_index');
            }
            
        }

        return $this->render('proof/new.html.twig', [
            'proof' => $proof,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proof_show", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA') or is_granted('ROLE_PROF')")
     */
    public function show(Proof $proof): Response
    {
        return $this->render('proof/show.html.twig', [
            'proof' => $proof,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="proof_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA') or is_granted('ROLE_PROF')")
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
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA') or is_granted('ROLE_PROF')")
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
