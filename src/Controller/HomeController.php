<?php

namespace App\Controller;

use App\Services\Cart\CartServices;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/Accueil')]
class HomeController extends AbstractController
{


    private $cartServices;
    public function __construct(CartServices $cartServices)
    {
        $this->cartServices = $cartServices;
    }


    #[Route('/home', name: 'app_home', methods: ['GET'])]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, Request $request): Response
    {


        $prodMostViewed = $productRepository->findMostViewed();
        $categories = $categoryRepository->findAll();
        $products = $productRepository->findAll();

        $cart = $this->cartServices->getFullCart();

        $discountProduct = $productRepository->productDiscount();

        foreach ($discountProduct as $key => $product) {
            $productPrice = $product->getProductPrice();
            $discount = $product->getDiscount();
            $discountProduct[$key]->discountPrice = ($productPrice / 100) - ($discount / 100) * ($productPrice / 100);
        }







        return $this->render('Shop/home/index.html.twig', [
            'controller_name' => 'HomeController',
            'categories' => $categories,
            'products' => $products,
            'prodMostViewed' => $prodMostViewed,
            'discountProduct' => $discountProduct,

        ]);
    }

    #[Route('/home/search', name: 'app_search', methods: ['GET', 'POST'])]
    public function searchAction(Request $request, EntityManagerInterface $em, ProductRepository $products)
    {

        $form = $this->createFormBuilder()
            ->add('search', SearchType::class)
            ->getForm();

        $form->handleRequest($request);


        // if ($form->isSubmitted() && $form->isValid()) {
        $search = $form->getData()['search'];

        if (strlen($search) < 3) {
            throw new \Exception('minimum 3 caractère');
        }

        $query = $em->createQueryBuilder()
            ->select('p')
            ->from('App\Entity\Product', 'p')
            ->where('p.productName  LIKE :search')
            ->orWhere('p.productDescription LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery();


        $products = $query->getResult();
        // }

        return $this->render('home/search.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
        ]);
    }


    #[Route('/home', name: 'app_mostView', methods: ['GET'])]
    public function mostViewed(ProductRepository $productRepository): Response
    {
        $prodMostViewed = $productRepository->findMostViewed();

        return $this->render('home/index.html.twig', [
            'prodMostViewed' => $prodMostViewed,
        ]);
    }
}
