<?php

namespace App\Services\Order;

use App\Entity\Order;
use App\Entity\Product;
use App\Services\Cart\CartServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderServices
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack, CartServices $cartServices)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
        $this->cartServices = $cartServices;
    }

    public function Order($data, $user)
    {

        $order = new Order();
        $carrier = $this->requestStack->getSession();
        return $carrier->get('carrier', []);
        $address =  $this->requestStack->getSession('checkout', []);

        $order->setAddresses($address)
            ->setUserOrder($user)
            ->setCarrier($carrier)
            ->setFullPrice($data['data']['subTotalTtc'] * 100)
            ->setOrderPrice($data['data']['subTotal'] * 100);

        $this->entityManager->persist($order);

        $cart_details = [];

        foreach ($data['orders'] as $product) {
            $cartDetail = new Product();
            $cartDetail->setProductName($product['product']->getProductName());

            $this->entityManager->persist($cartDetail);
            $cart_details[] = $cartDetail;
        }
        $this->entityManager->flush();
    }

    public function createOrder()
    {
        $carrier = $this->requestStack->getSession();
        return $carrier->get('carrier', []);
        $address =  $this->requestStack->getSession('checkout', []);
        return $carrier->get('addresses', []);


        $fullcart = $this->cartServices->getFullCart();
        $order = new Order();
        $order->setAddresses($address)
            ->setCarrier($carrier)
            ->setOrderPrice($fullcart['data']['subTotal'])
            ->setFullPrice($fullcart['data']['subTotalTtc']);
        $this->entityManager->persist($order);


        $this->entityManager->flush();
        return $order;
    }
}
