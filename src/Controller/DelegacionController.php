<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DelegacionController extends AbstractController
{
    #[Route('/delegacion', name: 'app_delegacion')]
    public function index(): Response
    {
        return $this->render('delegacion/index.html.twig', [
            'controller_name' => 'DelegacionController',
        ]);
    }
}
