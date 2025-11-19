<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(\Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        // Création d'un utilisateur simple
        $utilisateur = new \App\Entity\Utilisateur();
        $utilisateur->setName('Maxime');
        $utilisateur->setPassword('password123');

        // Création d'un admin avec un statut
        $admin = new \App\Entity\Admin();
        $admin->setName('AdminUser');
        $admin->setPassword('adminpass456');
        $admin->setStatut('SuperAdmin');

        // Création d'un modérateur avec un niveau
        $moderateur = new \App\Entity\Moderateur();
        $moderateur->setName('ModUser');
        $moderateur->setPassword('modpass789');
        $moderateur->setNiveau(5);

        // Persistance des entités en base de données
        $entityManager->persist($moderateur);
        $entityManager->persist($admin);
        $entityManager->persist($utilisateur);
        $entityManager->flush();

        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }
}
