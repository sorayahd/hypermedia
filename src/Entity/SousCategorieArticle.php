<?php

namespace App\Entity;

use App\Repository\SousCategorieArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SousCategorieArticleRepository::class)]
class SousCategorieArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'sousCategorieArticles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategorieArticle $Categorie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCategorie(): ?CategorieArticle
    {
        return $this->Categorie;
    }

    public function setCategorie(?CategorieArticle $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }
}
