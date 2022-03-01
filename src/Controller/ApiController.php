<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Enchere;
use App\Entity\Magasin;
use App\Entity\Produit;
use App\Entity\Encherir;
use App\Entity\TypeEnchere;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Repository\EnchereRepository;
use App\Repository\ProduitRepository;
use App\Repository\EncherirRepository;
use Symfony\Component\Serializer\Serializer;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

  /**
     * @Route("/api/EncherirPost", name="EncherirPost")
     */
    public function EncherirPost(Request $request,UserRepository $userRepository, EntityManager $em)
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
        $response = new Response("ok");
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }

    /**
     * @Route("/api/getEncheres/{enchereId}", defaults={"enchereId"= null},name="Getencheres")
     */
    public function Getencheres($enchereId, Request $request, EnchereRepository $enchereRepository)
    {
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);
        $data = $request->getContent();
        $var = $enchereRepository->findEncheres($enchereId);
        $data =  $serializer->serialize($var, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    /** 
     * @Route("/api/getEncheresEnCours/{enchereId}",defaults={"enchereId"=0}, name="GetEncheresEnCoursuwu")
    */
    public function GetEncheresEnCours($enchereId, Request $request,EnchereRepository $enchereRepository){
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);
        $data = $request->getContent();
        //On Récuprère toutes les enchères en cours ou on envoie true ou false si on regarde pour une enchère si elle est en cours
        $var = $enchereId==0 ?$enchereRepository->findEncheresEnCours() : $enchereRepository->findEncheresEnCours($enchereId);
        $var = !empty($var);
        $data =  $serializer->serialize($var, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /** 
     * @Route("/api/getEncheresParticipes/{userId}", name="GetEncheresParticipes")
    */
    public function GetEncheresParticipes( $userId,Request $request,EnchereRepository $enchereRepository,UserRepository $userRepository ){
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);
        $data = $request->getContent();
        $leuser = $userRepository->findOneBy(['id' => $userId]);
        $var =$enchereRepository->findEncheresParticipes($leuser);
        $data =  $serializer->serialize($var, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

     /** 
     * @Route("/api/getProduits", name="getProduits")
    */
    public function GetProduits(Request $request, ProduitRepository $produitRepository){
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);
        $data = $request->getContent();
        $var =$produitRepository->findProduits();
        $data =  $serializer->serialize($var, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
         /** 
     * @Route("/api/getGagnant/{enchereId}", name="getGagnant")
    */
    public function GetGagnantEnchere(Request $request,UserRepository $userRepository,EnchereRepository $enchereRepository,EncherirRepository $encherirRepository,$enchereId){
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);
        $data = $request->getContent();
        $enchere = $enchereRepository->findOneBy(['id'=> $enchereId]);
        $var = $encherirRepository->findGagnantEnchere($enchere);
        $data =  $serializer->serialize($var, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
    /**
     * @Route("/api/getUser/{userId}",name="GetUser")
     */
    public function GetUserById($userId, Request $request, UserRepository $userRepository)
    {
        $encoder = new JsonEncoder();
$defaultContext = [
    AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
        return $object->getId();
    },
];
$normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

$serializer = new Serializer([$normalizer], [$encoder]);
        $data = $request->getContent();
        $var = $userRepository->findUserById(['id' => $userId]);
        $data =  $serializer->serialize($var, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}
