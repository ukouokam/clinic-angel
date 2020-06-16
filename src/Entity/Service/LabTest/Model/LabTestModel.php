<?php

namespace App\Entity\Service\LabTest\Model;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Service\LabTest\Model\LabTestModelRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LabTestModelRepository::class)
 * @ORM\Table(name="lab_test_models")
 * @ApiResource(
 *     normalizationContext={
 *     "groups" = {"lab_test_models_read"}
 *     }
 * )
 */
class LabTestModel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"lab_test_models_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="labTestModels")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"lab_test_models_read"})
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"lab_test_models_read"})
     * @Assert\Length(
     *     max="25", maxMessage="Le code ne peut avoir plus de 25 caractères"
     * )
     */
    private ?string $code = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"lab_test_models_read"})
     * @Assert\NotBlank(message="Le libellé du modèle d'analyse est manquant")
     * @Assert\Length(
     *     min="3", minMessage="Le libellé doit avoir au moins 03 caractères",
     *     max="100", maxMessage="Le libellé ne peut pas dépasser 100 caractères"
     * )
     */
    private ?string $wording = null;

    /**
     * @ORM\OneToMany(targetEntity=LabTestModelDetail::class, mappedBy="labTestModel")
     * @var LabTestModelDetail[]|ArrayCollection
     */
    private $labTestModelDetails;

    /**
     * @ORM\OneToMany(targetEntity=LabTestModelRate::class, mappedBy="labTestModel")
     * @var LabTestModelRate[]|ArrayCollection
     */
    private $labTestModelRates;

    public function __construct()
    {
        $this->labTestModelDetails = new ArrayCollection();
        $this->labTestModelRates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @Groups({"lab_test_models_read"})
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
        } catch (Exception $exception) {
            $this->createdAt = null;
        }

        return $this;
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
            $labTestModelDetail->setLabTestModel($this);
        }

        return $this;
    }

    public function removeLabTestModelDetail(LabTestModelDetail $labTestModelDetail): self
    {
        if ($this->labTestModelDetails->contains($labTestModelDetail)) {
            $this->labTestModelDetails->removeElement($labTestModelDetail);
            // set the owning side to null (unless already changed)
            if ($labTestModelDetail->getLabTestModel() === $this) {
                $labTestModelDetail->setLabTestModel(null);
            }
        }

        return $this;
    }

    /**
     * @return array|null
     * @Groups({"lab_test_models_read"})
     */
    public function getLabTestModelDetail(): ?array
    {
        if ($this->labTestModelDetails->count() === 0) {
            return null;
        }
       return array_map(static function(LabTestModelDetail $labTestModelDetail) {
            return [
                'labTestCode' => $labTestModelDetail->getLabTestCode(),
                'labTestName' => $labTestModelDetail->getLabTestName(),
                'labTestCost' => $labTestModelDetail->getActualLabTestCost()
            ];
        }, $this->labTestModelDetails->toArray());
    }

    /**
     * @Groups({"lab_test_models_read"})
     * @return float|null
     */
    public function getLabTestModelCost(): ?float
    {
        return array_reduce($this->labTestModelDetails->toArray(), static function(?float $initial, LabTestModelDetail $labTestModelDetail) {
            if (is_null($initial)) {
                return $labTestModelDetail->getActualLabTestCost();
            }
            return $labTestModelDetail->getActualLabTestCost() + $initial;
        }, null);
    }

    /**
     * @return Collection|LabTestModelRate[]
     */
    public function getLabTestModelRates(): Collection
    {
        return $this->labTestModelRates;
    }

    public function addLabTestModelRate(LabTestModelRate $labTestModelRate): self
    {
        if (!$this->labTestModelRates->contains($labTestModelRate)) {
            $this->labTestModelRates[] = $labTestModelRate;
            $labTestModelRate->setLabTestModel($this);
        }

        return $this;
    }

    public function removeLabTestModelRate(LabTestModelRate $labTestModelRate): self
    {
        if ($this->labTestModelRates->contains($labTestModelRate)) {
            $this->labTestModelRates->removeElement($labTestModelRate);
            // set the owning side to null (unless already changed)
            if ($labTestModelRate->getLabTestModel() === $this) {
                $labTestModelRate->setLabTestModel(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"lab_test_models_read"})
     * @return float|null
     */
    public function getActualRate(): ?float
    {
        /** @var LabTestModelRate[] $rates */
        $rates = $this->labTestModelRates->toArray();
        $rates = array_filter($rates, static function($key) use ($rates) {
            return ($rates[$key]->getIsActual() === true);
        }, ARRAY_FILTER_USE_KEY);
        if ($rates instanceof LabTestModelRate) {
            return $rates->getRate();
        }
        if (is_array($rates)) {
            return $rates[array_key_first($rates)]->getRate();
        }
        return null;
    }
}
