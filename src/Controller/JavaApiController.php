<?php

namespace App\Controller;

use App\Entity\BillLign;
use DateTime;
use App\Entity\User;
use App\Entity\Message;
use App\Entity\OutPackage;
use App\Repository\BillLignRepository;
use App\Repository\BillRepository;
use App\Repository\BillStateRepository;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use App\Security\LoginFormAuthenticator;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
     * @Route("/api", name="java_api")
     */
class JavaApiController extends AbstractController
{
    public $serializer;

    public function __construct()
    {
        $this->serializer = \JMS\Serializer\SerializerBuilder::create()->build();
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
    public function apiLogin(UserRepository $userRepo,Request $request,UserPasswordEncoderInterface $encoder)
    {
        $datas = json_decode($request->getContent());
        
        $user = $userRepo->findOneByEmail($datas->email);
        $isPasswordValid = $encoder->isPasswordValid($user, $datas->password);

        if (!$isPasswordValid) {
            return JsonResponse::fromJsonString($this->serializer->serialize(['user'=> false], 'json'));
        } else {
           
            $userData["nom"]=$user->getNom();
            $userData["prenom"]=$user->getPrenom();
            $userData["id"]=$user->getId();
    
            return JsonResponse::fromJsonString($this->serializer->serialize($userData, 'json'));  
    
        }  
        
    }
        
    /**
     * retourne la liste des messages du user concerné
     *
     * @Route("/message/list/{user_id}", name="api_get_list_message",methods={"POST","GET"})
     * @return JSON
     */
    public function apiGetListMessage(MessageRepository $messageRepo,$user_id)
    {
        //je récupère tous les messages du user
        //$data = $messageRepo->findByUserTo($user_id);
        //je ne récupere que les messages qui ne sont pas archivé
        $data = $messageRepo->getMessagesUnarchived($user_id);
        return JsonResponse::fromJsonString($this->serializer->serialize($data, 'json',SerializationContext::create()->enableMaxDepthChecks()));

    }

    /**
     * retourne la liste des factures du user concerné
     *
     * @Route("/bill/list/{user_id}", name="api_get_list_bill",methods={"POST","GET"})
     * @return JSON
     */
    public function apiGetListBill(BillRepository $billRepo,$user_id)
    {
        $data = $billRepo->findByUser($user_id);
        return JsonResponse::fromJsonString($this->serializer->serialize($data, 'json',SerializationContext::create()->enableMaxDepthChecks()));

    }
    

    /**
     * retourne la liste des lignes de la facture du user concerné
     *
     * @Route("/billlign/list/{bill_id}", name="api_get_bill_lign",methods={"POST","GET"})
     * @return JSON
     */
    public function apiGetBillLigns(BillLignRepository $BillLignRepo,$bill_id)
    {
        $data = $BillLignRepo->findByBill($bill_id);
        return JsonResponse::fromJsonString($this->serializer->serialize($data, 'json',SerializationContext::create()->enableMaxDepthChecks()));

    }

    /**
     * retourne le message de l'index
     *
     * @Route("/message/{message_id}", name="api_get_message",methods={"POST","GET"})
     * @return JSON
     */
    public function apiGetMessage(MessageRepository $messageRepo,$message_id)
    {
        $data = $messageRepo->find($message_id);
        return JsonResponse::fromJsonString($this->serializer->serialize($data, 'json',SerializationContext::create()->enableMaxDepthChecks()));

    }

    /**
     * créer un message
     *
     * @Route("/message/new", name="api_new_message",methods={"PUT","POST"})
     * @return JSON
     */
    public function apiNewMessage(Request $request,UserRepository $userRepo)
    {
        $datas = json_decode($request->getContent());

        $user_from = $userRepo->find($datas->user_from);
        $user_to = $userRepo->find($datas->user_to);

        $date = new DateTime('NOW');

        $message = new Message();

        $message->setTitle($datas->title);
        $message->setContent($datas->content);
        $message->setUserFrom($user_from);
        $message->setUserTo($user_to);
        $message->setDateSend($date);
        $message->setIsImportant($datas->is_important);
        $message->setIsRead(0);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($message);
        $entityManager->flush();

        return JsonResponse::fromJsonString($this->serializer->serialize($message, 'json',SerializationContext::create()->enableMaxDepthChecks()));

    }

    /**
     * mettre un message en archivé_user_to
     *
     * @Route("/message/archived/{id}", name="api_mesage_archived",methods={"PATCH","POST"})
     * @return JSON
     */
    public function apiArchivedMessage(Message $message)
    {
        $message->setIsArchivedUserTo(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($message);
        $entityManager->flush();

        return JsonResponse::fromJsonString($this->serializer->serialize($message, 'json',SerializationContext::create()->enableMaxDepthChecks()));

    }

        /**
     * créer un message
     *
     * @Route("/outpackage/new/{bill_id}", name="api_new_outpackage",methods={"PUT","POST"})
     * @return JSON
     */
    public function apiNewOutPackage(Request $request,BillRepository $billRepo,$bill_id)
    {
        $datas = json_decode($request->getContent());
        $entityManager = $this->getDoctrine()->getManager();
        $date = new DateTime('NOW');

        $outPackage = new OutPackage();
        $billLign = new BillLign();
        $bill = $billRepo->findOneById($bill_id);

        if ($bill->getBillState()->getStateName() == BillStateRepository::STR_STATE_CREATE) {
            
            $outPackage->setOutPackageName($datas->out_package_name);
            $outPackage->setValue($datas->out_package_value);
            $entityManager->persist($outPackage);
            
            $billLign->setCreatedAt($date);
            $billLign->setOutPackage($outPackage);
            $billLign->setBill($bill);
            $billLign->setGlobalLignValue($datas->out_package_value);
            $entityManager->persist($billLign);
    
            $bill->setGlobalBillValue($bill->getGlobalBillValue()+$datas->out_package_value);
            $entityManager->persist($bill);
            
            $entityManager->flush();

            return JsonResponse::fromJsonString($this->serializer->serialize($billLign, 'json',SerializationContext::create()->enableMaxDepthChecks()));

        } else {

            return JsonResponse::fromJsonString($this->serializer->serialize(['billLign'=> false], 'json'));
        }
        

    }

    


}
