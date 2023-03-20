<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\CheckoutType;
use App\Entity\OrderDetail;
use App\Repository\OrderRepository;
use App\Services\Cart\CartServices;
use App\Repository\AddressRepository;
use App\Repository\CarrierRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/Shop/checkout')]

class CheckoutController extends AbstractController
{
    private $cartServices;

    public function __construct(CartServices $cartServices)
    {
        $this->cartServices = $cartServices;
    }


    #[Route('/checkout', name: 'app_checkout')]
    public function index(AddressRepository $addressRepository, CarrierRepository $carrierRepository, Request $request): Response
    {

        $user = $this->getUser();
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }





        $addresses = $addressRepository->findBy(array('user' => $user));
        $cart = $this->cartServices->getFullCart();


        if (empty($cart['product'])) {
            return $this->redirectToRoute('app_home');
        }


        if (!$addresses) {
            $this->addFlash('checkout_message', 'Merci de selectionner une adresses pour valider !');
            return $this->redirectToRoute('app_address_new');
        }

        $carriers = $carrierRepository->findAll();
        foreach ($carriers as $carrier) {
            $carrier->setCarrierPrice(floatval($carrier->getCarrierPrice()));
        }
        $carrierPrice = 0;
        $selectedCarrier = $carrierRepository->findOneBy(['carrierName' => $request->get('carrier')]);
        if ($selectedCarrier) {
            $carrierPrice = $selectedCarrier->getCarrierPrice() / 100;
        }

        $form = $this->createForm(CheckoutType::class, null, ['user' => $user]);

        return $this->render('checkout/index.html.twig', [
            'controller_name' => 'CheckoutController',
            'cart' => $cart,
            'checkout' => $form->createView(),
            'addresses' => $addresses,
            'carriers' => $carriers,
            'carrierPrice' => $carrierPrice,


        ]);
    }




    #[Route('/valid', name: 'valid_checkout', methods: ['POST'])]
    public function Valid(Request $request, AddressRepository $addressRepository, ProductRepository $productRepository, EntityManagerInterface $entityManagerInterface, Security $security,  OrderRepository $orderRepository, CarrierRepository $carrierRepository): Response
    {



        $user = $security->getUser();

        // Vérifie que l'utilisateur est authentifié
        if (!$user) {
            $this->addFlash('error', 'Vous devez être authentifié pour valider votre panier.');
            return $this->redirectToRoute('app_login');
        }

        // Récupère les adresses de l'utilisateur
        $addresses = $addressRepository->findBy(['user' => $user]);


        // Récupère le panier de l'utilisateur
        $cart = $this->cartServices->getCart();
        // Récupère le contenu complet du panier de l'utilisateur
        $fullcart = $this->cartServices->getFullCart();

        // Vérifie que le panier n'est pas vide
        if (empty($fullcart['product'])) {
            $this->addFlash('error', 'Le panier est vide.');
            return $this->redirectToRoute('app_cart');
        }

        // Vérifie que l'utilisateur a au moins une adresse enregistrée
        if (!$addresses) {
            $this->addFlash('checkout_message', 'Merci de sélectionner une adresses pour valider !');
            return $this->redirectToRoute('app_address_new');
        }


        $carrierId = $request->request->get('carrier_id');

        $carriers = $carrierRepository->find($carrierId);


        $addressId = $request->request->get('deliveryAddress');
        $address = $addressRepository->find($addressId);


        // Vérifie que les produits sont disponibles en stock
        foreach ($cart as $id => $quantity) {
            $product = $productRepository->find($id);
            if ($product) {
                if ($product->getQuantity() < $quantity) {
                    $this->addFlash('error', 'Le produit ' . $product->getProductName() . ' n\'est plus disponible en stock.');
                    return $this->redirectToRoute('app_cart');
                }
            } else {
                // Produit non trouvé, on le retire du panier
                $this->cartServices->deleteFromCart($id);
            }
        }

        // Crée une nouvelle commande
        $order = new Order();
        // Crée un formulaire de validation de la commande
        $form = $this->createForm(CheckoutType::class, $order, ['user' => $user]);
        // Traite la requête de validation de la commande
        $form->handleRequest($request);


        // Met à jour les informations de la commande
        $order
            ->setUserOrder($user)
            ->setOrderPrice($fullcart['data']['subTotal'])
            ->setFullPrice($fullcart['data']['subTotalTtc'])
            ->setDiscount($fullcart['data']['discountTotal'])
            ->setOrderQuantity($fullcart['data']['quantityCart'])
            ->setDeleveryAddress($address)
            ->setCarrier($carriers);

        foreach ($cart as $id => $quantity) {
            $product = $productRepository->find($id);
            if ($product) {
                $orderDetail = new OrderDetail();
                $orderDetail->setProduct($product);
                $orderDetail->setQuantity($quantity);
                $orderDetail->setOrders($order);
                $entityManagerInterface->persist($orderDetail);
            }
        }


        // Enregistre la commande en base de données
        $entityManagerInterface->persist($order);

        // Met à jour le stock des produits achetés
        foreach ($cart as $id => $quantity) {
            $product = $productRepository->find($id);
            if ($product) {
                $product->setQuantity($product->getQuantity() - $quantity);
                $entityManagerInterface->persist($product);
            }
        }

        $entityManagerInterface->flush();

        // Vide le panier de l'utilisateur
        $this->cartServices->deleteCart();

        $this->addFlash('checkout_message', 'Votre commande a été enregistrée avec succès.');

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_home');
    }
}
