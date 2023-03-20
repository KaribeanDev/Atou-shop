<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PolicyController extends AbstractController
{
    #[Route('/policy', name: 'app_policy')]
    public function index(): Response
    {
        return $this->render('policy/politiqueConfidentialité.htlm.twig', [
            'controller_name' => 'PolicyController',
        ]);
    }
    #[Route('/cgv', name:'app_cgv')]
    public function cgv(): Response
    {
        return $this->render('policy/cgv.html.twig');
    }
}
