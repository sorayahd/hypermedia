<?php

namespace App\Entity;

use App\Repository\CategorieArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieArticleRepository::class)]
class CategorieArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nome = null;

    #[ORM\OneToMany(mappedBy: 'Categorie', targetEntity: SousCategorieArticle::class)]
    private Collection $sousCategorieArticles;

    public function __construct()
    {
        $this->sousCategorieArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(?string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * @return Collection<int, SousCategorieArticle>
     */
    public function getSousCategorieArticles(): Collection
    {
        return $this->sousCategorieArticles;
    }

    public function addSousCategorieArticle(SousCategorieArticle $sousCategorieArticle): self
    {
        if (!$this->sousCategorieArticles->contains($sousCategorieArticle)) {
            $this->sousCategorieArticles->add($sousCategorieArticle);
            $sousCategorieArticle->setCategorie($this);
        }

        return $this;
    }

    public function removeSousCategorieArticle(SousCategorieArticle $sousCategorieArticle): self
    {
        if ($this->sousCategorieArticles->removeElement($sousCategorieArticle)) {
            // set the owning side to null (unless already changed)
            if ($sousCategorieArticle->getCategorie() === $this) {
                $sousCategorieArticle->setCategorie(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nome;
    }
}
