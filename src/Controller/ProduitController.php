<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
    #[Route('/produit/test', name: 'app_produit_test')]
    public function test(EntityManagerInterface $entityManager): Response
    {
        // Création de la catégorie
        $categorie = new \App\Entity\Category();
        $categorie->setNom('Catégorie Test');
        $categorie->setDescription('Catégorie de test pour les produits');
        $entityManager->persist($categorie);

        // Création du tag commun
        $tag = new \App\Entity\Tag();
        $tag->setNom('Tag Commun');
        $entityManager->persist($tag);

        // Création du premier produit
        $produit1 = new \App\Entity\Produit();
        $produit1->setNom('Produit 1');
        $produit1->setDescription('Premier produit de test');
        $produit1->setPrix(100);
        $produit1->setCreationdate(new \DateTime());
        $produit1->setActive(true);
        $produit1->setCategorie($categorie);
        $entityManager->persist($produit1);

        // Création du second produit
        $produit2 = new \App\Entity\Produit();
        $produit2->setNom('Produit 2');
        $produit2->setDescription('Deuxième produit de test');
        $produit2->setPrix(200);
        $produit2->setCreationdate(new \DateTime());
        $produit2->setActive(true);
        $produit2->setCategorie($categorie);
        $entityManager->persist($produit2);

        // Associer le tag aux deux produits
        $tag->setProduit($produit1);
        $produit1->addTag($tag);

        $tag2 = new \App\Entity\Tag();
        $tag2->setNom('Tag Commun');
        $tag2->setProduit($produit2);
        $produit2->addTag($tag2);
        $entityManager->persist($tag2);

        $entityManager->flush();

        return new Response('2 produits, une catégorie et un tag commun ont été créés.');
    }
}
