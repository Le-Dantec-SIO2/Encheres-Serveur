<?php

namespace App\Entity;

use App\Repository\PlayerFlashRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerFlashRepository::class)
 */
class PlayerFlash
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=user::class)
     */
    private $leuser;

    /**
     * @ORM\ManyToOne(targetEntity=enchere::class, inversedBy="playerFlashes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $laenchere;

    /**
     * @ORM\Column(type="integer")
     */
    private $ordrePassage;


    public function __construct()
    {
        $this->leuser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, user>
     */
    public function getLeuser(): Collection
    {
        return $this->leuser;
    }

    public function addLeuser(user $leuser): self
    {
        if (!$this->leuser->contains($leuser)) {
            $this->leuser[] = $leuser;
        }

        return $this;
    }

    public function removeLeuser(user $leuser): self
    {
        $this->leuser->removeElement($leuser);

        return $this;
    }

    public function getLaenchere(): ?enchere
    {
        return $this->laenchere;
    }

    public function setLaenchere(?enchere $laenchere): self
    {
        $this->laenchere = $laenchere;

        return $this;
    }

    public function getOrdrepassage(): ?int
    {
        return $this->ordrepassage;
    }

    public function setOrdrepassage(int $ordrepassage): self
    {
        $this->ordrepassage = $ordrepassage;

        return $this;
    }
}