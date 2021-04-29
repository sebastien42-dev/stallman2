<?php

namespace App\Controller;

use App\Repository\ClasseRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_PROF",message="Accès réservé aux Formateurs !")
 * @Route("/prof")
 */
class ClasseProfController extends AbstractController
{
    /**
     * @Route("/classe", name="index_classe_prof", methods={"GET"})
     */
    public function indexProf(ClasseRepository $classeRepo): Response
    {
        return $this->render('classe/index_prof.html.twig',[
            'classes' => $this->getUser()->getCLasses()
            ]
        );
    }
}
