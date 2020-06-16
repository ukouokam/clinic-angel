<?php

namespace App\Entity\Service\LabTest;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Payment\Payment;
use App\Repository\Service\LabTest\LabTestRequestedDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LabTestRequestedDetailRepository::class)
 * @ORM\Table(name="lab_test_requested_details")
 * @ApiResource()
 */
class LabTestRequestedDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=LabTestRequested::class, inversedBy="labTestRequestedDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="La demande d'analyse est requise")
     */
    private ?LabTestRequested $labTestRequested = null;

    /**
     * @ORM\ManyToOne(targetEntity=LabTest::class, inversedBy="labTestRequestedDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'analyse en question n'a pas été fournie")
     */
    private ?LabTest $labTest = null;

    /**
     * @ORM\ManyToOne(targetEntity=LabTestCost::class, inversedBy="labTestRequestedDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le coût de l'analyse est manquant")
     */
    private ?LabTestCost $labTestCost = null;

    /**
     * @ORM\ManyToOne(targetEntity=Payment::class, inversedBy="labTestRequestedDetails")
     */
    private ?Payment $payment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabTest(): ?LabTest
    {
        return $this->labTest;
    }

    public function setLabTest(?LabTest $labTest): self
    {
        $this->labTest = $labTest;

        return $this;
    }

    public function getRequestDetail(): ?array
    {
        if (is_null($this->labTest)) {
            return null;
        }
        return [
            'labTestCode' => $this->labTest->getCode(),
            'labTestName' => $this->labTest->getWording(),
            'labTestCost' => $this->getUnitPrice()
        ];
    }

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

    public function getLabTestRequested(): ?LabTestRequested
    {
        return $this->labTestRequested;
    }

    public function setLabTestRequested(?LabTestRequested $labTestRequested): self
    {
        $this->labTestRequested = $labTestRequested;

        return $this;
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
