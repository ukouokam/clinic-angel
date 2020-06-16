<?php

namespace App\Entity\Service\LabTest\Model;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Payment\Payment;
use App\Entity\Service\LabTest\LabTestCost;
use App\Repository\Service\LabTest\Model\LabTestModelRequestedDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LabTestModelRequestedDetailRepository::class)
 * @ORM\Table(name="lab_test_models_requested_details")
 * @ApiResource()
 */
class LabTestModelRequestedDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=LabTestModelRequested::class, inversedBy="labTestModelRequestedDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="La requête du modèle d'analyse est requise")
     */
    private ?LabTestModelRequested $labTestModelRequested = null;

    /**
     * @ORM\ManyToOne(targetEntity=LabTestModelDetail::class, inversedBy="labTestModelRequestedDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le détail du modèle d'analyse est attendu")
     */
    private ?LabTestModelDetail $labTestModelDetail = null;

    /**
     * @ORM\ManyToOne(targetEntity=LabTestCost::class, inversedBy="labTestModelRequestedDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le coût de l'analyse est attendu")
     */
    private ?LabTestCost $labTestCost = null;

    /**
     * @ORM\ManyToOne(targetEntity=Payment::class, inversedBy="labTestModelRequestedDetails")
     */
    private ?Payment $payment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabTestModelRequested(): ?LabTestModelRequested
    {
        return $this->labTestModelRequested;
    }

    public function setLabTestModelRequested(?LabTestModelRequested $labTestModelRequested): self
    {
        $this->labTestModelRequested = $labTestModelRequested;

        return $this;
    }

    public function getLabTestModelDetail(): ?LabTestModelDetail
    {
        return $this->labTestModelDetail;
    }

    public function setLabTestModelDetail(?LabTestModelDetail $labTestModelDetail): self
    {
        $this->labTestModelDetail = $labTestModelDetail;

        return $this;
    }

    //******************************* Methods for Payment *********************************//
    /**
     * For a payment
     * @return string|null
     */
    public function getPatientName(): ?string
    {
        return $this->labTestModelRequested->getPatientName();
    }

    public function getRequestDetail(): ?array
    {
        if (is_null($this->labTestModelDetail) || is_null($this->labTestCost)) {
            return null;
        }
        return [
            'labTestModelCode' => $this->getLabTestModelCode(),
            'labTestModelName' => $this->getLabTestModelName(),
            'labTestModelRate' => $this->getRate(),
            'labTest' => [
                'labTestCode' => $this->labTestModelDetail->getLabTestCode(),
                'labTestName' => $this->labTestModelDetail->getLabTestName(),
                'labTestCost' => $this->getUnitPrice(),
                'labTestCostWithRate' => $this->getUnitPriceWithRate()
            ]
        ];
    }
    //************************* End methods for Payments (Invoices) **************************//

    public function getLabTestCost(): ?LabTestCost
    {
        return $this->labTestCost;
    }

    public function setLabTestCost(?LabTestCost $labTestCost): self
    {
        $this->labTestCost = $labTestCost;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return !is_null($this->labTestCost) ? $this->labTestCost->getUnitPrice() : null;
    }

    public function getUnitPriceWithRate(): ?float
    {
        $rate = $this->getRate();
        if (is_null($this->labTestCost) || is_null($rate)) {
            return null;
        }
        $unitPrice = $this->labTestCost->getUnitPrice();
        /** @var float|null $reduction */
        $reduction = ($rate/100) * $unitPrice;
        //$this->labTestCost->setUnitPrice($unitPrice - $reduction);
        return ($unitPrice - $reduction);
    }

    /**
     * @return float|null
     */
    public function getRate(): ?float
    {
        return !is_null($this->labTestModelRequested) ? $this->labTestModelRequested->getRate() : null;
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

    /**
     * @return string|null
     */
    public function getLabTestModelCode(): ?string
    {
        return !is_null($this->labTestModelDetail) ? $this->labTestModelDetail->getLabTestCode() : null;
    }

    /**
     * @return string|null
     */
    public function getLabTestModelName(): ?string
    {
        return !is_null($this->labTestModelDetail) ? $this->labTestModelDetail->getLabTestModelName() : null;
    }
}
