<?php

namespace App\Entity\Service\LabTest;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Entity\Service\LabTest\Model\LabTestModelRequestedDetail;
use App\Repository\Service\LabTest\LabTestCostRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LabTestCostRepository::class)
 * @ORM\Table(
 *     name="lab_tests_costs",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={"lab_test_id", "is_actual", "created_at"})}
 * )
 * @ApiResource()
 */
class LabTestCost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=LabTest::class, inversedBy="labTestsCost")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'analyse est requise")
     */
    private ?LabTest $labTest = null;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Le coût de l'analyse ne peut être vide")
     * @Assert\Type(type="float", message="La valeur fournie doit être numérique")
     * @Assert\Positive(message="Cette valeur doit être positive")
     */
    private ?float $unitPrice = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-DD-MM H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="labTestCosts")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isActual = true;

    /**
     * @ORM\OneToMany(targetEntity=LabTestModelRequestedDetail::class, mappedBy="labTestCost")
     * @var LabTestModelRequestedDetail[]|ArrayCollection
     */
    private $labTestModelRequestedDetails;

    /**
     * @ORM\OneToMany(targetEntity=LabTestRequestedDetail::class, mappedBy="labTestCost")
     * @var LabTestRequestedDetail[]|ArrayCollection
     */
    private $labTestRequestedDetails;

    public function __construct()
    {
        $this->labTestModelRequestedDetails = new ArrayCollection();
        $this->labTestRequestedDetails = new ArrayCollection();
    }

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

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface|string|null $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt): self
    {
        try {
            $this->createdAt = is_string($createdAt) ? new DateTime($createdAt) : $createdAt;
        } catch (Exception $exception) {
            $this->createdAt = null;
        }


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

    public function getAuthor(): ?string
    {
        return !is_null($this->createdBy) ? $this->createdBy->getFullName() ?? $this->createdBy->getUsername() : null;
    }

    public function getIsActual(): ?bool
    {
        return $this->isActual;
    }

    /**
     * @param bool|int|null $isActual
     * @return $this
     */
    public function setIsActual($isActual): self
    {
        $this->isActual = is_int($isActual) ? (bool)$isActual : $isActual;

        return $this;
    }

    /**
     * @return Collection|LabTestModelRequestedDetail[]
     */
    public function getLabTestModelRequestedDetails(): Collection
    {
        return $this->labTestModelRequestedDetails;
    }

    public function addLabTestModelRequestedDetail(LabTestModelRequestedDetail $labTestModelRequestedDetail): self
    {
        if (!$this->labTestModelRequestedDetails->contains($labTestModelRequestedDetail)) {
            $this->labTestModelRequestedDetails[] = $labTestModelRequestedDetail;
            $labTestModelRequestedDetail->setLabTestCost($this);
        }

        return $this;
    }

    public function removeLabTestModelRequestedDetail(LabTestModelRequestedDetail $labTestModelRequestedDetail): self
    {
        if ($this->labTestModelRequestedDetails->contains($labTestModelRequestedDetail)) {
            $this->labTestModelRequestedDetails->removeElement($labTestModelRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($labTestModelRequestedDetail->getLabTestCost() === $this) {
                $labTestModelRequestedDetail->setLabTestCost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LabTestRequestedDetail[]
     */
    public function getLabTestRequestedDetails(): Collection
    {
        return $this->labTestRequestedDetails;
    }

    public function addLabTestRequestedDetail(LabTestRequestedDetail $labTestRequestedDetail): self
    {
        if (!$this->labTestRequestedDetails->contains($labTestRequestedDetail)) {
            $this->labTestRequestedDetails[] = $labTestRequestedDetail;
            $labTestRequestedDetail->setLabTestCost($this);
        }

        return $this;
    }

    public function removeLabTestRequestedDetail(LabTestRequestedDetail $labTestRequestedDetail): self
    {
        if ($this->labTestRequestedDetails->contains($labTestRequestedDetail)) {
            $this->labTestRequestedDetails->removeElement($labTestRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($labTestRequestedDetail->getLabTestCost() === $this) {
                $labTestRequestedDetail->setLabTestCost(null);
            }
        }

        return $this;
    }
}
