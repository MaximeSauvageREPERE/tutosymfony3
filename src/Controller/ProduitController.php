<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Produit;
use App\Form\ProduitType;

final class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $produits = $entityManager->getRepository(\App\Entity\Produit::class)->findBy([], ['nom' => 'ASC']);
        return $this->render('produit/index.html.twig', [
            'produits' => $produits
        ]);
    }

    #[Route('/produit/create', name: 'app_produit_create')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload d'image
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir').'/public/images/produits',
                        $newFilename
                    );
                    $produit->setImage($newFilename);
                } catch (FileException $e) {
                    // Gérer l'exception si quelque chose se passe mal pendant l'upload
                }
            }

            $entityManager->persist($produit);
            $entityManager->flush();
            return $this->redirectToRoute('app_produit');
        }

        return $this->render('produit/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit_show', requirements: ['id' => '\d+'])]
    public function show(Produit $produit): Response
    {
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/produit/{id}/edit', name: 'app_produit_edit', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload d'image
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir').'/public/images/produits',
                        $newFilename
                    );
                    $produit->setImage($newFilename);
                } catch (FileException $e) {
                    // Gérer l'exception si quelque chose se passe mal pendant l'upload
                }
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_produit');
        }
        return $this->render('produit/edit.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit,
        ]);
    }

    #[Route('/produit/{id}/delete', name: 'app_produit_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }
        $entityManager->remove($produit);
        $entityManager->flush();
        return $this->redirectToRoute('app_produit');
    }
}
