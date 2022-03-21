<?php

namespace App\Controller;

use App\Entity\User;
use App\Utils\Utils;
use Monolog\Handler\Curl\Util;
use App\Repository\UserRepository;
use App\Repository\EnchereRepository;
use App\Repository\EncherirRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        else
            return Utils::ErrorMissingArguments();

        $enchere = $enchereRepository->findOneBy(['id' => $id]);
        $var = $encherirRepository->findGagnantEnchere($enchere);
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }

    /**
     * @Route("/api/getUser", name="getUser")
     */
    public function getUser(Request $request, UserRepository $userRepository)
    {
        $postdata = json_decode($request->getContent());
        if (isset($postdata->Id))
            $id = $postdata->Id;
        else
            return Utils::ErrorMissingArguments();

        $user = $userRepository->findOneBy(['id' => $id]);
        $var = $userRepository->findGagnantEnchere($user);
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }

    /**
     * @Route("/api/getUserByMailAndPass",name="GetUserByMailAndPass")
     */
    public function GetUserByMailAndPass(Request $request, UserRepository $userRepository)
    {
        $postdata = json_decode($request->getContent());
        if (isset($postdata->Email) && isset($postdata->Password)) {
            $email = $postdata->Email;
            $password = $postdata->Password;
        } else
            return  Utils::ErrorMissingArgumentsDebug($request->getContent());
        $var = $userRepository->findUserByEmailAndPass(['email' => $email], ['password' => $password]);
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
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


        $user->setphoto(($postdata->Photo));


        try {
            $manager->persist($user);
            $manager->flush();
            $response = new Response($user->getId());
        } catch (UniqueConstraintViolationException $e) {
            $response = new Response('Email déjà existant', 409);
        }
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }
}
