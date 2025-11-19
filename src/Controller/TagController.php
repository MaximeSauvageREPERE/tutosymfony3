<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TagController extends AbstractController
{
    #[Route('/tag', name: 'app_tag')]
    public function index(): Response
    {
        return $this->render('tag/index.html.twig', [
            'controller_name' => 'TagController',
        ]);
    }

    #[Route('/tag/create', name: 'app_tag_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tag = new Tag();
        $form = $this->createFormBuilder($tag)
            ->add('nom', TextType::class, [
                'label' => 'Nom du tag',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Créer le tag'
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tag);
            $entityManager->flush();
            return $this->redirectToRoute('app_tag');
        }

        return $this->render('tag/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}