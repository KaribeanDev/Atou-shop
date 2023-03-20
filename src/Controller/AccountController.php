<?php

namespace App\Controller;

use App\Repository\AddressRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/compte')]
class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(AddressRepository $address, OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }

        $orderCount = $orderRepository->countOrdersByUser($user);
        


        return $this->render('User/account/index.html.twig', [
            'address' => $address,
            'user' => $user, 
            'orderCount' => $orderCount
        ]);
    }

    #[Route('/', name: 'app_history', methods: ['GET'])]
    public function historyByUser(OrderRepository $orderRepository)
    {
        $user = $this->getUser();


        $orders = $orderRepository->findBy(array ('userOrder' => $user));

        return $this->render('User/account/history.html.twig', [
            'orders' => $orders,
        ]);
    }
}
