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
    private ?CategorieArticle $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'Articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Genre $sexe = null;

    #[ORM\OneToMany(mappedBy: 'articles', targetEntity: CartDetails::class)]
    private Collection $cartDetails;


    public function __construct()
    {
        $this->favoris = new ArrayCollection();
        $this->cartDetails = new ArrayCollection();

        
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

    public function getCategorie(): ?CategorieArticle
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieArticle $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getSexe(): ?Genre
    {
        return $this->sexe;
    }

    public function setSexe(?Genre $sexe): self
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
   
}
