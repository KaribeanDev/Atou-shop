<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Services\Cart\CartServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/Shop/cart')]
class CartController extends AbstractController
{

    private $cartServices;
    public function __construct(CartServices $cartServices)
    {
        $this->cartServices = $cartServices;
    }

    #[Route('/', name: 'app_cart')]
    public function index(ProductRepository $product): Response
    {
        $cart = $this->cartServices->getFullCart();
        if (!isset($cart['product'])) {
            return $this->redirectToRoute('app_home');
        }

        

        return $this->render('User/cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cart,
            'product' => $product,
        ]);
    }

    #[Route('/add/{id}', name: 'app_cart_add')]
    public function addCart($id, Request $request): Response
    {

        $this->cartServices->addToCart($id);
        $this->addFlash('addCart', 'Ajouté à votre panier.');

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/remove/{id}', name: 'app_cart_remove')]
    public function removeCart($id): Response
    {

        $this->cartServices->deleteFromCart($id);

        return $this->redirectToRoute("app_cart");
    }



    #[Route('/delete/{id}', name: 'app_cart_delete')]
    public function deleteCart($id)
    {

        $this->cartServices->deleteAllToCart($id);
        $this->addFlash('addCart', 'Supprimé du panier.');

        return $this->redirectToRoute("app_cart");
    }

    #[Route('/deleteAll', name: 'app_cart_delete_all')]
    public function deleteAllCart()
    {

        $this->cartServices->deleteCart();
        $this->addFlash('addCart', 'Panier vidé.');


        return $this->redirectToRoute("app_cart");
    }
}
