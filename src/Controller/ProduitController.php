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
use Knp\Component\Pager\PaginatorInterface;

final class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $search = $request->query->get('search', '');
        
        if ($search) {
            // Recherche avec DQL pour chercher dans le nom du produit, la catégorie et les tags
            $qb = $entityManager->createQueryBuilder();
            $qb->select('DISTINCT p')
                ->from(Produit::class, 'p')
                ->leftJoin('p.categorie', 'c')
                ->leftJoin('p.tags', 't')
                ->where('p.nom LIKE :search OR c.nom LIKE :search OR t.nom LIKE :search')
                ->setParameter('search', '%' . $search . '%')
                ->orderBy('p.nom', 'ASC');
            
            $query = $qb->getQuery();
        } else {
            $query = $entityManager->getRepository(Produit::class)->createQueryBuilder('p')
                ->orderBy('p.nom', 'ASC')
                ->getQuery();
        }
        
        // Pagination : 12 produits par page
        $produits = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12
        );
        
        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'search' => $search,
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
        
        // Sauvegarder l'ancien nom de fichier
        $oldImage = $produit->getImage();
        
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload d'image
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                // Supprimer l'ancienne image si elle existe
                if ($oldImage) {
                    $oldImagePath = $this->getParameter('kernel.project_dir').'/public/images/produits/'.$oldImage;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
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
        
        // Supprimer l'image associée si elle existe
        if ($produit->getImage()) {
            $imagePath = $this->getParameter('kernel.project_dir').'/public/images/produits/'.$produit->getImage();
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $entityManager->remove($produit);
        $entityManager->flush();
        return $this->redirectToRoute('app_produit');
    }
}
