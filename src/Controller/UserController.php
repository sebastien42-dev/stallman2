<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\FonctionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA')")
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository,FonctionRepository $fonctionRepo): Response
    {
        $eleves = $userRepository->findByFunction($fonctionRepo::FONCTION_ELEVE);
        $formateurs = $userRepository->findByFunction($fonctionRepo::FONCTION_FORMATEUR);
        $comptables = $userRepository->findByFunction($fonctionRepo::FONCTION_COMPTABLE);
        $admins = $userRepository->findByFunction($fonctionRepo::FONCTION_ADMIN);

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'eleves' => $eleves,
            'formateurs' => $formateurs,
            'comptables' => $comptables,
            'admins' => $admins
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN",message="Accès réservé aux administrateurs !")
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * 
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $date = new DateTime();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $passwordEncoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $user->setDateCreation($date);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA')")
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTA')")
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $passwordEncoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN",message="Accès réservé aux administrateurs !")
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
