<?php

namespace App\Entity;

use App\Repository\TypePersoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypePersoRepository::class)
 */
class TypePerso
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
     * @ORM\OneToMany(targetEntity=Personnel::class, mappedBy="typePerso")
     */
    private $personnel;

    public function __construct()
    {
        $this->personnel = new ArrayCollection();
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
     * @return Collection<int, Personnel>
     */
    public function getPersonnel(): Collection
    {
        return $this->personnel;
    }

    public function addPersonnel(Personnel $personnel): self
    {
        if (!$this->personnel->contains($personnel)) {
            $this->personnel[] = $personnel;
            $personnel->setTypePerso($this);
        }

        return $this;
    }

    public function removePersonnel(Personnel $personnel): self
    {
        if ($this->personnel->removeElement($personnel)) {
            // set the owning side to null (unless already changed)
            if ($personnel->getTypePerso() === $this) {
                $personnel->setTypePerso(null);
            }
        }

        return $this;
    }
}
