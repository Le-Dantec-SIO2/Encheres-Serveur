<?php

namespace App\Controller;

use App\Utils\Utils;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProduitController extends AbstractController
{

    /** 
     * @Route("/api/getProduit", name="getProduit")
     */
    public function GetProduit(Request $request, ProduitRepository $produitRepository)
    {
        $postdata = json_decode($request->getContent());
        
        if (isset($postdata->Id))
            $id = $postdata->Id;
        else 
            $id = null;

        $var = $produitRepository->findProduits($id);
        $response = new Utils;
        $ignoredFields = ['lesmagasins','lesencheres'];
        return $response->GetJsonResponse($request, $var, $ignoredFields);
    }
    /**
     * @Route("/api/postProduit", name="postProduit")
     */
    public function PostProduit(Request $request, EntityManagerInterface $manager)
    {
        $postdata = json_decode($request->getContent());
        $produit = new Produit();
        $produit->setNom($postdata->Nom);
        $produit->setPhoto($postdata->Photo);
        $produit->setPrixreel($postdata->Prixreel);



        $manager->persist($produit);
        $manager->flush();

        $response = new Response($produit->getId());
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }
}