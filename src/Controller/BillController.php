<?php

namespace App\Controller;

use DateTime;
use App\Entity\Bill;
use App\Form\BillType;
use App\Entity\BillState;
use App\Repository\BillLignRepository;
use App\Repository\BillRepository;
use App\Repository\BillStateRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/bill")
 */
class BillController extends AbstractController
{
    public $billStateCreated;
    public $billStateWait;
    public $billStateValidate;
    public $billStatePaid;

    public function __construct(BillStateRepository $billStateRepo)
    {
        $this->billStateCreated = $billStateRepo->find($billStateRepo::STATE_CREATE);
        $this->billStateWait = $billStateRepo->find($billStateRepo::STATE_WAIT);
        $this->billStateValidate = $billStateRepo->find($billStateRepo::STATE_VALIDATE);
        $this->billStatePaid = $billStateRepo->find($billStateRepo::STATE_PAID);
    }
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA') or is_granted('ROLE_PROF')")
     * @Route("/", name="bill_index", methods={"GET"})
     */
    public function index(BillRepository $billRepository): Response
    {
        $roles = $this->getUser()->getRoles();
        foreach ($roles as $role) {
            if($role != 'ROLE_USER') {
                $role_user = $role ;
            }
        }

        if($role_user == "ROLE_PROF" || $role_user == "ROLE_USER" || $role_user == "ROLE_ELEVE" ) {
            $bills = $billRepository->findByUser($this->getUser());
        } else {
            $bills = $billRepository->findAll();
        }
    
        return $this->render('bill/index.html.twig', [
            'bills' => $bills,
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA') or is_granted('ROLE_PROF')")
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

            $this->addFlash('success','La nouvelle facture a bien été créée');

            return $this->redirectToRoute('bill_index');
        }

        return $this->render('bill/new.html.twig', [
            'bill' => $bill,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA') or is_granted('ROLE_PROF')")
     * @Route("/{id}", name="bill_show", methods={"GET"},requirements={"id":"\d+"})
     */
    public function show(Bill $bill): Response
    {
        return $this->render('bill/show.html.twig', [
            'bill' => $bill,
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA') or is_granted('ROLE_PROF')")
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
     * @IsGranted("ROLE_ADMIN",message="Accès réservé aux administrateurs !")
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

    /**
     * @IsGranted("ROLE_COMPTA",message="Accès réservé aux comptables !")
     * @Route("/bill/validate/{id_bill}", name="bill_validate")
     */
    public function billValidate(BillRepository $billRepo, $id_bill) 
    {
        //TODO faire un test sur les outpackage, si pas de preuves erreur pas possible de valider

        $bill = $billRepo->find($id_bill);
        $bill->setBillState($this->billStateValidate);
        $bill->setUpdatedAt(new DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($bill);
        $entityManager->flush();

        return $this->redirectToRoute('bill_index');
    }

    /** change l'etat de facture a en attente
     * @IsGranted("ROLE_COMPTA",message="Accès réservé aux comptables !")
     * @Route("/bill/wait/{id_bill}", name="bill_wait")
     */
    public function billWait(BillRepository $billRepo, $id_bill) 
    {
        $bill = $billRepo->find($id_bill);
        $bill->setBillState($this->billStateWait);
        $bill->setUpdatedAt(new DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($bill);
        $entityManager->flush();

        return $this->redirectToRoute('bill_index');
    }

    /** change l'etat de facture a en attente
     * @IsGranted("ROLE_COMPTA",message="Accès réservé aux comptables !")
     * @Route("/bill/paid/{id_bill}", name="bill_paid")
     */
    public function billPaid(BillRepository $billRepo, $id_bill) 
    {
        //TODO faire un champs pour le reglement et la date de regelement et le mode. ou autre table
        $bill = $billRepo->find($id_bill);
        $bill->setBillState($this->billStatePaid);
        $bill->setUpdatedAt(new DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($bill);
        $entityManager->flush();

        return $this->redirectToRoute('bill_index');
    }


    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA') or is_granted('ROLE_PROF')")
     * @Route("/bill/showall/{id_bill}", name="bill_show_all")
     */
    public function billShowAll(BillRepository $billRepo,BillLignRepository $billLignRepo, $id_bill) 
    {
        $bill = $billRepo->find($id_bill);
        $billLigns = $billLignRepo->findByBill($bill->getId());

        return $this->render('bill/show_all.html.twig', [
            'bill' => $bill,
            'billLigns' => $billLigns
        ]);
    }
}
