<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



    /**
     * @Route("/api", name="java_api")
     */
class JavaApiController extends AbstractController
{
    // /**
    //  * @Route("/java/api", name="java_api")
    //  */
    // public function index(): Response
    // {
    //     return $this->render('java_api/index.html.twig', [
    //         'controller_name' => 'JavaApiController',
    //     ]);
    // }

    /**
     * retourne la liste des messages du user concerné
     *
     * @Route("/message/list/{user_id}", name="api_get_message",methods={"POST","GET"})
     * @return JSON
     */
    public function apiGetMessage(MessageRepository $messageRepo,$user_id)
    {
        $data = $messageRepo->findByUserTo($user_id);
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        return JsonResponse::fromJsonString($serializer->serialize($data, 'json',SerializationContext::create()->enableMaxDepthChecks()));

    }


}
