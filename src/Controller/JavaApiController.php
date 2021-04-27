<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    /**
     * @Route("/api", name="java_api")
     */
class JavaApiController extends AbstractController
{
    
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

    // /**
    //  * permet de tester la connexion à partir de app_stallman
    //  *
    //  * @Route("/login/{email}/{pwd}", name="api_login",methods={"POST","GET"})
    //  * @return void
    //  */
    // public function apiLogin(UserRepository $userRepo,$email,$pwd)
    // {
    //     //TODO passer cette methode en post parce que là suprême de crassou
    //     $user = $userRepo->findOneByEmail(["email"=>$email,"password"=>$pwd]);
    //     if(!is_null($user)) {
    //         $userData["nom"]=$user->getNom();
    //         $userData["prenom"]=$user->getPrenom();
    //         $userData["id"]=$user->getId();
    //         $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
    //         return JsonResponse::fromJsonString($serializer->serialize($userData, 'json'));   
    //     } else {
    //         $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
    //         return JsonResponse::fromJsonString($serializer->serialize(['user'=> false], 'json'));
    //     }
        
    // }

    /**
     * permet de tester la connexion à partir de app_stallman
     *
     * @Route("/login", name="api_login",methods={"POST","GET"})
     * @return void
     */
    public function apiLogin(UserRepository $userRepo,Request $request)
    {
        $datas = json_decode($request->getContent());
        $user = $userRepo->findOneByEmail(["email"=>$datas->email,"password"=>$datas->password]);
        if(!is_null($user)) {
            $userData["nom"]=$user->getNom();
            $userData["prenom"]=$user->getPrenom();
            $userData["id"]=$user->getId();
            $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
            return JsonResponse::fromJsonString($serializer->serialize($userData, 'json'));   
        } else {
            $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
            return JsonResponse::fromJsonString($serializer->serialize(['user'=> false], 'json'));
        }
        
    }


}
