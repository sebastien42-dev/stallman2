<?php

namespace App\Controller;

use App\Entity\EventPlanning;
use App\Form\EventPlanningType;
use App\Repository\SalleRepository;
use App\Repository\ClasseRepository;
use App\Repository\MatiereRepository;
use App\Repository\EventPlanningRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("/calendar", name="event_planning_calendar", methods={"GET"})
     */
    public function calendar(EventPlanningRepository $eventPlanningRepository,SalleRepository $salleRepo,ClasseRepository $classeRepo,MatiereRepository $matiereRepo): Response
    {
        $events = $eventPlanningRepository->findAll();
        $tab_events =[];
        foreach($events as $event) {
            $salle = $salleRepo->findOneById($event->getSalles());
            $matiere =$matiereRepo->findOneById($event->getMatieres());
            $classe = $classeRepo->findOneById($event->getClasses());
            $descr = 'salle : '.$salle->getLibelleSalle().' - classe : '.$classe->getLibelleClasse();
            $tab_events[]=[
                'id' => $event->getId(),
                'title' => $event->getTitle().' '.$descr,
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'borderColor' => $matiere->getEventBorderColor(),
                'backgroundColor' => $matiere->getEventBackgroundColor(),
                'textColor' => $matiere->getEventTextColor(),
            ];
        }
        $data = json_encode($tab_events);
        return $this->render('event_planning/calendar.html.twig',compact('data'));
    }

    /**
     * @Route("/admin/new", name="event_planning_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        //TODO setter par défaut la date de fin a la date du début, ne pouvoir que changer l'heure
        $eventPlanning = new EventPlanning();
        $form = $this->createForm(EventPlanningType::class, $eventPlanning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($eventPlanning);
            $entityManager->flush();

            $this->addFlash("success","Le nouvel cours de {$eventPlanning->getMatieres()->getLibelleMatiere()} pour la classe {$eventPlanning->getClasses()->getLibelleClasse()} à bien été ajouté au planning");

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
     * @Route("/admin/{id}/edit", name="event_planning_edit", methods={"GET","POST"})
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
     * @Route("/admin/{id}", name="event_planning_delete", methods={"DELETE"})
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
