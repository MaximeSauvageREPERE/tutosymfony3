<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TagType;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TagController extends AbstractController
{
    #[Route('/tag', name: 'app_tag')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $tags = $entityManager->getRepository(Tag::class)->findAll();
        return $this->render('tag/index.html.twig', [
            'tags' => $tags,
        ]);
    }

    #[Route('/tag/create', name: 'app_tag_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Synchroniser la relation ManyToMany bidirectionnelle
            foreach ($tag->getProduits() as $produit) {
                $produit->addTag($tag);
            }
            
            $entityManager->persist($tag);
            $entityManager->flush();
            return $this->redirectToRoute('app_tag');
        }

        return $this->render('tag/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tag/{id}', name: 'app_tag_show', requirements: ['id' => '\d+'])]
    public function show(Tag $tag): Response
    {
        return $this->render('tag/show.html.twig', [
            'tag' => $tag,
        ]);
    }

    #[Route('/tag/{id}/edit', name: 'app_tag_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, Tag $tag, EntityManagerInterface $entityManager): Response
    {
        // Sauvegarder les produits actuels avant modification
        $originalProduits = [];
        foreach ($tag->getProduits() as $produit) {
            $originalProduits[] = $produit;
        }
        
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Retirer le tag des produits qui ne sont plus associés
            foreach ($originalProduits as $produit) {
                if (!$tag->getProduits()->contains($produit)) {
                    $produit->removeTag($tag);
                }
            }
            
            // Ajouter le tag aux nouveaux produits associés
            foreach ($tag->getProduits() as $produit) {
                if (!in_array($produit, $originalProduits, true)) {
                    $produit->addTag($tag);
                }
            }
            
            $entityManager->flush();
            return $this->redirectToRoute('app_tag');
        }
        return $this->render('tag/edit.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag,
        ]);
    }

    #[Route('/tag/{id}/delete', name: 'app_tag_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Tag $tag, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($tag);
        $entityManager->flush();
        return $this->redirectToRoute('app_tag');
    }
}