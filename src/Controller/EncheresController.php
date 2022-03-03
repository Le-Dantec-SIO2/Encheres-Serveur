<?php

namespace App\Controller;

use App\Entity\Encherir;
use App\Repository\EnchereRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use function PHPUnit\Framework\isNull;

class EncheresController extends AbstractController
{
    /**
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
        if (isset($postdata->Id)) {
            $id = $postdata->Id;
        } else {
            $id = null;
        }
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);
        $data = $request->getContent();
        $var = $enchereRepository->findEncheres($id);
        $data = $serializer->serialize($var, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
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
        $var = isNull($enchereId) ? $enchereRepository->findEncheresAll() : $enchereRepository->findEncheresEnCours($enchereId);
        $data = $serializer->serialize($var, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
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
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);
        $data = $request->getContent();
        $var = isNull($userId) ? $enchereRepository->findEncheresAll() : $enchereRepository->findEncheresParticipes($userId);
        // $var = [IF] ? [THEN] : [ELSE]
        $data = $serializer->serialize($var, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
