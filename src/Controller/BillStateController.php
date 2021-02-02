<?php

namespace App\Controller;

use App\Entity\BillState;
use App\Form\BillStateType;
use App\Repository\BillStateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// TODO limiter cette page aux admin

/**
 * @Route("/bill/state")
 */
class BillStateController extends AbstractController
{
    /**
     * @Route("/", name="bill_state_index", methods={"GET"})
     */
    public function index(BillStateRepository $billStateRepository): Response
    {
        return $this->render('bill_state/index.html.twig', [
            'bill_states' => $billStateRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="bill_state_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $billState = new BillState();
        $form = $this->createForm(BillStateType::class, $billState);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($billState);
            $entityManager->flush();

            return $this->redirectToRoute('bill_state_index');
        }

        return $this->render('bill_state/new.html.twig', [
            'bill_state' => $billState,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bill_state_show", methods={"GET"})
     */
    public function show(BillState $billState): Response
    {
        return $this->render('bill_state/show.html.twig', [
            'bill_state' => $billState,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bill_state_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BillState $billState): Response
    {
        $form = $this->createForm(BillStateType::class, $billState);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bill_state_index');
        }

        return $this->render('bill_state/edit.html.twig', [
            'bill_state' => $billState,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bill_state_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BillState $billState): Response
    {
        //TODO faire un test pour ne pas pouvoir suppirmer si l'etat à été utilisé
        if ($this->isCsrfTokenValid('delete'.$billState->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($billState);
            $entityManager->flush();
        }

        return $this->redirectToRoute('bill_state_index');
    }
}
