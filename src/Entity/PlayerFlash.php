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
    private $Leuser;

    /**
     * @ORM\ManyToOne(targetEntity=enchere::class, inversedBy="playerFlashes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Laenchere;

    /**
     * @ORM\Column(type="integer")
     */
    private $OrdrePassage;


    public function __construct()
    {
        $this->Leuser = new ArrayCollection();
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
        return $this->Leuser;
    }

    public function addLeuser(user $leuser): self
    {
        if (!$this->Leuser->contains($leuser)) {
            $this->Leuser[] = $leuser;
        }

        return $this;
    }

    public function removeLeuser(user $leuser): self
    {
        $this->Leuser->removeElement($leuser);

        return $this;
    }

    public function getLaenchere(): ?enchere
    {
        return $this->Laenchere;
    }

    public function setLaenchere(?enchere $Laenchere): self
    {
        $this->Laenchere = $Laenchere;

        return $this;
    }

    public function getOrdrePassage(): ?int
    {
        return $this->OrdrePassage;
    }

    public function setOrdrePassage(int $OrdrePassage): self
    {
        $this->OrdrePassage = $OrdrePassage;

        return $this;
    }
}