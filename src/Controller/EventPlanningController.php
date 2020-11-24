<?php

namespace App\Controller;

use App\Entity\EventPlanning;
use App\Form\EventPlanningType;
use App\Repository\EventPlanningRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event/planning")
 */
class EventPlanningController extends AbstractController
{
    /**
     * @Route("/", name="event_planning_index", methods={"GET"})
     */
    public function index(EventPlanningRepository $eventPlanningRepository): Response
    {
        return $this->render('event_planning/index.html.twig', [
            'event_plannings' => $eventPlanningRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="event_planning_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $eventPlanning = new EventPlanning();
        $form = $this->createForm(EventPlanningType::class, $eventPlanning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($eventPlanning);
            $entityManager->flush();

            return $this->redirectToRoute('event_planning_index');
        }

        return $this->render('event_planning/new.html.twig', [
            'event_planning' => $eventPlanning,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_planning_show", methods={"GET"})
     */
    public function show(EventPlanning $eventPlanning): Response
    {
        return $this->render('event_planning/show.html.twig', [
            'event_planning' => $eventPlanning,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="event_planning_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EventPlanning $eventPlanning): Response
    {
        $form = $this->createForm(EventPlanningType::class, $eventPlanning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_planning_index');
        }

        return $this->render('event_planning/edit.html.twig', [
            'event_planning' => $eventPlanning,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_planning_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EventPlanning $eventPlanning): Response
    {
        if ($this->isCsrfTokenValid('delete'.$eventPlanning->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($eventPlanning);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_planning_index');
    }
}
