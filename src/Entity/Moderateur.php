<?php

namespace App\Entity;

use App\Repository\ModerateurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModerateurRepository::class)]
class Moderateur extends Utilisateur
{
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $niveau = null;

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(?int $niveau): static
    {
        $this->niveau = $niveau;
        return $this;
    }
    // Les propriétés et méthodes sont héritées de Utilisateur
}
