<?php

namespace App\Entity;

use App\Repository\TypeEnchereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass=TypeEnchereRepository::class)
 */
class TypeEnchere
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
     * @ORM\OneToMany(targetEntity=Enchere::class, mappedBy="letypeenchere")
     * @Ignore()
     */
    private $lesencheres;

    public function __construct()
    {
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
            $lesenchere->setLetypeenchere($this);
        }

        return $this;
    }

    public function removeLesenchere(Enchere $lesenchere): self
    {
        if ($this->lesencheres->removeElement($lesenchere)) {
            // set the owning side to null (unless already changed)
            if ($lesenchere->getLetypeenchere() === $this) {
                $lesenchere->setLetypeenchere(null);
            }
        }

        return $this;
    }
}