<?php

namespace App\Controller;

use App\Entity\Enchere;
use App\Utils\Utils;
use App\Entity\Encherir;
use App\Entity\PlayerFlash;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\EncherirRepository;
use App\Repository\EnchereRepository;
use Exception;
use Monolog\Handler\Curl\Util;
use phpDocumentor\Reflection\Types\Null_;

use function PHPUnit\Framework\isNull;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlayerFlashController extends AbstractController
{
 /**
     * Permet d'encherir sur une enchère
     * Retourne une réponse http
     * @Route("/api/postPlayerFlash", name="PostPlayerFlash")
     */
    public function PostPlayerFlash(Request $request, UserRepository $userRepository, EnchereRepository $enchereRepository, EntityManagerInterface $em, EncherirRepository $encherirRepository)
    {
        //On récupère les données envoyés en post
        $postdata = json_decode($request->getContent());

        //On cherche l'utilisateur
        $user = $userRepository->find($postdata->IdUser);

        //On cherche l'enchère
        $enchere = $enchereRepository->find($postdata->IdEnchere);
        
        //On crée un objet playerFlash avec les valeurs trouvées
        $playerFlash = new PlayerFlash();
        $playerFlash->addLeuser($user);
        $playerFlash->setLaenchere($enchere);

        $em->persist($playerFlash);
        $em->flush();

        //On renvoie une réponse pour savoir si l'opération à réussie
        $response = new Response('ok');
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }
}