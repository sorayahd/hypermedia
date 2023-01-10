<?php

namespace App\Entity;

use App\Repository\StatusCommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusCommandeRepository::class)]
class StatusCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\OneToMany(mappedBy: 'status', targetEntity: Cart::class)]
    private Collection $carts;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
        //$this->status = new StatusCommande;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Cart>
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts->add($cart);
            $cart->setStatus($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getStatus() === $this) {
                $cart->setStatus(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->status;
    }
}
