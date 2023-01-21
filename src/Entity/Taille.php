<?php

namespace App\Entity;

use App\Repository\TailleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TailleRepository::class)]
class Taille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $taille = null;

    #[ORM\OneToMany(mappedBy: 'taille', targetEntity: Article::class)]
    private Collection $articles;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'taille')]
    private Collection $articlesTaille;

    #[ORM\OneToMany(mappedBy: 'taille', targetEntity: TailleArticle::class)]
    private Collection $tailleArticles;

    #[ORM\Column(nullable: true)]
    private ?float $tailleMin = null;

    #[ORM\Column(nullable: true)]
    private ?float $tailleMax = null;

    #[ORM\Column(nullable: true)]
    private ?int $poidsMin = null;

    #[ORM\Column(nullable: true)]
    private ?float $poidsMax = null;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->articlesTaille = new ArrayCollection();
        $this->tailleArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(string $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticlesTaille(): Collection
    {
        return $this->articlesTaille;
    }

    public function addArticlesTaille(Article $articlesTaille): self
    {
        if (!$this->articlesTaille->contains($articlesTaille)) {
            $this->articlesTaille->add($articlesTaille);
            $articlesTaille->addTaille($this);
        }

        return $this;
    }

    public function removeArticlesTaille(Article $articlesTaille): self
    {
        if ($this->articlesTaille->removeElement($articlesTaille)) {
            $articlesTaille->removeTaille($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, TailleArticle>
     */
    public function getTailleArticles(): Collection
    {
        return $this->tailleArticles;
    }

    public function addTailleArticle(TailleArticle $tailleArticle): self
    {
        if (!$this->tailleArticles->contains($tailleArticle)) {
            $this->tailleArticles->add($tailleArticle);
            $tailleArticle->setTaille($this);
        }

        return $this;
    }

    public function removeTailleArticle(TailleArticle $tailleArticle): self
    {
        if ($this->tailleArticles->removeElement($tailleArticle)) {
            // set the owning side to null (unless already changed)
            if ($tailleArticle->getTaille() === $this) {
                $tailleArticle->setTaille(null);
            }
        }

        return $this;
    }

   
    public function __toString()
    {
    
        

        return $this->taille;
    }

    public function getTailleMin(): ?float
    {
        return $this->tailleMin;
    }

    public function setTailleMin(?float $tailleMin): self
    {
        $this->tailleMin = $tailleMin;

        return $this;
    }

    public function getTailleMax(): ?float
    {
        return $this->tailleMax;
    }

    public function setTailleMax(?float $tailleMax): self
    {
        $this->tailleMax = $tailleMax;

        return $this;
    }

    public function getPoidsMin(): ?int
    {
        return $this->poidsMin;
    }

    public function setPoidsMin(?int $poidsMin): self
    {
        $this->poidsMin = $poidsMin;

        return $this;
    }

    public function getPoidsMax(): ?float
    {
        return $this->poidsMax;
    }

    public function setPoidsMax(?float $poidsMax): self
    {
        $this->poidsMax = $poidsMax;

        return $this;
    }
}
