<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Services\Cart\CartServices;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/Shop')]
class ProductController extends AbstractController
{
    private $cartServices;

    public function __construct(CartServices $cartServices)
    {
        $this->cartServices = $cartServices;
    }

    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $product, CategoryRepository $categoryRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $sort = $request->query->get('sort');

        $data = $product->findBy(array(), array($sort  => 'DESC'));

        $discountProduct = $product->productDiscount();

        $cart = $this->cartServices->getFullCart();




        $productRepository = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            9

        );

        $latestProduct = $product->findBy([], ['id' => 'DESC'], 5);

        foreach ($discountProduct as $key => $product) {
            $productPrice = $product->getProductPrice();
            $discount = $product->getDiscount();
            $discountProduct[$key]->discountPrice = ($productPrice / 100) - ($discount / 100) * ($productPrice / 100);
        }

        return $this->render('Shop/product/index.html.twig', [
            'products' => $productRepository,
            'categories' => $categoryRepository->findAll(),
            'latestProduct' => $latestProduct,
            'data' => $data,
            'discountProduct' => $discountProduct,
            'sort' => $sort,
        ]);
    }


    #[Route('/{id}/{product}', name: 'app_product_show', methods: ['GET'])]
    public function show($id,$product, EntityManagerInterface $entityManagerInterface): Response
    {

        $cart = $this->cartServices->getFullCart();

        

        $detailQuantity = 1;

        $product = $entityManagerInterface->getRepository(Product::class)->find($id);


        $product->setMostViewed($product->getMostViewed() + 1);

        $entityManagerInterface->persist($product);
        $entityManagerInterface->flush();




        return $this->render('Shop/product/show.html.twig', [
            'product' => $product,
            'detailQuantity' => $detailQuantity
        ]);
    }


    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/plus/{id}', name: 'app_plus')]
    public function plus($id): Response
    {
        $this->cartServices->addToCart($id);
        return $this->redirectToRoute("app_product_show");
    }
}
