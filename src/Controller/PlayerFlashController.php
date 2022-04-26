<?php

namespace App\Controller;

use App\Utils\Utils;
use App\Entity\Encherir;
use App\Entity\PlayerFlash;
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
        $playerFlash->SetTag(False);
        $em->persist($playerFlash);
        $em->flush();

        //On renvoie une réponse pour savoir si l'opération à réussie
        $response = new Response($playerFlash->getId());
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
     * @Route("/api/getPlayerFlashByID",name="GetPlayerFlashByID")
     */
    public function GetPlayerFlashByID(Request $request,PlayerFlashRepository $playerFlashRepository)
    {
        $postdata = json_decode($request->getContent());
        $var = $playerFlashRepository->findJoueurinscrit($postdata->IdEnchere,$postdata->IdUser);
        
        $response = new Utils;
        $tab = ['laenchere','leuser'];
        return $response->GetJsonResponse($request, $var, $tab);
    }


    /**
     * @Route("/api/postEncherirFlashManuel",name="PostEncherirFlashManuel")
     */
    public function PostEncherirFlashManuel(Request $request, EnchereRepository $enchereRepository, EncherirRepository $encherirRepository, UserRepository $userRepository, EntityManagerInterface $em)
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
        
        // On verifiequ'une premiere enchere a deja ete passée
        if(isset($derniereEnchere))
        //On calcule la valeur de la nouvelle enchère
        $nouvelleEnchere = $enchere->GetMontantNouvelleEnchere($derniereEnchere['prixenchere'],38);
        else
        //On calcule la valeur de la nouvelle enchère avec le prix de depart
        $nouvelleEnchere = $enchere->GetMontantNouvelleEnchere($enchere->getPrixdepart(),38);
       
        //On INSERT la nouvelle enchere
        $encherir = new Encherir();
        $encherir->setLeuser($user);
        $encherir->setLaenchere($enchere);
        $encherir->setPrixenchere($nouvelleEnchere);
        $encherir->setDateenchere(new \DateTime('now'));

        $em->persist($encherir);
        $em->flush();

        //On UPDATE le TABLEAU FLASH
        $enchere->setTableauFlash($TableauFlash);
        $em->persist($enchere);
        $em->flush();

        //On renvoie une réponse pour savoir si l'opération à réussie
        $response = new Response("ok");
        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }

    /**
     * @Route("/api/postEncherirFlashJePasse",name="PostEncherirFlashJePasse")
     */
    public function PostEncherirFlashJePasse(Request $request, EnchereRepository $enchereRepository, EncherirRepository $encherirRepository, UserRepository $userRepository, EntityManagerInterface $em)
    {
    //On récupère les données envoyées en post
        $postdata = json_decode($request->getContent());

        //On cherche l'utilisateur
        $user = $userRepository->find($postdata->IdUser);

        //On cherche l'enchère
        $enchere = $enchereRepository->find($postdata->IdEnchere);

        //On cherche le montant de la derniere l'enchère
        $derniereEnchere = $encherirRepository->findActualPrice($postdata->IdEnchere);
        
        // On verifiequ'une premiere enchere a deja ete passée
        if(isset($derniereEnchere))
        //On calcule la valeur de la nouvelle enchère
        $nouvelleEnchere = $derniereEnchere;
        else
        //On calcule la valeur de la nouvelle enchère avec le prix de depart
        $nouvelleEnchere = $derniereEnchere;
       
        //On INSERT la nouvelle enchere
        $encherir = new Encherir();
        $encherir->setLeuser($user);
        $encherir->setLaenchere($enchere);
        $encherir->setPrixenchere($nouvelleEnchere);
        $encherir->setDateenchere(new \DateTime('now'));

        $em->persist($encherir);
        $em->flush();


        //On marque le prochain joueurs


        //On renvoie une réponse pour savoir si l'opération à réussie
        $response = new Response("ok");
        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }
        /**
     * @Route("/api/getJoueurActif",name="GetJoueurActif")
     */
    public function GetJoueurActif(Request $request, PlayerFlashRepository $playerFlashRepository,EntityManagerInterface $em)
    {   
        //On récupère les données envoyées en post
        $postdata = json_decode($request->getContent());

        //On cherche l'utilisateur
        $playerAncien = $playerFlashRepository->find($postdata->Id);
    

        //On Recherche le joueur actif suivant
        $playernouveau = $playerFlashRepository->findJoueur($postdata->IdEnchere,$postdata->Id);
        if(!isset($playernouveau))
         $playernouveau = $playerFlashRepository->findJoueurOne($postdata->IdEnchere);


        //On decoche le tag de l'ancien joueur
        $playerAncien->setTag(False);
        $em->persist($playerAncien);
        $em->flush();

         //On coche le tag du nouveau joueur
        $playernouveau->setTag(True);
        $em->persist($playernouveau);
        $em->flush();

        $response = new Utils;
         $tab = ['laenchere','leuser'];
        return $response->GetJsonResponse($request, $playernouveau,$tab);
    }
}