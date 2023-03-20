<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/compte')]
class OrderController extends AbstractController
{

    #[Route('/order', name: 'app_order_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {

        $user = $this->getUser();
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('User/order/index.html.twig', [
            'orders' => $orderRepository->findBy(array('userOrder' => $user)),
        ]);
    }


    #[Route('/{id}', name: 'app_order_show', methods: ['GET'])]
    public function show(Order $order, ProductRepository $product): Response
    {
        $discountProduct = $product->productDiscount();

        foreach ($discountProduct as $key => $product) {
            $productPrice = $product->getProductPrice();
            $discount = $product->getDiscount();
            $discountProduct[$key]->discountPrice = ($productPrice / 100) - ($discount / 100) * ($productPrice / 100);
        }


        return $this->render('User/order/show.html.twig', [
            'order' => $order,
            'discountProduct' => $discountProduct,

        ]);
    }


    // #[Route('/{id}', name: 'app_order_delete', methods: ['POST'])]
    // public function delete(Request $request, Order $order, OrderRepository $orderRepository): Response
    // {
    //     if ($this->isCsrfTokenValid('delete' . $order->getId(), $request->request->get('_token'))) {
    //         $orderRepository->remove($order, true);
    //     }

    //     return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
    // }

    #[Route("/admin/order/{id}", name: "admin_order_detail")]
    public function orderDetail(Order $order)
    {
        return $this->render('admin/order_detail.html.twig', [
            'order' => $order
        ]);
    }
}
