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
     * @Route("/api/getGagnant", name="getGagnant")
     */
    public function getGagnant(Request $request, EnchereRepository $enchereRepository, EncherirRepository $encherirRepository)
    {
        $postdata = json_decode($request->getContent());
        if (isset($postdata->Id))
            $id = $postdata->Id;
        else {
            $response = new Response('MISSING_ARGUMENTS_PARAMETERS');
            $response->headers->set('Content-Type', 'text/html');
            return $response;
        }
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);
        $data = $request->getContent();
        $enchere = $enchereRepository->findOneBy(['id' => $postdata->Id]);
        $var = $encherirRepository->findGagnantEnchere($enchere);
        $data = $serializer->serialize($var, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/api/getUserByMailAndPass",name="GetUserByMailAndPass")
     */
    public function GetUserByMailAndPass(Request $request, UserRepository $userRepository)
    {
        $postdata = json_decode($request->getContent());
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);
        $data = $request->getContent();
        $var = $userRepository->findUserByEmailAndPass(['email' => $postdata->email],['password' => $postdata ->password]);
        $data = $serializer->serialize($var, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/api/connect/",name="connect")
     */
    public function Bidule(Request $request, UserRepository $userRepository)
    {
        $postdata = json_decode($request->getContent());
        dd($postdata);
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);
        $data = $request->getContent();
        $response = new Response();
        // $var = $userRepository->findUserByEmailAndPass($mail);
        // if(1){
        // $data = $serializer->serialize($var, 'json');
        // $response = new Response($data);
        // $response->headers->set('Content-Type', 'application/json');
        // }else{
        //     $data = "Mauvais identifiants";
        //     $response = new Response($data, 401);
        //     $response->headers->set('Content-Type', 'application/json');
        // }
        return $response;
    }

    /**
     * @Route("/api/postUser", name="postUser")
     */
    public function PostUser(Request $request, EntityManagerInterface $manager)
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