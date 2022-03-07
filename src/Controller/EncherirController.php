<?php

namespace App\Controller;

use App\Utils\Utils;
use App\Entity\Encherir;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Repository\EncherirRepository;
use App\Repository\EnchereRepository;
use function PHPUnit\Framework\isNull;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EncherirController extends AbstractController
{
    /**
     * @Route("/api/getActualPrice",name="GetActualPrice")
     */
    public function GetActualPrice(Request $request, EncherirRepository $encherirRepository, EnchereRepository $enchereRepository)
    {
        $postdata = json_decode($request->getContent());
        $var = isset($postdata->Id) ? $id = $postdata->Id :  Utils::ErrorMissingArguments();
        $enchere = $enchereRepository->findOneBy(['id' => $id]);
        $var = $encherirRepository->findActualPrice($enchere);
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }
    /**
     * @Route("/api/getLastFiveEnchere",name="GetLastFiveEnchere")
     */
    public function GetLastFiveEnchere(Request $request,EncherirRepository $encherirRepository, EnchereRepository $enchereRepository)
    {
        $postdata = json_decode($request->getContent());
        $var = isset($postdata->Id) ? $id = $postdata->Id :  Utils::ErrorMissingArguments();
        $enchere = $enchereRepository->findOneBy(['id' => $id]);
        $var = $encherirRepository->findLastFiveEnchere($enchere);
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }
}