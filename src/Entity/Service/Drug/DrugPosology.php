<?php

namespace App\Entity\Service\Drug;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Service\Drug\DrugPosologyRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DrugPosologyRepository::class)
 * @ORM\Table(name="drug_posologies")
 * @ApiResource()
 */
class DrugPosology
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Drug::class, inversedBy="drugPosologies")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le Médicament est requis")
     */
    private ?Drug $drug = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le libelé de la posologie est requis")
     * @Assert\Length(
     *     min="3", minMessage="Le libelé doit avoir 03 caractère au minimum",
     *     max="100", maxMessage="Le libelé ne doit pas avoir plus de 100 caractères"
     * )
     */
    private ?string $label = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'explication de la posologie ne doit pas être vide")
     * @Assert\Length(
     *     min="5", minMessage="L'explication doit tenir au moins sur 05 caractères",
     *     max="255", maxMessage="L'explication ne peut pas dépasser 255 caractères"
     * )
     */
    private ?string $explanation = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="drugPosologies")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDrug(): ?Drug
    {
        return $this->drug;
    }

    public function setDrug(?Drug $drug): self
    {
        $this->drug = $drug;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getExplanation(): ?string
    {
        return $this->explanation;
    }

    public function setExplanation(string $explanation): self
    {
        $this->explanation = $explanation;

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

    /**
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return !is_null($this->createdBy) ? $this->createdBy->getFullName() ?? $this->createdBy->getUsername() : null;
    }
}
