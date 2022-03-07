<?php

namespace App\Controller;

use App\Utils\Utils;
use App\Entity\Encherir;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\EncherirRepository;
use App\Repository\EnchereRepository;
use Exception;
use phpDocumentor\Reflection\Types\Null_;

use function PHPUnit\Framework\isNull;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EncherirController extends AbstractController
{
    /**
     * Permet d'encherir sur une enchère
     * Retourne une réponse http
     * @Route("/api/postEncherir", name="PostEncherir")
     */
    public function PostEncherir(Request $request, UserRepository $userRepository, EnchereRepository $enchereRepository, EntityManagerInterface $em, EncherirRepository $encherirRepository)
    {
        //On récupère les données envoyés en post
        $postdata = json_decode($request->getContent());

        //On cherche l'utilisateur
        $user = $userRepository->find($postdata->IdUser);

        //On cherche l'enchère
        $enchere = $enchereRepository->find($postdata->IdEnchere);

        //On récupère le prix de l'offre
        $prixoffre = $postdata->PrixEnchere;
        //On récupère le Coefficient envoyer en paramètre si il y'en a un sinon mettre a 1 par défaut
        isset($postdata->Coefficient) ? $coefficient = $postdata->Coefficient : $coefficient = 1;

        //On vérifie que le prix proposer est cohérent et valide 
        $authorize = EncherirController::PriceAuthorize($postdata->IdEnchere, $prixoffre, $coefficient, $encherirRepository, $enchereRepository);
        if ($authorize != null)
            //On renvoie une réponse pour savoir si l'opération à réussie
            return Utils::ErrorCustom($authorize);
        //On crée un objet encherir avec les valeurs trouvées
        $encherir = new Encherir();
        $encherir->setLeuser($user);
        $encherir->setLaenchere($enchere);
        $encherir->setPrixenchere($prixoffre);
        $encherir->setDateenchere(new \DateTime('now'));

        $em->persist($encherir);
        $em->flush();

        //On renvoie une réponse pour savoir si l'opération à réussie
        $response = new Response('ok');
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }
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
     * @Route("/api/getLastSixOffer",name="GetLastSixOffer")
     */
    public function GetLastSixOffer(Request $request, EncherirRepository $encherirRepository, EnchereRepository $enchereRepository)
    {
        $postdata = json_decode($request->getContent());
        $var = isset($postdata->Id) ? $id = $postdata->Id :  Utils::ErrorMissingArguments();
        $enchere = $enchereRepository->findOneBy(['id' => $id]);
        $var = $encherirRepository->findLastSixOffer($enchere);
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }

    public static function PriceAuthorize($IdEnchere, $prixoffre, $coefficient, EncherirRepository $encherirRepository, EnchereRepository $enchereRepository)
    {
        //On cherche l'enchère
        $enchere = $enchereRepository->find($IdEnchere);
        $prixActuel = $encherirRepository->findActualPrice($enchere);
        dd($prixActuel->find(0));
        //Si le type d'enchère est classique
        if ($enchere->getLetypeenchere()->getId() == 1) {
            //Vérifie que l'offre saisie est supérieur au prix actuel * par le coefficient saisie (Classique)
            if ($prixActuel * $coefficient < $prixoffre)
                return "PRICE_TOO_LOW"+$prixActuel;
        }
        //Vérifie que l'offre saisie est inférieur au prix actuel * par le coefficient saisie (Inverser)
        if ($prixActuel * $coefficient > $prixoffre)
            return "PRICE_TOO_HIGH"+$prixActuel;
        return null;
    }
}