<?php

namespace App\Controller;

use App\Entity\Enchere;
use App\Utils\Utils;
use App\Entity\Encherir;
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

class EncherirController extends AbstractController
{

    /**
     * Permet d'encherir sur une enchère
     * Retourne une réponse http
     * @Route("/api/postEncherirImmediat", name="PostEncherirImmediat")
     */
    public function PostEncherirImmediat(Request $request, UserRepository $userRepository, EnchereRepository $enchereRepository, EntityManagerInterface $em, EncherirRepository $encherirRepository)
    {
        //On récupère les données envoyés en post
        $postdata = json_decode($request->getContent());

        //On cherche l'utilisateur
        $user = $userRepository->find($postdata->IdUser);

        //On cherche l'enchère
        $enchere = $enchereRepository->find($postdata->IdEnchere);
        //On cherche un encherir
        $encherir = $encherirRepository->findOneEncherir($enchere);
        //On récupère le prix de l'offre
        $prixoffre = $postdata->PrixEnchere;

        //On crée un objet encherir avec les valeurs trouvées
        if ($encherir == null) {
            $encherir = new Encherir();
            $encherir->setLeuser($user);
            $encherir->setLaenchere($enchere);
            $encherir->setPrixenchere($prixoffre);
            $encherir->setDateenchere(new \DateTime('now'));

            $em->persist($encherir);
            $em->flush();

            $enchere->setDatefin(new \DateTime('now'));
            $em->persist($enchere);
            $em->flush();
        }


        //On renvoie une réponse pour savoir si l'opération à réussie
        $response = new Response('ok');
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }

    /**
     * Permet d'encherir sur une enchère
     * Retourne une réponse http
     * @Route("/api/postEncherirInverse", name="PostEncherirInverse")
     */
    public function PostEncherirInverse(Request $request, UserRepository $userRepository, EnchereRepository $enchereRepository, EntityManagerInterface $em, EncherirRepository $encherirRepository)
    {
        //On récupère les données envoyés en post
        $postdata = json_decode($request->getContent());

        //On cherche l'utilisateur
        $user = $userRepository->find($postdata->IdUser);

        //On cherche l'enchère
        $enchere = $enchereRepository->find($postdata->IdEnchere);
        //On cherche un encherir
        $encherir = $encherirRepository->findOneEncherir($enchere);
        //On récupère le prix de l'offre
        $prixoffre = $postdata->PrixEnchere;

        //On crée un objet encherir avec les valeurs trouvées
        if ($encherir == null && $enchere->getPrixreserve() < $prixoffre) {
            $encherir = new Encherir();
            $encherir->setLeuser($user);
            $encherir->setLaenchere($enchere);
            $encherir->setPrixenchere($prixoffre);
            $encherir->setDateenchere(new \DateTime('now'));

            $em->persist($encherir);
            $em->flush();

            $enchere->setDatefin(new \DateTime('now'));
            $em->persist($enchere);
            $em->flush();
        }


        //On renvoie une réponse pour savoir si l'opération à réussie
        $response = new Response('ok');
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }
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
        $user = $userRepository->findOneBy(['id' => $postdata->IdUser]);

        //On cherche l'enchère
        $enchere = $enchereRepository->findOneBy(['id' => $postdata->IdEnchere]);

        //On récupère le prix de l'offre
        $prixoffre = $postdata->PrixEnchere;
        if ($enchere->getLetypeenchere()->getId() != 2 && $enchere->getLetypeenchere()->getId() != 4) {

            //On récupère le Coefficient envoyer en paramètre si il y'en a un sinon mettre a 1 par défaut
            isset($postdata->Coefficient) ?  $coefficient = floatval(str_replace(',', '.', ($postdata->Coefficient))) : $coefficient = 1;

            //On vérifie que le prix proposer est cohérent et valide 
            $authorize = EncherirController::PriceAuthorize($postdata->IdEnchere, $prixoffre, $coefficient, $encherirRepository, $enchereRepository);
            if ($authorize != null)
                //On renvoie une réponse pour savoir si l'opération à réussie
                return Utils::ErrorCustom($authorize);
            //On crée un objet encherir avec les valeurs trouvées
        }
        $encherir = new Encherir();
        $encherir->setLeuser($user);
        $encherir->setLaenchere($enchere);
        $enchere->setDatefin($enchere->getLetypeenchere()->getId() == 2 ? new \DateTime('now') : $enchere->getDatefin());
        $encherir->setPrixenchere($prixoffre);
        $encherir->setDateenchere(new \DateTime('now'));

        $em->persist($enchere);
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
        if (!isset($postdata->Id)) {
            $var = Utils::ErrorMissingArguments();
        } else {
            $id = $postdata->Id;
            $enchere = $enchereRepository->findOneBy(['id' => $id]);
            $var = $encherirRepository->findActualPrice($enchere) != null ? $encherirRepository->findActualPrice($enchere) : ["prixenchere" => $enchere->getPrixdepart()];
        }

