<?php

namespace App\Entity\Service\LabTest\Model;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Entity\Service\LabTest\LabTest;
use App\Repository\Service\LabTest\Model\LabTestModelDetailRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LabTestModelDetailRepository::class)
 * @ORM\Table(
 *     name="lab_test_model_details",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={"lab_test_id", "lab_test_model_id"})}
 * )
 * @ApiResource()
 */
class LabTestModelDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=LabTestModel::class, inversedBy="labTestModelDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le modèle d'analyse est requis")
     */
    private ?LabTestModel $labTestModel = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="labTestModelDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=LabTest::class, inversedBy="labTestModelDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'analyse est requise")
     */
    private ?LabTest $labTest = null;

    /**
     * @ORM\OneToMany(targetEntity=LabTestModelRequestedDetail::class, mappedBy="labTestModelDetail")
     * @var LabTestModelRequestedDetail[]|ArrayCollection
     */
    private $labTestModelRequestedDetails;

    public function __construct()
    {
        $this->labTestModelRequestedDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabTestModel(): ?LabTestModel
    {
        return $this->labTestModel;
    }

    public function setLabTestModel(?LabTestModel $labTestModel): self
    {
        $this->labTestModel = $labTestModel;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLabTestModelName(): ?string
    {
        return !is_null($this->labTestModel) ? $this->labTestModel->getWording() : null;
    }

    /**
     * @return string|null
     */
    public function getLabTestModelCode(): ?string
    {
        return !is_null($this->labTestModel) ? $this->labTestModel->getCode() : null;
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

    public function getLabTest(): ?LabTest
    {
        return $this->labTest;
    }

    public function setLabTest(?LabTest $labTest): self
    {
        $this->labTest = $labTest;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLabTestName(): ?string
    {
        return !is_null($this->labTest) ? $this->labTest->getWording() : null;
    }

    public function getLabTestCode(): ?string
    {
        return !is_null($this->labTest) ? $this->labTest->getCode() : null;
    }

    public function getActualLabTestCost(): ?float
    {
        return !is_null($this->labTest) ? $this->labTest->getActualLabTestCost() : null;
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
            $labTestModelRequestedDetail->setLabTestModelDetail($this);
        }

        return $this;
    }

    public function removeLabTestModelRequestedDetail(LabTestModelRequestedDetail $labTestModelRequestedDetail): self
    {
        if ($this->labTestModelRequestedDetails->contains($labTestModelRequestedDetail)) {
            $this->labTestModelRequestedDetails->removeElement($labTestModelRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($labTestModelRequestedDetail->getLabTestModelDetail() === $this) {
                $labTestModelRequestedDetail->setLabTestModelDetail(null);
            }
        }

        return $this;
    }
}
