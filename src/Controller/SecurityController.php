<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\FonctionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/")
 */
class SecurityController extends AbstractController
{
     /**
     * @Route("/", name="app_home")
     */
    public function appHome (UserRepository $userRepo,FonctionRepository $fonctionRepo): Response
    {
        return $this->render('home/index.html.twig', [
            'nbEleve' => count($userRepo->findByFunction($fonctionRepo::FONCTION_ELEVE)),
            'nbFormateur' => count($userRepo->findByFunction($fonctionRepo::FONCTION_FORMATEUR)),
            'nbComptable' => count($userRepo->findByFunction($fonctionRepo::FONCTION_COMPTABLE)),
            'nbAdmin' => count($userRepo->findByFunction($fonctionRepo::FONCTION_ADMIN)),
        ]);
    }


    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('home');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        return $this->render('security/login.html.twig');
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
