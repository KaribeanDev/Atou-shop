<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/compte')]
class AddressController extends AbstractController
{
    #[Route('/address', name: 'app_address_index', methods: ['GET'])]
    public function index(AddressRepository $addressRepository): Response
    {
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('User/address/index.html.twig', [
            'addresses' => $addressRepository->findBy(array('user' => $user)),
        ]);
    }

    #[Route('/new', name: 'app_address_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {


        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $address->setUser($user);
            $entityManagerInterface->persist($address);
            $entityManagerInterface->flush();

          $this->addFlash('address_message', 'Votre adresse à bien été enregistrée');
            return $this->redirectToRoute('app_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('User/address/new.html.twig', [
            'address' => $address,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_address_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Address $address, AddressRepository $addressRepository): Response
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addressRepository->save($address, true);

            $this->addFlash('address_message', 'Votre adresse à bien été mise à jour');
            return $this->redirectToRoute('app_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('User/address/edit.html.twig', [
            'address' => $address,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_address_delete', methods: ['POST'])]
    public function delete(Request $request, Address $address,EntityManagerInterface $entityManager, AddressRepository $addressRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $address->getId(), $request->request->get('_token'))) {
            $address->setDeletedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $addressRepository->remove($address, true);


        }

        $this->addFlash('address_message', 'Votre adresse à bien été effacée');
        return $this->redirectToRoute('app_address_index', [], Response::HTTP_SEE_OTHER);
    }

}
