<?php

namespace App\Entity\Service\Consultation;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Payment\Payment;
use App\Repository\Service\Consultation\ConsultationRequestedDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ConsultationRequestedDetailRepository::class)
 * @ORM\Table(name="consultation_requested_details")
 * @ApiResource()
 */
class ConsultationRequestedDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=ConsultationRequested::class, inversedBy="consultationRequestedDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="La requête de consultation est requise")
     */
    private ?ConsultationRequested $consultationRequested = null;

    /**
     * @ORM\ManyToOne(targetEntity=ConsultationCategory::class, inversedBy="consultationRequestedDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le nom de la catégorie de consultation est attendue")
     */
    private ?ConsultationCategory $consultationCategory = null;

    /**
     * @ORM\ManyToOne(targetEntity=ConsultationCategoryCost::class, inversedBy="consultationRequestedDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le coût de la catégorie de consultation est requis")
     */
    private ?ConsultationCategoryCost $consultationCategoryCost = null;

    /**
     * @ORM\ManyToOne(targetEntity=Payment::class, inversedBy="consultationRequestedDetails")
     */
    private ?Payment $payment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConsultationRequested(): ?ConsultationRequested
    {
        return $this->consultationRequested;
    }

    public function setConsultationRequested(?ConsultationRequested $consultationRequested): self
    {
        $this->consultationRequested = $consultationRequested;

        return $this;
    }

    public function getConsultationCategory(): ?ConsultationCategory
    {
        return $this->consultationCategory;
    }

    public function setConsultationCategory(?ConsultationCategory $consultationCategory): self
    {
        $this->consultationCategory = $consultationCategory;

        return $this;
    }

    public function getConsultationName(): ?string
    {
        return !is_null($this->consultationCategory) ? $this->consultationCategory->getWording() : null;
    }

    public function getConsultationCode(): ?string
    {
        return !is_null($this->consultationCategory) ? $this->consultationCategory->getCode() : null;
    }

    public function getConsultationCategoryCost(): ?ConsultationCategoryCost
    {
        return $this->consultationCategoryCost;
    }

    public function setConsultationCategoryCost(?ConsultationCategoryCost $consultationCategoryCost): self
    {
        $this->consultationCategoryCost = $consultationCategoryCost;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return !is_null($this->consultationCategoryCost) ? $this->consultationCategoryCost->getUnitPrice() : null;
    }

    public function getConsultationDetails(): ?array
    {
        if (is_null($this->consultationCategory) || is_null($this->consultationCategoryCost)) {
            return null;
        }
        return [
            'consultationCode' => $this->getConsultationCode(),
            'consultationName' => $this->getConsultationName(),
            'unitPrice' => $this->getUnitPrice()
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
