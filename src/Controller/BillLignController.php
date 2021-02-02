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

/**
 * @Route("/bill/lign")
 */
class BillLignController extends AbstractController
{
    /**
     * @Route("/", name="bill_lign_index", methods={"GET"})
     */
    public function index(BillLignRepository $billLignRepository): Response
    {
        return $this->render('bill_lign/index.html.twig', [
            'bill_ligns' => $billLignRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{bill}", name="bill_lign_new", methods={"GET","POST"})
     */
    public function new(Request $request,$bill,BillRepository $billRepo): Response
    {
        $oBill = $billRepo->find($bill);
        $billLign = new BillLign();
        $form = $this->createForm(BillLignType::class, $billLign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $billLign->setBill($oBill);
            $billLign->setGlobalLignValue(1000);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($billLign);
            $entityManager->flush();

            return $this->redirectToRoute('bill_lign_index');
        }

        return $this->render('bill_lign/new.html.twig', [
            'bill_lign' => $billLign,
            'form' => $form->createView(),
            'bill' => $oBill
        ]);
    }

    /**
     * @Route("/{id}", name="bill_lign_show", methods={"GET"})
     */
    public function show(BillLign $billLign): Response
    {
        return $this->render('bill_lign/show.html.twig', [
            'bill_lign' => $billLign,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bill_lign_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BillLign $billLign): Response
    {
        $form = $this->createForm(BillLignType::class, $billLign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bill_lign_index');
        }

        return $this->render('bill_lign/edit.html.twig', [
            'bill_lign' => $billLign,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bill_lign_delete", methods={"DELETE"})
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
