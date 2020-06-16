<?php

namespace App\Entity\Payment;

use App\Entity\Person\User\User;
use App\Repository\Payment\AdvancePaymentRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdvancePaymentRepository::class)
 * @ORM\Table(name="advance_payments")
 */
class AdvancePayment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Payment::class, inversedBy="advancePayments")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Payment $payment = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="advancePayments")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $createdBy = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $amount = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $comment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
