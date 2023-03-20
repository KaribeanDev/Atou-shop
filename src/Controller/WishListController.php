<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\WishList;
use App\Repository\WishListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ma-liste-de-souhait')]
class WishListController extends AbstractController
{
    #[Route('/add', name: 'wishlist_add')]
    public function addToWishlist(Request $request, EntityManagerInterface $em)
    {

        $user = $this->getUser();

        // Récupérez le produit à ajouter à la liste de souhaits
        $productId = $request->query->get('productId');
        $product = $em->getRepository(Product::class)->find($productId);

        $inWishlist = $em->getRepository(WishList::class)->findOneBy([
            'user' => $user,
            'product' => $product
        ]);
        if ($inWishlist !== null) {
            $this->addFlash('addWishlist', 'Ce produit est déjà dans votre liste de souhaits.');
            return $this->redirect($request->headers->get('referer'));
        } else {
            // Créez un nouvel objet WishList et définissez ses propriétés
            $wishListItem = new WishList();
            $wishListItem->setProduct($product);
            $wishListItem->setUser($user);
            $wishListItem->setWishlistDate(new \DateTimeImmutable());

            // Enregistrez l'objet WishList dans la base de données
            $em->persist($wishListItem);
            $em->flush();

            $this->addFlash('addWishlist', 'Ajouté à la liste de souhait');

            return $this->redirect($request->headers->get('referer'));
        }
    }

    #[Route('/User/show', name: 'app_wishlist_show')]
    public function show(WishListRepository $wishListRepository)
    {

        $user = $this->getUser();
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }



        // Récupération de la liste de souhaits de l'utilisateur courant à partir de la base de données
        $wishList = $wishListRepository->findBy(['user' => $user]);

        return $this->render('User/wishlist/show.html.twig', ['wishList' => $wishList]);
    }

    #[Route('/delete/{productId}', name: 'wishlist_delete')]
    public function delete(int $productId, EntityManagerInterface $em, WishListRepository $wishListRepository, Request $request)
    {
        // Récupération de l'utilisateur courant
        $user = $this->getUser();

        // Récupération de l'entrée de liste de souhaits à supprimer à partir de la base de données
        $wishList = $wishListRepository->findOneBy(['product' => $productId, 'user' => $user]);

        if (!$wishList) {
            throw new \Exception('Wishlist entry not found');
        }

        // Suppression de l'entrée de liste de souhaits de la base de données
        $em->remove($wishList);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
