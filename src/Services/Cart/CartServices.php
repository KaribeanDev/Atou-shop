<?php

namespace App\Services\Cart;

use App\Repository\CarrierRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartServices
{
    private $requestStack;
    private $repoProduct;
    private $repoCarrier;
    private $tva = 20;

    public function __construct(RequestStack $requestStack, ProductRepository $repoProduct, CarrierRepository $repoCarrier)
    {
        $this->requestStack = $requestStack;
        $this->repoProduct = $repoProduct;
        $this->repoCarrier = $repoCarrier;
    }

    public function addToCart($id)
    {
        $cart = $this->getCart();
        if (isset($cart[$id])) {
            //produit déjà dans le panier on incrémente
            $cart[$id]++;
        } else {
            //produit pas encore dans le panier on ajoute
            $cart[$id] = 1;
        }
        $this->updateCart($cart);
    }

    public function deleteFromCart($id)
    {
        $cart = $this->getCart();
        //si produit déjà dans le panier 
        if (!empty($cart[$id])) {
            //si il y a plus d'une fois le produit dans le panier on décrémente
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                //Sinon on supprime
                unset($cart[$id]);
            }
            //on met à jour la session
            $this->updateCart($cart);
        }
    }

    public function deleteAllToCart($id)
    {
        $cart = $this->getCart();
        //si produit(s) déjà dans le panier 
        if (isset($cart[$id])) {
            //on supprime
            unset($cart[$id]);
        }
        //on met à jour la session
        $this->updateCart($cart);
    }

    public function deleteCart()
    {
        //on supprime tous les produits (on vide le panier)
        $this->updateCart([]);
    }

    public function updateCart($cart)
    {
        $session = $this->requestStack->getSession();
        return $session->set('cart', $cart);
    }

    public function getCart()
    {
        $session =  $this->requestStack->getSession();
        return $session->get('cart', []);
    }



    public function getFullCart()
    {
        $cart = $this->getCart();
        $fullCart = [];
        $quantityCart = 0;
        $TotalTtc = 0;
        $discountTotal = 0;

        // Récupère le transporteur
        $carrier = $this->repoCarrier->find(1);
        // Récupère le prix du transporteur
        $carrierPrice = $carrier->getCarrierPrice() / 100;

        foreach ($cart as $id => $quantity) {
            $product = $this->repoProduct->find($id);
            if ($product) {
                $productPrice = $product->getProductPrice() / 100;
                $productPriceWithDiscount = $productPrice;
                if ($product->getDiscount() !== null) {
                    $discount = ($productPrice * $product->getDiscount()) / 100;
                    $productPriceWithDiscount -= $discount;
                    $discountTotal += $discount;
                }
                $fullCart['product'][] = [
                    'quantity' => $quantity,
                    'product' => $product,
                    'discountPrice' => $productPriceWithDiscount,
                ];
                $quantityCart += $quantity;
                $TotalTtc += $quantity * $productPriceWithDiscount;
            } else {
                $this->deleteFromCart($id);
            }
        }
        // Ajoutez le prix du transporteur au subtotal TTC
        $fullCart['data'] = [
            'quantityCart' => $quantityCart,
            'subTotalTtc' => $TotalTtc,
            'taxe' => round(($TotalTtc / 120) * $this->tva, 2),
            'subTotal' => round($TotalTtc - (($TotalTtc / 120) * $this->tva), 2),
            'discountTotal' => $discountTotal,
            'carrierPrice' => $carrierPrice, 
        ];

        $session = $this->requestStack->getSession();
        $session->set('fullCart', $fullCart);

        return $fullCart;
    }
}
