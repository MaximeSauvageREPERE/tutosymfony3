<?php

namespace App\Command;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Crée un nouvel utilisateur',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Nom d\'utilisateur')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'Créer un utilisateur admin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $password = $input->getArgument('password');
        $isAdmin = $input->getOption('admin');

        $utilisateur = new Utilisateur();
        $utilisateur->setName($name);
        
        // Hash le mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, $password);
        $utilisateur->setPassword($hashedPassword);
        
        // Ajouter le rôle admin si l'option est passée
        if ($isAdmin) {
            $utilisateur->addRole('ROLE_ADMIN');
        }

        $this->entityManager->persist($utilisateur);
        $this->entityManager->flush();

        $io->success(sprintf(
            'Utilisateur "%s" créé avec succès%s',
            $name,
            $isAdmin ? ' (admin)' : ''
        ));

        return Command::SUCCESS;
    }
}
