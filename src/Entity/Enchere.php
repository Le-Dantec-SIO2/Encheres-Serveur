<?php

namespace App\Entity;

use App\Repository\EnchereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass=EnchereRepository::class)
 */
class Enchere
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datedebut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datefin;

    /**
     * @ORM\Column(type="float")
     */
    private $prixreserve;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="lesencheres")
     */
    private $leproduit;

    /**
     * @ORM\ManyToOne(targetEntity=TypeEnchere::class, inversedBy="lesencheres")
     * @Ignore()
     */
    private $letypeenchere;

    /**
     * @ORM\OneToMany(targetEntity=Encherir::class, mappedBy="laenchere")
     * @Ignore()
     */
    private $lesencherirs;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visibilite;

    /**
     * @ORM\ManyToOne(targetEntity=Magasin::class, inversedBy="lesencheres")
     */
    private $lemagasin;

    /**
     * @ORM\Column(type="float")
     */
    private $prixdepart;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tableauFlash;

    /**
     * @ORM\OneToMany(targetEntity=PlayerFlash::class, mappedBy="Laenchere")
     */
    private $playerFlashes;

    public function __construct()
    {
        $this->lesencherirs = new ArrayCollection();
        $this->playerFlashes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getPrixreserve(): ?float
    {
        return $this->prixreserve;
    }

    public function setPrixreserve(float $prixreserve): self
    {
        $this->prixreserve = $prixreserve;

        return $this;
    }

    public function getLeproduit(): ?Produit
    {
        return $this->leproduit;
    }

    public function setLeproduit(?Produit $leproduit): self
    {
        $this->leproduit = $leproduit;

        return $this;
    }
       /**
     * @Ignore()
     */

    public function getLetypeenchere(): ?TypeEnchere
    {
        return $this->letypeenchere;
    }
   /**
     * @Ignore()
     */
    public function setLetypeenchere(?TypeEnchere $letypeenchere): self
    {
        $this->letypeenchere = $letypeenchere;

        return $this;
    }

    /**
     * @return Collection<int, Encherir>
     * @Ignore()
     */
    public function getLesencherirs(): Collection
    {
        return $this->lesencherirs;
    }

    public function addLesencherir(Encherir $lesencherir): self
    {
        if (!$this->lesencherirs->contains($lesencherir)) {
            $this->lesencherirs[] = $lesencherir;
            $lesencherir->setLaenchere($this);
        }

        return $this;
    }

    public function removeLesencherir(Encherir $lesencherir): self
    {
        if ($this->lesencherirs->removeElement($lesencherir)) {
            // set the owning side to null (unless already changed)
            if ($lesencherir->getLaenchere() === $this) {
                $lesencherir->setLaenchere(null);
            }
        }

        return $this;
    }

    public function getVisibilite(): ?bool
    {
        return $this->visibilite;
    }

    public function setVisibilite(bool $visibilite): self
    {
        $this->visibilite = $visibilite;

        return $this;
    }

    public function getLemagasin(): ?Magasin
    {
        return $this->lemagasin;
    }

    public function setLemagasin(?Magasin $lemagasin): self
    {
        $this->lemagasin = $lemagasin;

        return $this;
    }

    public function getPrixdepart(): ?float
    {
        return $this->prixdepart;
    }

    public function setPrixdepart(float $prixdepart): self
    {
        $this->prixdepart = $prixdepart;

        return $this;
    }

    public function getTableauFlash(): ?string
    {
        return $this->tableauFlash;
    }

    public function setTableauFlash(?string $tableauFlash): self
    {
        $this->tableauFlash = $tableauFlash;

        return $this;
    }

    /**
     * @return Collection<int, PlayerFlash>
     */
    public function getPlayerFlashes(): Collection
    {
        return $this->playerFlashes;
    }

    public function addPlayerFlash(PlayerFlash $playerFlash): self
    {
        if (!$this->playerFlashes->contains($playerFlash)) {
            $this->playerFlashes[] = $playerFlash;
            $playerFlash->setLaenchere($this);
        }

        return $this;
    }

    public function removePlayerFlash(PlayerFlash $playerFlash): self
    {
        if ($this->playerFlashes->removeElement($playerFlash)) {
            // set the owning side to null (unless already changed)
            if ($playerFlash->getLaenchere() === $this) {
                $playerFlash->setLaenchere(null);
            }
        }

        return $this;
    }
}