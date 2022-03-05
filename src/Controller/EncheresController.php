<?php

namespace App\Controller;

use App\Utils\Utils;
use App\Entity\Encherir;
use Doctrine\ORM\EntityManager;
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
     * Permet d'encherir sur une enchère
     * Retourne une réponse http
     * @Route("/api/EncherirPost", name="EncherirPost")
     */
    public function EncherirPost(Request $request, UserRepository $userRepository, EntityManager $em)
    {
        //On récupère les données envoyés en post
        $postdata = json_decode($request->getContent());

        //On cherche l'utilisateur
        $user = $userRepository->find($postdata->IdUser);

        //On cherche l'enchère
        $enchere = $userRepository->find($postdata->IdEnchere);

        //On crée un objet encherir avec les valeurs trouvées
        $encherir = new Encherir();
        $encherir->setLeuser($user);
        $encherir->setLaenchere($enchere);
        $encherir->setPrixenchere($postdata->PrixEnchere);
        $encherir->setDateenchere(new \DateTime('now'));

        $em->persist($encherir);
        $em->flush();

        //On renvoie une réponse pour savoir si l'opération à réussie
        $response = new Response('ok');
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }

    /**
     * @Route("/api/getEnchere",name="Getenchere")
     */
    public function Getencheres(Request $request, EnchereRepository $enchereRepository)
    {
        $postdata = json_decode($request->getContent());
        $id = isset($postdata->Id) ? $postdata->Id : false;
        $var = $enchereRepository->findEncheres($id);
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }


    /**
     * @Route("/api/getEncheresEnCours", name="GetEncheresEnCoursuwu")
     */
    public function GetEncheresEnCours(Request $request, EnchereRepository $enchereRepository)
    {
        $postdata = json_decode($request->getContent());
        if (isset($postdata->Id)) {
            $enchereId = $postdata->Id;
        } else {
            $enchereId = null;
        }
        //On Récuprère toutes les enchères en cours ou on envoie true ou false si on regarde pour une enchère si elle est en cours
        $var = isNull($enchereId) ? $enchereRepository->findEncheresAll() : $enchereRepository->findEncheresEnCours($enchereId);
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }

    /**
     * @Route("/api/getEncheresParticipes", name="GetEncheresParticipes")
     */
    public function GetEncheresParticipes(Request $request, EnchereRepository $enchereRepository, UserRepository $userRepository)
    {
        $postdata = json_decode($request->getContent());
        if (isset($postdata->Id)) {
            $userId = $postdata->Id;
        } else {
            $userId = null;
        }
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
}