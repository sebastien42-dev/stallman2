<?php

namespace App\Controller;

use DateTime;
use App\Entity\Bill;
use App\Form\BillType;
use App\Entity\BillState;
use App\Repository\BillRepository;
use App\Repository\BillStateRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// TODO limiter cette page aux admin et compta 
/**
 * @Route("/bill")
 */
class BillController extends AbstractController
{
    public $billStateCreated;

    public function __construct(BillStateRepository $billStateRepo)
    {
        $this->billStateCreated = $billStateRepo->find($billStateRepo::STATE_CREATE);
    }
    /**
     * @Route("/", name="bill_index", methods={"GET"})
     */
    public function index(BillRepository $billRepository): Response
    {
        //TODO tester quel user connecter pour afficher que les factures concernées
        return $this->render('bill/index.html.twig', [
            'bills' => $billRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="bill_new", methods={"GET","POST"})
     */
    public function new(Request $request,BillRepository $billRepo): Response
    {
        if(count($billRepo->findByCreatedAtAndUser(date('Y-m'),$this->getUser()->getId())) > 0) {
            return $this->render('error/error_bill_exist.html.twig');
        }

        $bill = new Bill();
        $form = $this->createForm(BillType::class, $bill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bill->setCreatedAt(new DateTime());
            $bill->setUser($this->getUser());
            $bill->setBillState($this->billStateCreated);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bill);
            $entityManager->flush();

            return $this->redirectToRoute('bill_index');
        }

        return $this->render('bill/new.html.twig', [
            'bill' => $bill,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bill_show", methods={"GET"},requirements={"id":"\d+"})
     */
    public function show(Bill $bill): Response
    {
        return $this->render('bill/show.html.twig', [
            'bill' => $bill,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bill_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Bill $bill): Response
    {
        $form = $this->createForm(BillType::class, $bill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bill_index');
        }

        return $this->render('bill/edit.html.twig', [
            'bill' => $bill,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bill_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Bill $bill): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bill->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bill);
            $entityManager->flush();
        }

        return $this->redirectToRoute('bill_index');
    }
}
