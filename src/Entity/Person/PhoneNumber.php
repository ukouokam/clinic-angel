<?php

namespace App\Entity\Person;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Person\PhoneNumberRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PhoneNumberRepository::class)
 * @ORM\Table(name="phone_numbers")
 * @ApiResource()
 */
class PhoneNumber
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="phoneNumbers")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Une personne est requise")
     */
    private ?Person $person = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le numéro de téléphone est requis")
     * @Assert\Regex(pattern="/^[0-9]*$/", message="Le numéro donné n'est pas au format valide")
     */
    private ?string $value = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création est requise")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="phoneNumbers")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max="255", maxMessage="Le commentaire ne peut pas dépasser 255 caractères")
     */
    private ?string $comment = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isUse = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

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
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        try {
            $this->createdAt = is_string($createdAt) ? new DateTime($createdAt) : $createdAt;
        } catch (\Exception $exception) {
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getIsUse(): ?bool
    {
        return $this->isUse;
    }

    /**
     * @param bool|int|null $isUse
     * @return $this
     */
    public function setIsUse($isUse): self
    {
        $this->isUse = is_int($isUse) ? (bool)$isUse : $isUse;

        return $this;
    }


}