        $response = new Utils;
        $tab = [];
        return $response->GetJsonResponse($request, $var, $tab);
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
        $tab = [];
        return $response->GetJsonResponse($request, $var, $tab);
    }


    /**
     * @Route("/api/getLastOffer",name="GetLastSixOffer")
     */
    public function GetLastOffer(Request $request, EncherirRepository $encherirRepository, EnchereRepository $enchereRepository)
    {
        $postdata = json_decode($request->getContent());
        $var = isset($postdata->Id) ? $id = $postdata->Id :  Utils::ErrorMissingArguments();
        $enchere = $enchereRepository->findOneBy(['id' => $id]);
        $var = $encherirRepository->findLastOffer($enchere);
        $response = new Utils;
        $tab = [];
        return $response->GetJsonResponse($request, $var, $tab);
    }


    public static function PriceAuthorize($IdEnchere, $prixoffre, $coefficient, EncherirRepository $encherirRepository, EnchereRepository $enchereRepository)
    {
        //On cherche l'enchère
        $enchere = $enchereRepository->findOneBy(['id' => $IdEnchere]);
        //On cherche le prix actuel
        $encherir = $encherirRepository->findActualPrice($enchere);
        //Si il y'a un prix actuel lui affecter son prix
        if ($encherir != null)
            $prixActuel = $encherir["prixenchere"];
        else
            $prixActuel = $enchere->getPrixdepart();
        //Si le type d'enchère est classique
        if ($enchere->getLetypeenchere()->getId() == 1) {
            //Vérifie que l'offre saisie est supérieur au prix actuel * par le coefficient saisie (Classique)
            if (!(($prixActuel * $coefficient) < $prixoffre))
                return "PRICE_TOO_LOW";
        } else {
            //Vérifie que l'offre saisie est inférieur au prix actuel * par le coefficient saisie (Inverser)
            if (!(($prixActuel * $coefficient) > $prixoffre))
                return "PRICE_TOO_HIGH";
            return null;
        }
    }

    /**
     * @Route("/api/postEncherirFlashPass",name="PostEncherirFlashPass")
     */
    public function PostEncherirFlashPass(Request $request, EncherirRepository $encherirRepository, EnchereRepository $enchereRepository, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $postdata = json_decode($request->getContent());
        //On récupère l'enchère avec l'idenchere passer en paramètre
        $enchere = $enchereRepository->findOneBy(['id' => $postdata->IdEnchere]);
        //TODO
    }

    /**
     * @Route("/api/postEncherirFlash",name="PostEncherirFlash")
     */
    public function PostEncherirFlash(Request $request, EncherirRepository $encherirRepository, EnchereRepository $enchereRepository, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $postdata = json_decode($request->getContent());
        //On récupere l'enchère avec l'iduser passer en paramètre
        $user = $userRepository->findOneBy(['id' => $postdata->IdUser]);
        //On récupère la case cliquer
        $case = $postdata->Case;
        //Teste si le format de la case cliquer est correct
        if (preg_match("/\[[0-3]\,[0-3]\]/", $case)) {
            //On récupère l'enchère avec l'idenchere passer en paramètre
            $enchere = $enchereRepository->findOneBy(['id' => $postdata->IdEnchere]);
            //Valeur aléatoire comprise entre -8 et 8
            $coeff = random_int(-8, 8);
            //Récupère le prix actuel (si aucune encherir prendre le prix de départ)
            $actualPrice = $encherirRepository->findActualPrice($enchere) != null ? $encherirRepository->findActualPrice($enchere)["prixenchere"] : $enchere->getPrixdepart();
            //Calcul du nouveau prix avec le pourcentage choisis
            $newPrice = $actualPrice + ($actualPrice * $coeff / 100);

            $tableauFlash = $enchere->getTableauFlash();
            if (strpos($tableauFlash, $case) === false) {
                $enchere->setTableauFlash($tableauFlash . $case);

                $encherir = new Encherir();
                $encherir->setLeuser($user);
                $encherir->setLaenchere($enchere);
                $encherir->setPrixenchere(intval($newPrice));
                $encherir->setDateenchere(new \DateTime('now'));

                $em->persist($encherir);
                $em->persist($enchere);
                $em->flush();
                $var = ['prixenchere' => $newPrice, 'coefficient' => $coeff . "%"];

                $response = new Utils();
                return $response->GetJsonResponse($request, $var);
            } else {
                $response = new Response("Dupplicate case", 409);
                return $response;
            }
        } else {
            $response = new Response("Bad Case", 501);
            return $response;
        }
    }
}
