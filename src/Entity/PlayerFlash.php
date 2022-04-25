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
     * @ORM\ManyToMany(targetEntity=User::class)
     */
    private $leuser;

    /**
     * @ORM\ManyToOne(targetEntity=Enchere::class, inversedBy="playerFlashes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $laenchere;

    /**
     * @ORM\Column(type="boolean")
     */
    private $tag;

    public function __construct()
    {
        $this->leuser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getLeuser(): Collection
    {
        return $this->leuser;
    }

    public function addLeuser(User $leuser): self
    {
        if (!$this->leuser->contains($leuser)) {
            $this->leuser[] = $leuser;
        }

        return $this;
    }

    public function removeLeuser(User $leuser): self
    {
        $this->leuser->removeElement($leuser);

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

    public function getTag(): ?bool
    {
        return $this->tag;
    }

    public function setTag(bool $tag): self
    {
        $this->tag = $tag;

        return $this;
    }
}