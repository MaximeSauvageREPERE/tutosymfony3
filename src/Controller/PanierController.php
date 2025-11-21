<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\LignePanier;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panier')]
final class PanierController extends AbstractController
{
    #[Route('', name: 'app_panier')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Pour l'instant, on récupère le dernier panier en cours
        $panier = $entityManager->getRepository(Panier::class)
            ->findOneBy(['statut' => 'en_cours'], ['createdAt' => 'DESC']);

        if (!$panier) {
            $panier = new Panier();
            $entityManager->persist($panier);
            $entityManager->flush();
        }

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/add/{id}', name: 'app_panier_add', requirements: ['id' => '\d+'])]
    public function add(Produit $produit, EntityManagerInterface $entityManager, Request $request): Response
    {
        $panier = $entityManager->getRepository(Panier::class)
            ->findOneBy(['statut' => 'en_cours'], ['createdAt' => 'DESC']);

        if (!$panier) {
            $panier = new Panier();
            $entityManager->persist($panier);
        }

        // Vérifier si le produit est déjà dans le panier
        $ligneExistante = null;
        foreach ($panier->getLignes() as $ligne) {
            if ($ligne->getProduit()->getId() === $produit->getId()) {
                $ligneExistante = $ligne;
                break;
            }
        }

        if ($ligneExistante) {
            $ligneExistante->setQuantite($ligneExistante->getQuantite() + 1);
        } else {
            $ligne = new LignePanier();
            $ligne->setProduit($produit);
            $ligne->setQuantite(1);
            $panier->addLigne($ligne);
        }

        $panier->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->flush();

        $this->addFlash('success', 'Produit ajouté au panier');
        
        // Rediriger vers la page précédente ou la liste des produits
        $referer = $request->headers->get('referer');
        if ($referer) {
            return $this->redirect($referer);
        }
        
        return $this->redirectToRoute('app_produit');
    }

    #[Route('/update/{id}', name: 'app_panier_update', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function update(LignePanier $ligne, Request $request, EntityManagerInterface $entityManager): Response
    {
        $quantite = (int) $request->request->get('quantite', 1);
        
        if ($quantite > 0) {
            $ligne->setQuantite($quantite);
            $ligne->getPanier()->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();
            $this->addFlash('success', 'Quantité mise à jour');
        }

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/remove/{id}', name: 'app_panier_remove', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function remove(LignePanier $ligne, EntityManagerInterface $entityManager): Response
    {
        $panier = $ligne->getPanier();
        $panier->removeLigne($ligne);
        $panier->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->remove($ligne);
        $entityManager->flush();

        $this->addFlash('success', 'Produit retiré du panier');
        return $this->redirectToRoute('app_panier');
    }

    #[Route('/clear', name: 'app_panier_clear', methods: ['POST'])]
    public function clear(EntityManagerInterface $entityManager): Response
    {
        $panier = $entityManager->getRepository(Panier::class)
            ->findOneBy(['statut' => 'en_cours'], ['createdAt' => 'DESC']);

        if ($panier) {
            foreach ($panier->getLignes() as $ligne) {
                $entityManager->remove($ligne);
            }
            $entityManager->remove($panier);
            $entityManager->flush();
            $this->addFlash('success', 'Panier vidé');
        }

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/validate', name: 'app_panier_validate', methods: ['POST'])]
    public function validate(EntityManagerInterface $entityManager): Response
    {
        $panier = $entityManager->getRepository(Panier::class)
            ->findOneBy(['statut' => 'en_cours'], ['createdAt' => 'DESC']);

        if ($panier && count($panier->getLignes()) > 0) {
            $panier->setStatut('valide');
            $panier->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();
            $this->addFlash('success', 'Commande validée');
        } else {
            $this->addFlash('error', 'Votre panier est vide');
        }

        return $this->redirectToRoute('app_panier');
    }
}
