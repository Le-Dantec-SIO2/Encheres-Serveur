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



}
