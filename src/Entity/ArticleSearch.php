<?php

namespace App\Entity;

use App\Repository\ArticleSearchRepository;
use Doctrine\ORM\Mapping as ORM;

// #[ORM\Entity(repositoryClass: ArticleSearchRepository::class)]
class ArticleSearch
{
    /**
     * @ORM\Column(type="int", nullable=true)
     */
    private $minPrix;

    /**
     * @ORM\Column(type="int", nullable=true)
     */
    private $maxPrix;

    //  /**
    //  * @ORM\ManyToOne(targetEntity=DomainTicket::class, inversedBy="tickets")
    //  * @ORM\JoinColumn(nullable=false)
    //  */

    // private ?Genre $sexe = null;


    public function getminPrix(): ?int
    {
        return $this->minPrix;
    }

    public function setminPrix(?int $minPrix): self
    {
        $this->minPrix = $minPrix;

        return $this;
    }

    public function getmaxPrix(): ?int
    {
        return $this->maxPrix;
    }

    public function setmaxPrix(?int $maxPrix): self
    {
        $this->maxPrix = $maxPrix;

        return $this;
    }
 
}
