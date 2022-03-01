<?php

namespace App\Controller;

use App\Repository\EnchereRepository;
use App\Repository\EncherirRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;

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
}
