<?php

namespace App\Entity;

use App\Repository\SearchByTailleRepository;
use Doctrine\ORM\Mapping as ORM;

// #[ORM\Entity(repositoryClass: SearchByTailleRepository::class)]
class SearchByTaille
{
    
    #[ORM\Column(nullable: true)]
    private ?int $minTaille = null;

    #[ORM\Column(nullable: true)]
    private ?int $MaxTaille = null;

   

    public function getMinTaille(): ?int
    {
        return $this->minTaille;
    }

    public function setMinTaille(?int $minTaille): self
    {
        $this->minTaille = $minTaille;

        return $this;
    }

    public function getMaxTaille(): ?int
    {
        return $this->MaxTaille;
    }

    public function setMaxTaille(?int $MaxTaille): self
    {
        $this->MaxTaille = $MaxTaille;

        return $this;
    }
}
