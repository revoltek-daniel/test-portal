<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResultRepository::class)
 */
class Result
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="results")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user = null;

    /**
     * @ORM\OneToMany(targetEntity=ResultStep::class, mappedBy="result", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $stepResults;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->stepResults = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|ResultStep[]
     */
    public function getStepResults(): Collection
    {
        return $this->stepResults;
    }

    public function addStepResult(ResultStep $stepResult): self
    {
        if (!$this->stepResults->contains($stepResult)) {
            $this->stepResults[] = $stepResult;
            $stepResult->setResult($this);
        }

        return $this;
    }

    public function removeStepResult(ResultStep $stepResult): self
    {
        if ($this->stepResults->removeElement($stepResult)) {
            // set the owning side to null (unless already changed)
            if ($stepResult->getResult() === $this) {
                $stepResult->setResult(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
