<?php

namespace App\Controller;

use App\Utils\Utils;
use App\Entity\Encherir;
use App\Repository\UserRepository;
use App\Repository\EnchereRepository;

use function PHPUnit\Framework\isNull;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class EncheresController extends AbstractController
{


    /**
     * @Route("/api/getEnchere",name="Getenchere")
     */
    public function Getencheres(Request $request, EnchereRepository $enchereRepository)
    {
        $postdata = json_decode($request->getContent());
        $var = isset($postdata->Id) ? $enchereRepository->findEnchere($postdata->Id) : $enchereRepository->findEncheres();
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }


    /**
     * @Route("/api/getEncheresEnCours", name="GetEncheresEnCoursuwu")
     */
    public function GetEncheresEnCours(Request $request, EnchereRepository $enchereRepository)
    {
        $postdata = json_decode($request->getContent());
        //On Récuprère toutes les enchères en cours ou on envoie true ou false si on regarde pour une enchère si elle est en cours
        $var = isset($postdata -> Id) ? $enchereRepository->findEnchereEnCours($postdata->id) : $enchereRepository->findEncheresEnCours();
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }

    /**
     * @Route("/api/getEncheresParticipes", name="GetEncheresParticipes")
     */
    public function GetEncheresParticipes(Request $request, EnchereRepository $enchereRepository, UserRepository $userRepository)
    {
        $postdata = json_decode($request->getContent());
        $userId = isset($postdata->Id) ? $postdata->Id : null;
        $var = IsNull($userId) ? $enchereRepository->findEncheresAll() : $enchereRepository->findEncheresParticipes($userId);
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }

    /**
     * @Route("/api/getEnchereTest",name="Getencheretest")
     */
    public function GetenchereTest(Request $request, EnchereRepository $enchereRepository)
    {
        $postdata = json_decode($request->getContent());
        $var = $enchereRepository->findEnchere($postdata->Id);
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }

    /**
     * @Route("/api/getEnchereTestObjet",name="GetencheretestObjet")
     */
    public function GetenchereTestObjet(Request $request, EnchereRepository $enchereRepository)
    {
        $postdata = json_decode($request->getContent());
        $var = $enchereRepository->findEnchereTestObjet($postdata->Id);
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }
}