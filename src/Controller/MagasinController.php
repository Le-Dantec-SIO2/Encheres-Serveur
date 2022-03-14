<?php

namespace App\Controller;

use App\Repository\MagasinRepository;
use App\Utils\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MagasinController extends AbstractController
{
    /**
     * @Route("/api/getMagasins", name="magasinsLister")
     */
    public function GetMagasins(MagasinRepository $magasinRepository, Request $request): Response
    {
        $var = $magasinRepository->findAll();
        $utils = new Utils();

        return $utils->GetJsonResponse($request, $var);
    }
}
