<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    private ?Transporteur $transporteur = null;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adresse $adresse = null;

    // #[ORM\ManyToMany(targetEntity: ArticlePanier::class, mappedBy: 'cart')]
   // private Collection $articlePaniers;

    #[ORM\ManyToMany(targetEntity: Article::class, inversedBy: 'carts')]
    private Collection $article;

    #[ORM\OneToMany(mappedBy: 'Cart', targetEntity: CartDetails::class, orphanRemoval: true)]
    private Collection $cartDetails;

    #[ORM\Column]
    private ?float $Total = null;

    #[ORM\Column]
    private ?int $nbArticle = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    public function __construct()
    {
        
        $this->article = new ArrayCollection();
        $this->cartDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransporteur(): ?Transporteur
    {
        return $this->transporteur;
    }

    public function setTransporteur(?Transporteur $transporteur): self
    {
        $this->transporteur = $transporteur;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): self
    {
        $this->adresse = $adresse;

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
    //         $articlePanier->addCart($this);
    //     }

    //     return $this;
    // }

    // public function removeArticlePanier(ArticlePanier $articlePanier): self
    // {
    //     if ($this->articlePaniers->removeElement($articlePanier)) {
    //         $articlePanier->removeCart($this);
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, Article>
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->article->contains($article)) {
            $this->article->add($article);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        $this->article->removeElement($article);

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
            $cartDetail->setCart($this);
        }

        return $this;
    }

    public function removeCartDetail(CartDetails $cartDetail): self
    {
        if ($this->cartDetails->removeElement($cartDetail)) {
            // set the owning side to null (unless already changed)
            if ($cartDetail->getCart() === $this) {
                $cartDetail->setCart(null);
            }
        }

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->Total;
    }

    public function setTotal(float $Total): self
    {
        $this->Total = $Total;

        return $this;
    }

    public function getNbArticle(): ?int
    {
        return $this->nbArticle;
    }

    public function setNbArticle(int $nbArticle): self
    {
        $this->nbArticle = $nbArticle;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }
}
