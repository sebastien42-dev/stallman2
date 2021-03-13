<?php

namespace App\Controller;

use App\Entity\BillLign;
use App\Entity\OutPackage;
use App\Form\OutPackageType;
use App\Repository\BillRepository;
use App\Repository\OutPackageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/out/package")
 */
class OutPackageController extends AbstractController
{
    /**
     * @Route("/", name="out_package_index", methods={"GET"})
     */
    public function index(OutPackageRepository $outPackageRepository): Response
    {
        return $this->render('out_package/index.html.twig', [
            'out_packages' => $outPackageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{bill}", name="out_package_new", methods={"GET","POST"})
     */
    public function new(Request $request,$bill,BillRepository $billRepo): Response
    {
        $oBill = $billRepo->find($bill);
        $billLign = new BillLign();
        $outPackage = new OutPackage();
        $form = $this->createForm(OutPackageType::class, $outPackage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $billLign->setBill($oBill);

            $billLign->setOutPackage($outPackage);

            $billLign->setGlobalLignValue($billLign->getOutPackage()->getValue());

            $oBill->setGlobalBillValue($oBill->getGlobalBillValue()+($billLign->getGlobalLignValue()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($oBill);
            $entityManager->persist($billLign);
            $entityManager->persist($outPackage);
            $entityManager->flush();

            $date = date_format($billLign->getBill()->getCreatedAt(), 'm/Y');
            $this->addFlash('success',"la ligne hors forfait pour {$billLign->getBill()->getUser()->getNom()} {$billLign->getBill()->getUser()->getPrenom()} pour la facture de $date a bien été enregistrée");

            return $this->redirectToRoute('bill_index');
        }

        return $this->render('out_package/new.html.twig', [
            'out_package' => $outPackage,
            'form' => $form->createView(),
            'bill' => $oBill
        ]);
    }

    /**
     * @Route("/{id}", name="out_package_show", methods={"GET"})
     */
    public function show(OutPackage $outPackage): Response
    {
        return $this->render('out_package/show.html.twig', [
            'out_package' => $outPackage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="out_package_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OutPackage $outPackage): Response
    {
        $form = $this->createForm(OutPackageType::class, $outPackage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('out_package_index');
        }

        return $this->render('out_package/edit.html.twig', [
            'out_package' => $outPackage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="out_package_delete", methods={"DELETE"})
     */
    public function delete(Request $request, OutPackage $outPackage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$outPackage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($outPackage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('out_package_index');
    }
}
