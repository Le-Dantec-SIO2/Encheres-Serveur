<?php

namespace App\Controller;

use App\Entity\Enchere;
use App\Utils\Utils;
use App\Entity\Encherir;
use App\Entity\PlayerFlash;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\EncherirRepository;
use App\Repository\EnchereRepository;
use App\Repository\PlayerFlashRepository;
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
     * Permet d'ajouter un user a la liste des joueurs sur une enchère flash
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
    /**
     * @Route("/api/getAllPlayerFlashByID",name="GetAllPlayerFlashByID")
     */
    public function GetAllPlayerFlashByID(Request $request, EnchereRepository $enchereRepository, UserRepository $userRepository)
    {
        $postdata = json_decode($request->getContent());
        $var = $enchereRepository->findOneBy(['id' => $postdata->IdEnchere])->getPlayerFlashes();
        $users =new ArrayCollection();
        foreach($var as $flashe){
          $user = $flashe->getLeuser();
            $users->add($userRepository->findOneById( $user->first())); 
        }
        $response = new Utils;
        $tab = ['laenchere','lesencherirs','lesencheres','lesmagasins','lesproduits'];
        return $response->GetJsonResponse($request, $users, $tab);
    }

    /**
     * @Route("/api/postEncherirFlash",name="PostEncherirFlash")
     */
    public function PostEncherirFlash(Request $request, EnchereRepository $enchereRepository, EncherirRepository $encherirRepository, UserRepository $userRepository)
    {
    //On récupère les données envoyées en post
        $postdata = json_decode($request->getContent());

        //On recupere le nouveau tableau recalculé côté client
        $TableauFlash = $postdata->TableauFlash;

        //On cherche l'utilisateur
        $user = $userRepository->find($postdata->IdUser);

        //On cherche l'enchère
        $enchere = $enchereRepository->find($postdata->IdEnchere);

        //On cherche le montant de la derniere l'enchère
        $derniereEnchere = $encherirRepository->findActualPrice($postdata->IdEnchere);

        //On calcule la valeur de la nouvelle enchère
        $nouvelleEnchere = $enchere->GetMontantNouvelleEnchere($derniereEnchere,0.38);
       
        //on renvoie
        //On renvoie une réponse pour savoir si l'opération à réussie
        $response = new Response($nouvelleEnchere);
        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }
}