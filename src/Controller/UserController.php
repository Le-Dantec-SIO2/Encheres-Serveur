<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\EnchereRepository;
use App\Repository\EncherirRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/api/getGagnant/{enchereId}", name="getGagnant")
     */
    public function GetGagnantEnchere(Request $request, UserRepository $userRepository, EnchereRepository $enchereRepository, EncherirRepository $encherirRepository, $enchereId)
    {
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);
        $data = $request->getContent();
        $enchere = $enchereRepository->findOneBy(['id' => $enchereId]);
        $var = $encherirRepository->findGagnantEnchere($enchere);
        $data = $serializer->serialize($var, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/api/getUser/{userId}",name="GetUser")
     */
    public function GetUserById($userId, Request $request, UserRepository $userRepository)
    {
        $encoder = new JsonEncoder();
        $defaultContext = [
    AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
        return $object->getId();
    },
];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);
        $data = $request->getContent();
        $var = $userRepository->findUserById(['id' => $userId]);
        $data = $serializer->serialize($var, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    /**
     * @Route("/api/connect/",name="connect")
     */
    public function Connect(Request $request, UserRepository $userRepository)
    {
        $postdata = json_decode($request->getContent());
        if (isset($postdata->Email) && isset($postdata->Password)) {
            $mail = $postdata->Email;
            $pass = $postdata->Password;
        } else {
            $mail = null;
            $pass = null;
        }
        dd(isset($postdata->Email), isset($postdata->Password));
        $encoder = new JsonEncoder();
        $defaultContext = [
    AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
        return $object->getId();
    },
];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);
        $data = $request->getContent();
        $var = $userRepository->findUserByEmail($mail);
        if(1){
        $data = $serializer->serialize($var, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        }else{
            $data = "Mauvais identifiants";
            $response = new Response($data, 401);
            $response->headers->set('Content-Type', 'application/json');
        }
        return $response;

    }

        /**
     * @Route("/api/postUser", name="postUser")
     */
    public function PostUser(Request $request,EntityManagerInterface $manager)
    {
        $postdata = json_decode($request->getContent());
        $user = new User();
        $user->setEmail($postdata->Email);
        $user->setPassword($postdata->Password);

        $user->setPseudo($postdata->Pseudo);
        $user->setphoto($postdata->Photo);
        

       
        $manager->persist($user);
        $manager->flush();

        $response = new Response($user->getId());
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }
    
}
