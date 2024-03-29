<?php

namespace App\Controller;

use DateTime;
use App\Entity\Bill;
use App\Entity\BillLign;
use App\Form\BillType;
use App\Entity\BillState;
use App\Entity\OutPackage;
use App\Form\BillTypeLight;
use App\Repository\BillLignRepository;
use App\Repository\BillRepository;
use App\Repository\BillStateRepository;
use App\Repository\PackageRepository;
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
        $nbOutPackage = $request->request->get("countOutPackage");
        
        if ($form->isSubmitted() && $form->isValid()) {

            $bill->setCreatedAt(new DateTime());
            $bill->setUser($this->getUser());
            $bill->setBillState($this->billStateCreated);
            //TODO tester si la quantité est supérieure à 0 (assert)
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bill);
            $total = 0;

            foreach ($bill->getBillLigns() as $lign) {
                $lign->setBill($bill);
                $lign->setGlobalLignValue($lign->getQuantity()*$lign->getPackage()->getValue());
                $total += $lign->getGlobalLignValue();
            }

            if($nbOutPackage>0) {
                for ($i=0; $i < $nbOutPackage; $i++) {

                    if($request->request->get("outpackageDate".$i) != "" && $request->request->get("outpackageValue".$i) != "" && $request->request->get("outPackageName".$i) !="" ) {

                        $date = new DateTime($request->request->get("outpackageDate".$i));
                        $outPackage = new OutPackage();
                        $outPackage->setOutPackageName( $request->request->get("outPackageName".$i));
                        $outPackage->setValue($request->request->get("outpackageValue".$i));
                        $entityManager->persist($outPackage);
                        $billLignOut = new BillLign();
                        $billLignOut->setOutPackage($outPackage);
                        $billLignOut->setCreatedAt($date);
                        $billLignOut->setGlobalLignValue($outPackage->getValue());
                        $billLignOut->setBill($bill);
                        $entityManager->persist($billLignOut);
                        $total += $outPackage->getValue();
                        
                    } else {
                        $this->addFlash('danger','une ou des lignes hors forfait n\'ont pas pu être ajoutées car une des 3 champs demandés était vide. Vous pouvez le / les rajouter ci dessous ');
                    }
                        
                }
            }

            $bill->setGlobalBillValue($total);
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

    // /**
    //  * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA') or is_granted('ROLE_PROF')")
    //  * @Route("/{id}/edit", name="bill_edit", methods={"GET","POST"})
    //  */
    // public function edit(Request $request, Bill $bill): Response
    // {
    //     $form = $this->createForm(BillTypeLight::class, $bill);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('bill_index');
    //     }

    //     return $this->render('bill/editBillName.html.twig', [
    //         'bill' => $bill,
    //         'form' => $form->createView(),
    //     ]);
    // }



    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA') or is_granted('ROLE_PROF')")
     * @Route("/{id}/edit/save", name="bill_edit_save", methods={"GET","POST"})
     */
    public function edit(Request $request, Bill $bill,BillLignRepository $billLignRepo,PackageRepository $packageRepo): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($bill->getUser() != $this->getUser()) {
            return $this->redirectToRoute('bill_index');
        } else {


            if ($request->get('num_bill') != $bill->getBillProviderNum()) {
                $bill->setBillProviderNum($request->get('num_bill'));
                $entityManager->persist($bill);
                $entityManager->flush();
            }
            
            $billLigns = $bill->getBillLigns();
            
            $totalBill = 0;
            
            foreach ($billLigns as $lign) {
                
                $package = $packageRepo->findOneById($request->get('package_name_'.$lign->getId()));
                $date = new DateTime($request->get('package_date_'.$lign->getId()));
                
                $lign->setQuantity($request->get('package_quantity_'.$lign->getId()));
                $lign->setPackage($package);
                $lign->setCreatedAt($date);
                
                if($lign->getPackage() != NULL) {
                    $lign->setGlobalLignValue($package->getValue()*$request->get('package_quantity_'.$lign->getId()));
                    $totalBill += $lign->getGlobalLignValue();
                }
                
                if($lign->getOutPackage() != NULL) {
                    $lign->getOutPackage()->setOutPackageName($request->get('outpackage_name_'.$lign->getId()));
                    $lign->getOutPackage()->setValue($request->get('outpackage_value_'.$lign->getId()));
                    $lign->setGlobalLignValue($request->get('outpackage_value_'.$lign->getId()));
                    $totalBill += $lign->getGlobalLignValue();
                }
                
                $entityManager->persist($lign);
                $entityManager->flush();
            }
            
            $bill->setGlobalBillValue($totalBill);
            $entityManager->persist($bill);
            $entityManager->flush();

            $this->addFlash('success','la facture '.$bill->getBillProviderNum().' a bien été modifiée');
            return $this->redirectToRoute('bill_index');
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA') or is_granted('ROLE_PROF')")
     * @Route("/{id}/edit", name="bill_edit", methods={"GET","POST"})
     */
    public function displayEditBill(Bill $bill,PackageRepository $packageRepo )
    {
        $packages = $packageRepo->findAll();

        if ($bill->getUser() != $this->getUser()) {

            return $this->render('error/error_bill_edit_profil.html.twig');

        } elseif(($bill->getBillState()->getStateName() != BillStateRepository::STR_STATE_CREATE) && ($bill->getBillState()->getStateName() != BillStateRepository::STR_STATE_WAIT)) {

            return $this->render('error/error_bill_edit_state.html.twig');

        } else {

            return $this->render('bill/editBillName.html.twig', [
                'bill' => $bill,
                'packages' => $packages
            ]);
        }
    }


    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA') or is_granted('ROLE_PROF')")
     * @Route("/{id}/edit/bill/name", name="bill_edit_name", methods={"GET","POST"})
     */
    public function DisplayEditBillName(Request $request, Bill $bill,BillRepository $billRepo,$id,BillLignRepository $billLignRepo): Response
    {
        $bill = $billRepo->find($id);
        
        return $this->render('bill/editBillName.html.twig', [
            'bill' => $bill,
        ]);
    }

    
    // /**
    //  * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA') or is_granted('ROLE_PROF')")
    //  * @Route("/{id}/edit/bill/name", name="bill_edit_name", methods={"GET","POST"})
    //  */
    // public function editBillName(Request $request, Bill $bill,BillRepository $billRepo,$id): Response
    // {
        
    //     $bill = $billRepo->find($id);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('bill_index');
    //     }

    //     return $this->render('bill/edit.html.twig', [
    //         'bill' => $bill,
    //         'form' => $form->createView(),
    //     ]);
    // }

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
