<?php

namespace App\Entity\Service\MedicalAct;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Payment\Payment;
use App\Repository\Service\MedicalAct\MedicalActRequestedDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MedicalActRequestedDetailRepository::class)
 * @ORM\Table(name="medical_act_requested_details")
 * @ApiResource()
 */
class MedicalActRequestedDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=MedicalActRequested::class, inversedBy="medicalActRequestedDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="La requête de l'acte médical est requise")
     */
    private ?MedicalActRequested $medicalActRequested = null;

    /**
     * @ORM\ManyToOne(targetEntity=MedicalAct::class, inversedBy="medicalActRequestedDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'acte médical est requis")
     */
    private ?MedicalAct $medicalAct = null;

    /**
     * @ORM\ManyToOne(targetEntity=MedicalActCost::class, inversedBy="medicalActRequestedDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le cout de l'acte médical est manquant")
     */
    private ?MedicalActCost $medicalActCost = null;

    /**
     * @ORM\ManyToOne(targetEntity=Payment::class, inversedBy="medicalActRequestedDetails")
     */
    private ?Payment $payment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedicalActRequested(): ?MedicalActRequested
    {
        return $this->medicalActRequested;
    }

    public function setMedicalActRequested(?MedicalActRequested $medicalActRequested): self
    {
        $this->medicalActRequested = $medicalActRequested;

        return $this;
    }

    public function getPatientName(): ?string
    {
        return !is_null($this->medicalActRequested) ? $this->medicalActRequested->getPatientName() : null;
    }

    public function getRequestedDetail(): ?array
    {
        if (is_null($this->medicalAct)) {
            return null;
        }
        return [
            'medicalActCode' => $this->medicalAct->getCode(),
            'medicalActName' => $this->getMedicalActName(),
            'medicalActCost' => $this->getUnitPrice()
        ];
    }

    public function getMedicalAct(): ?MedicalAct
    {
        return $this->medicalAct;
    }

    public function setMedicalAct(?MedicalAct $medicalAct): self
    {
        $this->medicalAct = $medicalAct;

        return $this;
    }

    public function getMedicalActName(): ?string
    {
        return !is_null($this->medicalAct) ? $this->medicalAct->getWording() : null;
    }

    public function getMedicalActCost(): ?MedicalActCost
    {
        return $this->medicalActCost;
    }

    public function setMedicalActCost(?MedicalActCost $medicalActCost): self
    {
        $this->medicalActCost = $medicalActCost;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return !is_null($this->medicalActCost) ? $this->medicalActCost->getUnitPrice() : null;
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
