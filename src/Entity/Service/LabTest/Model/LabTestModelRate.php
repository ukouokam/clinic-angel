<?php

namespace App\Entity\Service\LabTest\Model;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Service\LabTest\Model\LabTestModelRateRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LabTestModelRateRepository::class)
 * @ORM\Table(name="lab_test_model_rates")
 * @ApiResource()
 */
class LabTestModelRate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=LabTestModel::class, inversedBy="labTestModelRates")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le modèle d'analyse est requis")
     */
    private ?LabTestModel $labTestModel = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="labTestModelRates")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Le pourcentage ne doit pas être vide")
     * @Assert\Type(type="float", message="La valeur du pourcentage doit être numérique")
     * @Assert\Positive(message="La valeur du pourcentage doit être positive")
     */
    private ?float $rate = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isActual = true;

    /**
     * @ORM\OneToMany(targetEntity=LabTestModelRequested::class, mappedBy="labTestModelRate")
     * @var LabTestModelRequested[]|ArrayCollection
     */
    private $labTestModelsRequested;

    public function __construct()
    {
        $this->labTestModelsRequested = new ArrayCollection();
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

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getIsActual(): ?bool
    {
        return $this->isActual;
    }

    public function setIsActual(bool $isActual): self
    {
        $this->isActual = $isActual;

        return $this;
    }

    /**
     * @return Collection|LabTestModelRequested[]
     */
    public function getLabTestModelsRequested(): Collection
    {
        return $this->labTestModelsRequested;
    }

    public function addLabTestModelsRequested(LabTestModelRequested $labTestModelsRequested): self
    {
        if (!$this->labTestModelsRequested->contains($labTestModelsRequested)) {
            $this->labTestModelsRequested[] = $labTestModelsRequested;
            $labTestModelsRequested->setLabTestModelRate($this);
        }

        return $this;
    }

    public function removeLabTestModelsRequested(LabTestModelRequested $labTestModelsRequested): self
    {
        if ($this->labTestModelsRequested->contains($labTestModelsRequested)) {
            $this->labTestModelsRequested->removeElement($labTestModelsRequested);
            // set the owning side to null (unless already changed)
            if ($labTestModelsRequested->getLabTestModelRate() === $this) {
                $labTestModelsRequested->setLabTestModelRate(null);
            }
        }

        return $this;
    }
}
