<?php

namespace App\Command;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:update-password',
    description: 'Met à jour le mot de passe d\'un utilisateur',
)]
class UpdateUserPasswordCommand extends Command
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
            ->addArgument('password', InputArgument::REQUIRED, 'Nouveau mot de passe')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $password = $input->getArgument('password');

        $utilisateur = $this->entityManager->getRepository(Utilisateur::class)->findOneBy(['name' => $name]);

        if (!$utilisateur) {
            $io->error(sprintf('Utilisateur "%s" non trouvé', $name));
            return Command::FAILURE;
        }

        // Hash le mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, $password);
        $utilisateur->setPassword($hashedPassword);

        $this->entityManager->flush();

        $io->success(sprintf('Mot de passe de l\'utilisateur "%s" mis à jour avec succès', $name));

        return Command::SUCCESS;
    }
}
