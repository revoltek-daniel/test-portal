<?php

namespace App\Entity;

use App\Repository\ResultStepRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResultStepRepository::class)
 */
class ResultStep
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Step::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Step $step;

    /**
     * @ORM\Column(type="integer")
     */
    private int $total = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $correct = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $processed = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Result::class, inversedBy="stepResults")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Result $result = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStep(): ?Step
    {
        return $this->step;
    }

    public function setStep(?Step $step): self
    {
        $this->step = $step;

        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getCorrect(): int
    {
        return $this->correct;
    }

    public function setCorrect(int $correct): self
    {
        $this->correct = $correct;

        return $this;
    }

    public function getProcessed(): int
    {
        return $this->processed;
    }

    public function setProcessed(int $processed): self
    {
        $this->processed = $processed;

        return $this;
    }

    public function getResult(): ?Result
    {
        return $this->result;
    }

    public function setResult(?Result $result): self
    {
        $this->result = $result;

        return $this;
    }
}
