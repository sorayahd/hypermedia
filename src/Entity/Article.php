<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]


class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'favoris')]
    private Collection $favoris;

    #[ORM\Column(nullable: true)]
    private ?int $promotion = null;



    // #[ORM\ManyToOne(inversedBy: 'articles')]


    // #[ORM\Column(length: 255)]
    // private ?string $genre = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ? CategorieArticle $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'Articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ? Genre $sexe = null;

    #[ORM\OneToMany(mappedBy: 'articles', targetEntity: CartDetails::class)]
    private Collection $cartDetails;

    #[ORM\ManyToMany(targetEntity: Taille::class, inversedBy: 'articlesTaille')]
    private Collection $taille;

    #[ORM\OneToMany(mappedBy: 'articls', targetEntity: TailleArticle::class)]
    private Collection $tailleArticles;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: ArticleLike::class)]
    private Collection $Likes;

    #[ORM\Column(nullable: true)]
    
    private ?int $nombreLike = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Comments::class, orphanRemoval: true)]
    private Collection $comments;



    public function __construct()
    {
        $this->favoris = new ArrayCollection();
        $this->cartDetails = new ArrayCollection();
        $this->taille = new ArrayCollection();
        $this->tailleArticles = new ArrayCollection();
        $this->Likes = new ArrayCollection();
        $this->comments = new ArrayCollection();


    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(user $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
        }

        return $this;
    }

    public function removeFavori(user $favori): self
    {
        $this->favoris->removeElement($favori);

        return $this;
    }

    public function getPromotion(): ?int
    {
        return $this->promotion;
    }

    public function setPromotion(?int $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }




    // /**
    //  * @return Collection<int, ArticlePanier>
    //  */
    // public function getArticlePaniers(): Collection
    // {
    //     return $this->articlePaniers;
    // }

    // public function addArticlePanier(ArticlePanier $articlePanier): self
    // {
    //     if (!$this->articlePaniers->contains($articlePanier)) {
    //         $this->articlePaniers->add($articlePanier);
    //         $articlePanier->setArticle($this);
    //     }

    //     return $this;
    // }

    // public function removeArticlePanier(ArticlePanier $articlePanier): self
    // {
    //     if ($this->articlePaniers->removeElement($articlePanier)) {
    //         // set the owning side to null (unless already changed)
    //         if ($articlePanier->getArticle() === $this) {
    //             $articlePanier->setArticle(null);
    //         }
    //     }

    //     return $this;
    // }






    // public function getGenre(): ?string
    // {
    //     return $this->genre;
    // }

    // public function setGenre(string $genre): self
    // {
    //     $this->genre = $genre;

    //     return $this;
    // }

    public function getCategorie(): ? CategorieArticle
    {
        return $this->categorie;
    }

    public function setCategorie(? CategorieArticle $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getSexe(): ? Genre
    {
        return $this->sexe;
    }

    public function setSexe(? Genre $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * @return Collection<int, CartDetails>
     */
    public function getCartDetails(): Collection
    {
        return $this->cartDetails;
    }

    public function addCartDetail(CartDetails $cartDetail): self
    {
        if (!$this->cartDetails->contains($cartDetail)) {
            $this->cartDetails->add($cartDetail);
            $cartDetail->setArticles($this);
        }

        return $this;
    }

    public function removeCartDetail(CartDetails $cartDetail): self
    {
        if ($this->cartDetails->removeElement($cartDetail)) {
            // set the owning side to null (unless already changed)
            if ($cartDetail->getArticles() === $this) {
                $cartDetail->setArticles(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Taille>
     */
    public function getTaille(): Collection
    {
        return $this->taille;
    }

    public function addTaille(Taille $taille): self
    {
        if (!$this->taille->contains($taille)) {
            $this->taille->add($taille);
        }

        return $this;
    }

    public function removeTaille(Taille $taille): self
    {
        $this->taille->removeElement($taille);

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
            $tailleArticle->setArticls($this);
        }

        return $this;
    }

    public function removeTailleArticle(TailleArticle $tailleArticle): self
    {
        if ($this->tailleArticles->removeElement($tailleArticle)) {
            // set the owning side to null (unless already changed)
            if ($tailleArticle->getArticls() === $this) {
                $tailleArticle->setArticls(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ArticleLike>
     */
    public function getLikes(): Collection
    {
        return $this->Likes;
    }

    public function addLike(ArticleLike $like): self
    {
        if (!$this->Likes->contains($like)) {
            $this->Likes->add($like);
            $like->setArticle($this);
        }

        return $this;
    }

    public function removeLike(ArticleLike $like): self
    {
        if ($this->Likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getArticle() === $this) {
                $like->setArticle(null);
            }
        }

        return $this;
    }
    /**
     * Summary of isLikedByUser
     * @param User $user
     * @return bool
     */
    public function isLikedByUser(User $user): bool
    {
        foreach ($this->Likes as $like) {
            if ($like->getUser() == $user)

                return true;

        }
        return false;
    }

    public function getnombreLike(): ?int
    {
        return $this->nombreLike;
    }

    public function setnombreLike(?int $nombreLike): self
    {
        $this->nombreLike = $nombreLike;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }
}