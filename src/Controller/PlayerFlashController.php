<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayerFlashController extends AbstractController
{
    /**
     * @Route("/player/flash", name="player_flash")
     */
    public function index(): Response
    {
        return $this->render('player_flash/index.html.twig', [
            'controller_name' => 'PlayerFlashController',
        ]);
    }
}
