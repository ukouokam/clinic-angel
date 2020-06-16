<?php

namespace App\Entity\Service\LabTest;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Service\LabTest\LabTestRangeRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LabTestRangeRepository::class)
 * @ORM\Table(name="lab_tests_ranges")
 * @ApiResource()
 */
class LabTestRange
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=LabTest::class, inversedBy="labTestRanges")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'analyse est requise")
     */
    private ?LabTest $labTest = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Le libelé de la valeur normale ne peut être vide")
     * @Assert\Length(
     *     min="3", minMessage="Le libelé doit avoir au moins 03 caractères",
     *     max="100", maxMessage="Le libelé ne peut pas dépasser 100 caractères"
     * )
     */
    private ?string $wording = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La valeur minimale de la valeur normale ne peut être vide")
     * @Assert\Length(
     *     min="3", minMessage="La valeur minimale doit avoir au moins 03 caractères",
     *     max="100", maxMessage="La valeur minimale ne peut pas dépasser 100 caractères"
     * )
     */
    private ?string $minValue = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La valeur maximale de la valeur normale ne peut être vide")
     * @Assert\Length(
     *     min="3", minMessage="La valeur maximale doit avoir au moins 03 caractères",
     *     max="100", maxMessage="La valeur maximale ne peut pas dépasser 100 caractères"
     * )
     */
    private ?string $maxValue = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @var string|null
     * @Assert\NotBlank(message="L'unite ne peut être vide")
     * @Assert\Length(
     *     min="3", minMessage="L'unité doit avoir au moins 03 caractères",
     *     max="100", maxMessage="L'unité ne peut pas dépasser 100 caractères"
     * )
     */
    private ?string $unit = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="labTestRanges")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

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

    public function getWording(): ?string
    {
        return $this->wording;
    }

    public function setWording(string $wording): self
    {
        $this->wording = $wording;

        return $this;
    }

    public function getMinValue(): ?string
    {
        return $this->minValue;
    }

    public function setMinValue(string $minValue): self
    {
        $this->minValue = $minValue;

        return $this;
    }

    public function getMaxValue(): ?string
    {
        return $this->maxValue;
    }

    public function setMaxValue(string $maxValue): self
    {
        $this->maxValue = $maxValue;

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

    /**
     * @return string|null
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }

    /**
     * @param string|null $unit
     * @return LabTestRange
     */
    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;
        return $this;
    }
}
