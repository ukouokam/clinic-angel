<?php

namespace App\Entity\Service\Drug;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Payment\Payment;
use App\Repository\Service\Drug\DrugRequestedDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DrugRequestedDetailRepository::class)
 * @ORM\Table(name="drug_requested_details")
 * @ApiResource()
 */
class DrugRequestedDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=DrugRequested::class, inversedBy="drugRequestedDetails")
     * @Assert\NotBlank(message="La requête (L'ordonnance) est requise")
     */
    private ?DrugRequested $drugRequested = null;

    /**
     * @ORM\ManyToOne(targetEntity=Drug::class, inversedBy="drugRequestedDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le médicament est requis")
     */
    private ?Drug $drug = null;

    /**
     * @ORM\ManyToOne(targetEntity=DrugCost::class, inversedBy="drugRequestedDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le coût du médicament est manquant")
     */
    private ?DrugCost $drugCost = null;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="La quantité de médicaments prescrits ne peut être vide")
     * @Assert\Type(type="int", message="La valeur fournie doit être un entier")
     * @Assert\Positive(message="La valeur fournie doit être un entier positif")
     */
    private ?int $quantity = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $comment = null;

    /**
     * @ORM\ManyToOne(targetEntity=Payment::class, inversedBy="drugRequestedDetail")
     */
    private ?Payment $payment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDrugRequested(): ?DrugRequested
    {
        return $this->drugRequested;
    }

    public function setDrugRequested(?DrugRequested $drugRequested): self
    {
        $this->drugRequested = $drugRequested;

        return $this;
    }

    public function getDrug(): ?Drug
    {
        return $this->drug;
    }

    public function setDrug(?Drug $drug): self
    {
        $this->drug = $drug;

        return $this;
    }

    public function getDrugPosology(): ?array
    {
        return !is_null($this->drug) ? [$this->drug->getWording() => $this->drug->getDrugPosology()] : null;
    }

    public function getDrugCost(): ?DrugCost
    {
        return $this->drugCost;
    }

    public function setDrugCost(?DrugCost $drugCost): self
    {
        $this->drugCost = $drugCost;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return !is_null($this->drugCost) ? $this->drugCost->getUnitPrice() : null;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->quantity * $this->getUnitPrice();
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

    public function getRequestDetail(): ?array
    {
        if (is_null($this->drug)) {
            return null;
        }
        return [
            'drugCode' => $this->drug->getCode(),
            'drugName' => $this->drug->getWording(),
            'drugCost' => $this->getUnitPrice(),
            'drugQuantity' => $this->getQuantity(),
            'drugAmount' => $this->getAmount()
        ];
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
}
