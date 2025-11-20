<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;
use App\Form\ProduitType;

final class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $produits = $entityManager->getRepository(\App\Entity\Produit::class)->findAll();
        return $this->render('produit/index.html.twig', [
            'produits' => $produits
        ]);
    }

    #[Route('/produit/create', name: 'app_produit_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit);
            $entityManager->flush();
            return $this->redirectToRoute('app_produit');
        }

        return $this->render('produit/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit_show', requirements: ['id' => '\d+'])]
    public function show(Produit $produit = null): Response
    {
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/produit/{id}/edit', name: 'app_produit_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, Produit $produit = null, EntityManagerInterface $entityManager): Response
    {
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_produit');
        }
        return $this->render('produit/edit.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit,
        ]);
    }

    #[Route('/produit/{id}/delete', name: 'app_produit_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Produit $produit = null, EntityManagerInterface $entityManager): Response
    {
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }
        $entityManager->remove($produit);
        $entityManager->flush();
        return $this->redirectToRoute('app_produit');
    }
}
