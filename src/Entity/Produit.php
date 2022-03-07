<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo;

    /**
     * @ORM\Column(type="float")
     */
    private $prixreel;

    /**
     * @ORM\ManyToMany(targetEntity=Magasin::class, mappedBy="lesproduits")
     * @Ignore()
     */
    private $lesmagasins;

    /**
     * @ORM\OneToMany(targetEntity=Enchere::class, mappedBy="leproduit")
     * @Ignore()
     */
    private $lesencheres;

    public function __construct()
    {
        $this->lesmagasins = new ArrayCollection();
        $this->lesencheres = new ArrayCollection();
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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPrixreel(): ?float
    {
        return $this->prixreel;
    }

    public function setPrixreel(float $prixreel): self
    {
        $this->prixreel = $prixreel;

        return $this;
    }

    /**
     * @return Collection<int, Magasin>
     */
    public function getLesmagasins(): Collection
    {
        return $this->lesmagasins;
    }

    public function addLesmagasin(Magasin $lesmagasin): self
    {
        if (!$this->lesmagasins->contains($lesmagasin)) {
            $this->lesmagasins[] = $lesmagasin;
            $lesmagasin->addLesproduit($this);
        }

        return $this;
    }

    public function removeLesmagasin(Magasin $lesmagasin): self
    {
        if ($this->lesmagasins->removeElement($lesmagasin)) {
            $lesmagasin->removeLesproduit($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Enchere>
     */
    public function getLesencheres(): Collection
    {
        return $this->lesencheres;
    }

    public function addLesenchere(Enchere $lesenchere): self
    {
        if (!$this->lesencheres->contains($lesenchere)) {
            $this->lesencheres[] = $lesenchere;
            $lesenchere->setLeproduit($this);
        }

        return $this;
    }

    public function removeLesenchere(Enchere $lesenchere): self
    {
        if ($this->lesencheres->removeElement($lesenchere)) {
            // set the owning side to null (unless already changed)
            if ($lesenchere->getLeproduit() === $this) {
                $lesenchere->setLeproduit(null);
            }
        }

        return $this;
    }
}
