<?php

namespace App\Entity\Service\LabTest;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Entity\Service\LabTest\Model\LabTestModelDetail;
use App\Repository\Service\LabTest\LabTestRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LabTestRepository::class)
 * @ORM\Table(name="lab_tests")
 * @ApiResource(
 *     normalizationContext={
 *     "groups"={"lab_tests_read"}
 *     }
 * )
 */
class LabTest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"lab_tests_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"lab_tests_read"})
     */
    private ?string $code = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"lab_tests_read"})
     * @Assert\NotBlank(message="Le nom de l'analyse est requis")
     * @Assert\Length(
     *     min="3", minMessage="Le nom de l'analyse doit avoir au minimum 03 caractères",
     *     max="100", maxMessage="Le nom de l'analyse ne peut pas dépasser 100 caractères"
     * )
     */
    private ?string $wording = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="labTests")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"lab_tests_read"})
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=LabTestCategory::class, inversedBy="labTests")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="La catégorie de l'analyse est requise")
     */
    private ?LabTestCategory $category = null;

    /**
     * @ORM\OneToMany(targetEntity=LabTestRange::class, mappedBy="labtest")
     * @var LabTestRange[]|ArrayCollection
     */
    private $labTestRanges;

    /**
     * @ORM\OneToMany(targetEntity=LabTestCost::class, mappedBy="labTest")
     * @var LabTestCost[]|ArrayCollection
     */
    private $labTestsCost;

    /**
     * @ORM\OneToMany(targetEntity=LabTestModelDetail::class, mappedBy="labTest")
     * @var LabTestModelDetail[]|ArrayCollection
     */
    private $labTestModelDetails;

    /**
     * @ORM\OneToMany(targetEntity=LabTestRequestedDetail::class, mappedBy="labTest")
     * @var LabTestRequestedDetail[]|ArrayCollection
     */
    private $labTestRequestedDetails;

    public function __construct()
    {
        $this->labTestRanges = new ArrayCollection();
        $this->labTestsCost = new ArrayCollection();
        $this->labTestModelDetails = new ArrayCollection();
        $this->labTestRequestedDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getWording(): ?string
    {
        return $this->wording;
    }

    public function setWording(string $wording): self
    {
        $this->wording = $wording;

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

    /**
     * @Groups({"lab_tests_read"})
     * @return string|null
     */
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
        } catch (\Exception $exception) {
            $this->createdAt = null;
        }

        return $this;
    }

    public function getCategory(): ?LabTestCategory
    {
        return $this->category;
    }

    public function setCategory(?LabTestCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @Groups({"lab_tests_read"})
     * @return string|null
     */
    public function getCategoryName(): ?string
    {
        return !is_null($this->category) ? $this->category->getWording() : null;
    }

    /**
     * @return Collection|LabTestRange[]
     */
    public function getLabTestRanges(): Collection
    {
        return $this->labTestRanges;
    }

    public function addLabTestRange(LabTestRange $labTestRange): self
    {
        if (!$this->labTestRanges->contains($labTestRange)) {
            $this->labTestRanges[] = $labTestRange;
            $labTestRange->setLabTest($this);
        }

        return $this;
    }

    public function removeLabTestRange(LabTestRange $labTestRange): self
    {
        if ($this->labTestRanges->contains($labTestRange)) {
            $this->labTestRanges->removeElement($labTestRange);
            // set the owning side to null (unless already changed)
            if ($labTestRange->getLabTest() === $this) {
                $labTestRange->setLabTest(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"lab_tests_read"})
     * @return float|null
     */
    public function getActualLabTestCost(): ?float
    {
        /** @var LabTestCost[] $costs */
        $costs = $this->labTestsCost->toArray();
        if (count($costs) === 0) {
            return null;
        }
        $costs = array_filter($costs, static function($key) use ($costs) {
            return ($costs[$key]->getIsActual() === true);
        }, ARRAY_FILTER_USE_KEY);
        if ($costs instanceof LabTestCost) {
            return $costs->getUnitPrice();
        }
        if (is_array($costs)) {
            if (count($costs) === 0) {
                return null;
            }
            return $costs[array_key_first($costs)]->getUnitPrice();
        }
        return null;
    }

    /**
     * @return Collection|LabTestCost[]
     */
    public function getLabTestsCost(): Collection
    {
        return $this->labTestsCost;
    }

    public function addLabTestsCost(LabTestCost $labTestsCost): self
    {
        if (!$this->labTestsCost->contains($labTestsCost)) {
            $this->labTestsCost[] = $labTestsCost;
            $labTestsCost->setLabTest($this);
        }

        return $this;
    }

    public function removeLabTestsCost(LabTestCost $labTestsCost): self
    {
        if ($this->labTestsCost->contains($labTestsCost)) {
            $this->labTestsCost->removeElement($labTestsCost);
            // set the owning side to null (unless already changed)
            if ($labTestsCost->getLabTest() === $this) {
                $labTestsCost->setLabTest(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LabTestModelDetail[]
     */
    public function getLabTestModelDetails(): Collection
    {
        return $this->labTestModelDetails;
    }

    public function addLabTestModelDetail(LabTestModelDetail $labTestModelDetail): self
    {
        if (!$this->labTestModelDetails->contains($labTestModelDetail)) {
            $this->labTestModelDetails[] = $labTestModelDetail;
            $labTestModelDetail->setLabTest($this);
        }

        return $this;
    }

    public function removeLabTestModelDetail(LabTestModelDetail $labTestModelDetail): self
    {
        if ($this->labTestModelDetails->contains($labTestModelDetail)) {
            $this->labTestModelDetails->removeElement($labTestModelDetail);
            // set the owning side to null (unless already changed)
            if ($labTestModelDetail->getLabTest() === $this) {
                $labTestModelDetail->setLabTest(null);
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
            $labTestRequestedDetail->setLabTest($this);
        }

        return $this;
    }

    public function removeLabTestRequestedDetail(LabTestRequestedDetail $labTestRequestedDetail): self
    {
        if ($this->labTestRequestedDetails->contains($labTestRequestedDetail)) {
            $this->labTestRequestedDetails->removeElement($labTestRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($labTestRequestedDetail->getLabTest() === $this) {
                $labTestRequestedDetail->setLabTest(null);
            }
        }

        return $this;
    }
}
