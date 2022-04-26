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
        if(isset($postdata->IdTypeEnchere))
            $var = isset($postdata->Id) ? $enchereRepository->findEnchere($postdata->Id) : $enchereRepository->findEncheresByType($postdata->IdTypeEnchere);
        else
            $var = isset($postdata->Id) ? $enchereRepository->findEnchere($postdata->Id) : $enchereRepository->findEncheres();
        $response = new Utils;
        $tab = ['leuser','laenchere','lesencherirs','lesencheres','lesmagasins','lesproduits'];
        return $response->GetJsonResponse($request, $var, $tab);
    }


    /**
     * @Route("/api/getEncheresEnCours", name="GetEncheresEnCours")
     */
    public function GetEncheresEnCours(Request $request, EnchereRepository $enchereRepository)
    {
        $postdata = json_decode($request->getContent());
        if(isset($postdata->IdTypeEnchere))
            $var = isset($postdata -> Id) ? $enchereRepository->findEnchereEnCours($postdata->Id) : $enchereRepository->findEncheresEnCoursByType($postdata->IdTypeEnchere);
        else
            $var = isset($postdata -> Id) ? $enchereRepository->findEnchereEnCours($postdata->Id) : $enchereRepository->findEncheresEnCours();
        
        $counts = array_count_values($enchereRepository->findEncheresInverseesFinies());
        $result = array_filter($var, function($o) use (&$counts) {
            return empty($counts[$o]) || !$counts[$o]--;
        });
        sort($result, SORT_NUMERIC);
        $var = $result;
        //On Récuprère toutes les enchères en cours ou on envoie true ou false si on regarde pour une enchère si elle est en cours
        $response = new Utils;
        $tab = ['leuser','laenchere','lesencherirs','lesencheres','lesmagasins','lesproduits'];
        return $response->GetJsonResponse($request, $var, $tab);
    }

    /**
     * @Route("/api/getEncheresFutures", name="GetEncheresFutures")
     */
    public function GetEncheresFutures(Request $request, EnchereRepository $enchereRepository)
    {
        //On Récuprère toutes les enchères en cours ou on envoie true ou false si on regarde pour une enchère si elle est en cours
        $var = $enchereRepository->findEncheresFutures();
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
        $var = $enchereRepository->findEnchereTestObjet();
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }

    /**
     * @Route("/api/getProchaineEnchere", name="GetProchaineEnchere")
     */
    public function GetProchaineEnchere(Request $request, EnchereRepository $enchereRepository){
        $var = $enchereRepository->findProchaineEnchere();
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }
}