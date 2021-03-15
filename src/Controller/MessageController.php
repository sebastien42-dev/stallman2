<?php

namespace App\Controller;

use DateTime;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/message")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/", name="message_index", methods={"GET"})
     */
    public function index(MessageRepository $messageRepository): Response
    {
        return $this->render('message/index.html.twig', [
            'messages' => $messageRepository->findByUserTo($this->getUser()->getId()),
            'messagesSend' => $messageRepository->findByUserFrom($this->getUser()->getId()),
        ]);
    }

    /**
     * @Route("/new", name="message_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserRepository $userRepo): Response
    {
        $user = $userRepo->find($this->getUser());
        $message = new Message();
        $date = new DateTime();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        
        $message->setUserFrom($user);

        if ($form->isSubmitted() && $form->isValid()) {
            // on sette la date du moment de l'envoie par défaut
            $message->setDateSend($date);
            $message->setIsRead(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash("success","Votre message a bien été envoyé à  {$message->getUserTo()->getNom()}  {$message->getUserTo()->getPrenom()} (aller dans 'messages envoyés' pour voir s'il a été lu par le destinataire");

            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/new.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /** retourne le formulaire de nouveau message pour un user précis
     * @Route("/new/user/{user_id}", name="message_new_user", methods={"GET","POST"})
     */
    public function newByUser(Request $request,UserRepository $userRepo,$user_id): Response
    {
        $user = $userRepo->find($this->getUser());
        $userTo = $userRepo->find($user_id);

        $message = new Message();
        $date = new DateTime();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        
        $message->setUserFrom($user);
        $message->setUserTo($userTo);

        if ($form->isSubmitted() && $form->isValid()) {
            // on sette la date du moment de l'envoie par défaut
           
            $message->setDateSend($date);
            $message->setIsRead(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/new_by_user.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }


    /**
     * retourne le contenu du message en ajax
     * @Route("/ajaxDisplayMessage", name="ajaxDisplayMessage", methods={"POST"})
     */
    public function ajaxDisplayMessage(Request $request, MessageRepository $messageRepo) {

        $message = $messageRepo->find($request->request->get("val"));
        
		$reponse = new Response(json_encode(
            array(
                'title' => $message->getTitle(),
                'content' => $message->getContent(),
                'id' => $request->request->get("val")
            )
        ));
        return $reponse;
    }

    /**
     * @Route("/{id}", name="message_show", methods={"GET"})
     */
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="message_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Message $message): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/edit.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="message_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Message $message): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('message_index');
    }

    /**
     * marque les message comme lu
     * @Route("/{id}/markMailRead", name="mail_read",methods={"GET","POST"})
     */
    public function markMailRead(Message $message)
    {
        $message->setIsRead(1);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($message);
        $entityManager->flush();

        return $this->redirectToRoute('message_index');

    }
}
