<?php

namespace App\Controller;

use App\Entity\BillLign;
use App\Form\BillLignType;
use App\Repository\BillRepository;
use App\Repository\BillLignRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
//TODO les champs globalValue dans les lignes et bill sont des champs calculés ATTENTION DONC DANS DELETE ET UPDATE !
/**
 * @Route("/bill/lign")
 */
class BillLignController extends AbstractController
{
    /**
     * @Route("/", name="bill_lign_index", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA')")
     */
    public function index(BillLignRepository $billLignRepository,BillRepository $billRepo): Response
    {
       
        $roles = $this->getUser()->getRoles();

        foreach($roles as $role) {
            if($role != 'ROLE_USER') {
                $role_user = $role;
            }
        }
        
        if($role_user == "ROLE_ADMIN" || $role_user == "ROLE_COMPTA") {
            $billLigns = $billLignRepository->findAll();
        } else {
            $bills = $billRepo->findByUser($this->getUser());
            $billLigns = $billLignRepository->findByBill($bills);
        }

        return $this->render('bill_lign/index.html.twig', [
            'bill_ligns' => $billLigns,
        ]);
    }

    /**
     * @Route("/new/{bill}", name="bill_lign_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA')  or is_granted('ROLE_PROF')")
     */
    public function new(Request $request,$bill,BillRepository $billRepo): Response
    {
        $oBill = $billRepo->find($bill);
        $billLign = new BillLign();
        $form = $this->createForm(BillLignType::class, $billLign);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $billLign->setBill($oBill);
            $billLign->setGlobalLignValue($billLign->getQuantity()*$billLign->getPackage()->getValue());

            $oBill->setGlobalBillValue($oBill->getGlobalBillValue()+($billLign->getQuantity()*$billLign->getPackage()->getValue()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($billLign);
            $entityManager->persist($oBill);
            $entityManager->flush();

            $date = date_format($billLign->getBill()->getCreatedAt(), 'm/Y');

            $this->addFlash('success',"la ligne forfait pour {$billLign->getBill()->getUser()->getNom()} {$billLign->getBill()->getUser()->getPrenom()} pour la facture de $date a bien été enregistrée");

            return $this->redirectToRoute('bill_index');
        }

        return $this->render('bill_lign/new.html.twig', [
            'bill_lign' => $billLign,
            'form' => $form->createView(),
            'bill' => $oBill
        ]);
    }

    /**
     * @Route("/{id}", name="bill_lign_show", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA')  or is_granted('ROLE_PROF')")
     */
    public function show(BillLign $billLign): Response
    {
        return $this->render('bill_lign/show.html.twig', [
            'bill_lign' => $billLign,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bill_lign_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA')  or is_granted('ROLE_PROF')")
     */
    public function edit(Request $request, BillLign $billLign,BillRepository $billRepo): Response
    {
        $total = 0;
        $bill = $billLign->getBill();

        $form = $this->createForm(BillLignType::class, $billLign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $billLign->setGlobalLignValue($billLign->getQuantity()*$billLign->getPackage()->getValue());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($billLign);
            $billLigns = $bill->getBillLigns();

            foreach ($billLigns as $lign) {
                $total += $lign->getGlobalLignValue();
            }
            $bill->setGlobalBillValue($total);
            $entityManager->persist($bill);
            $entityManager->flush();

            return $this->redirectToRoute('bill_index');
        }

        return $this->render('bill_lign/edit.html.twig', [
            'bill_lign' => $billLign,
            'form' => $form->createView(),
            'bill' => $bill
        ]);
    }

    /**
     * @Route("/{id}", name="bill_lign_delete", methods={"DELETE"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA')  or is_granted('ROLE_PROF')")
     */
    public function delete(Request $request, BillLign $billLign): Response
    {
        if ($this->isCsrfTokenValid('delete'.$billLign->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($billLign);
            $entityManager->flush();
        }

        return $this->redirectToRoute('bill_lign_index');
    }
}
