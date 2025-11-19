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
    #[Route('/produit/{id}', name: 'app_produit_show')]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }
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

    #[Route('/produit/{id}/delete', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($produit);
        $entityManager->flush();
        return $this->redirectToRoute('app_produit');
    }

    #[Route('/produit/test', name: 'app_produit_test')]
    public function test(EntityManagerInterface $entityManager): Response
    {
        // 1. On crée d'abord la catégorie, car chaque produit doit y être rattaché
        $categorie = new \App\Entity\Category();
        $categorie->setNom('Catégorie Test');
        $categorie->setDescription('Catégorie de test pour les produits');
        $entityManager->persist($categorie);

        // 2. On crée un premier tag, qui sera associé au premier produit
        $tag = new \App\Entity\Tag();
        $tag->setNom('Tag Commun');
        $entityManager->persist($tag);

        // 3. On crée le premier produit, puis on l'associe à la catégorie et au tag
        $produit1 = new \App\Entity\Produit();
        $produit1->setNom('Produit 1');
        $produit1->setDescription('Premier produit de test');
        $produit1->setPrix(100);
        $produit1->setCreationdate(new \DateTime());
        $produit1->setActive(true);
        $produit1->setCategorie($categorie);
        $entityManager->persist($produit1);

        // On associe le tag au produit 1 (relation ManyToOne côté Tag)
        $tag->setProduit($produit1);
        $produit1->addTag($tag);

        // 4. On crée le second produit, puis on l'associe à la catégorie
        $produit2 = new \App\Entity\Produit();
        $produit2->setNom('Produit 2');
        $produit2->setDescription('Deuxième produit de test');
        $produit2->setPrix(200);
        $produit2->setCreationdate(new \DateTime());
        $produit2->setActive(true);
        $produit2->setCategorie($categorie);
        $entityManager->persist($produit2);

        // 5. On crée un second tag, qu'on associe au produit 2
        $tag2 = new \App\Entity\Tag();
        $tag2->setNom('Tag Commun');
        $tag2->setProduit($produit2);
        $produit2->addTag($tag2);
        $entityManager->persist($tag2);

        // 6. On enregistre toutes les entités en base
        $entityManager->flush();

        return new Response('2 produits, une catégorie et un tag commun ont été créés.');
    }
}
