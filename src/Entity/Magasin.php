<?php

namespace App\Entity;

use App\Repository\MagasinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MagasinRepository::class)
 */
class Magasin
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
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codepostal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $portable;

    /**
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;

    /**
     * @ORM\ManyToMany(targetEntity=Produit::class, inversedBy="lesmagasins")
     */
    private $lesproduits;

<<<<<<< HEAD
    /**
     * @ORM\OneToMany(targetEntity=Enchere::class, mappedBy="leMagasin", orphanRemoval=true)
     */
    private $lesencheres;

    public function __construct()
    {
        $this->lesproduits = new ArrayCollection();
        $this->lesencheres = new ArrayCollection();
=======
    public function __construct()
    {
        $this->lesproduits = new ArrayCollection();
>>>>>>> parent of 16044c7 (ajout magasin dans encher)
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodepostal(): ?string
    {
        return $this->codepostal;
    }

    public function setCodepostal(string $codepostal): self
    {
        $this->codepostal = $codepostal;

        return $this;
    }

    public function getPortable(): ?string
    {
        return $this->portable;
    }

    public function setPortable(string $portable): self
    {
        $this->portable = $portable;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getLesproduits(): Collection
    {
        return $this->lesproduits;
    }

    public function addLesproduit(Produit $lesproduit): self
    {
        if (!$this->lesproduits->contains($lesproduit)) {
            $this->lesproduits[] = $lesproduit;
        }

        return $this;
    }

    public function removeLesproduit(Produit $lesproduit): self
    {
        $this->lesproduits->removeElement($lesproduit);

        return $this;
    }
<<<<<<< HEAD

    /**
     * @return Collection<int, Enchere>
     */
    public function getLesEncheres(): Collection
    {
        return $this->lesencheres;
    }

    public function addLesEnchere(Enchere $lesEnchere): self
    {
        if (!$this->lesencheres->contains($lesEnchere)) {
            $this->lesencheres[] = $lesEnchere;
            $lesEnchere->setLeMagasin($this);
        }

        return $this;
    }

    public function removeLesEnchere(Enchere $lesEnchere): self
    {
        if ($this->lesencheres->removeElement($lesEnchere)) {
            // set the owning side to null (unless already changed)
            if ($lesEnchere->getLeMagasin() === $this) {
                $lesEnchere->setLeMagasin(null);
            }
        }

        return $this;
    }
=======
>>>>>>> parent of 16044c7 (ajout magasin dans encher)
}