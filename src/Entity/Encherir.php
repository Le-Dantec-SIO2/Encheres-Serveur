<?php

namespace App\Entity;

use App\Repository\EncherirRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EncherirRepository::class)
 */
class Encherir
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $prixenchere;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateenchere;

    /**
     * @ORM\ManyToOne(targetEntity=Enchere::class, inversedBy="lesencherirs")
     */
    private $laenchere;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="lesencherirs")
     */
    private $leuser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixenchere(): ?float
    {
        return $this->prixenchere;
    }

    public function setPrixenchere(float $prixenchere): self
    {
        $this->prixenchere = $prixenchere;

        return $this;
    }

    public function getDateenchere(): ?\DateTimeInterface
    {
        return $this->dateenchere;
    }

    public function setDateenchere(\DateTimeInterface $dateenchere): self
    {
        $this->dateenchere = $dateenchere;

        return $this;
    }

    public function getLaenchere(): ?Enchere
    {
        return $this->laenchere;
    }

    public function setLaenchere(?Enchere $laenchere): self
    {
        $this->laenchere = $laenchere;

        return $this;
    }

    public function getLeuser(): ?User
    {
        return $this->leuser;
    }

    public function setLeuser(?User $leuser): self
    {
        $this->leuser = $leuser;

        return $this;
    }
}
