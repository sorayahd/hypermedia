<?php

namespace App\Entity;

use App\Repository\TailleArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TailleArticleRepository::class)]
class TailleArticle
{

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'tailleArticles')]
    #[ORM\JoinColumn(nullable: false)]
    private ? Article $articls = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'tailleArticles')]
    private ? Taille $taille = null;


    public function getArticls(): ? Article
    {
        return $this->articls;
    }

    public function setArticls(? Article $articls): self
    {
        $this->articls = $articls;

        return $this;
    }

    public function getTaille(): ? Taille
    {
        return $this->taille;
    }

    public function setTaille(? Taille $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * toString
     * @return string
     */
    public function __toString()
    {
        return $this->taille->getTaille();

    }
}