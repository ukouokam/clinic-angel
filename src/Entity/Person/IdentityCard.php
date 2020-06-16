<?php

namespace App\Entity\Person;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Person\IdentityCardRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=IdentityCardRepository::class)
 * @ORM\Table(name="identity_cards")
 * @ApiResource(
 *     denormalizationContext={"disable_type_enforcement"="true"}
 * )
 */
class IdentityCard
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le numéro de téléphone est requis")
     * @Assert\Type(type="int", message="Le n° de carte doit être un nombre entier")
     * @Assert\Positive(message="Cette valeur doit être positive")
     */
    private ?int $identityNumber = null;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="cardNumber")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Sélectionnez la personne concernée")
     */
    private ?Person $person = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max="255", maxMessage="Le commentaire ne peut pas dépasser 255 caractères")
     */
    private ?string $comment = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de délivrance n'est pas au format valide (YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $issueAt = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date d'expiration n'est pas au format valide (YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $expirationAt = null;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private ?bool $actualCard = true;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création n'est pas au format valide (YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="identityCards")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Il faut être connecter pour réaliser cette opération")
     */
    private ?User $createdBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentityNumber(): ?int
    {
        return $this->identityNumber;
    }

    /**
     * @param int|string|null $identityNumber
     * @return $this
     */
    public function setIdentityNumber(int $identityNumber): self
    {
        $this->identityNumber = is_string($identityNumber) ? (int)$identityNumber : $identityNumber;

        return $this;
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getIssueAt(): ?DateTimeInterface
    {
        return $this->issueAt;
    }

    /**
     * @param DateTimeInterface|string|null $issueAt
     * @return $this
     */
    public function setIssueAt($issueAt): self
    {
        try {
            $this->issueAt = is_string($issueAt) ? new DateTime($issueAt) : $issueAt;
        } catch (Exception $exception) {
            $this->issueAt = null;
        }

        return $this;
    }

    public function getExpirationAt(): ?DateTimeInterface
    {
        return $this->expirationAt;
    }

    /**
     * @param DateTimeInterface|string|null $expirationAt
     * @return $this
     */
    public function setExpirationAt($expirationAt): self
    {
        try {
            $this->expirationAt = is_string($expirationAt) ? new DateTime($expirationAt) : $expirationAt;
        } catch (Exception $exception) {
            $this->expirationAt = null;
        }

        return $this;
    }

    public function getActualCard(): ?bool
    {
        return $this->actualCard;
    }

    /**
     * @param bool|int|null $actualCard
     * @return $this
     */
    public function setActualCard($actualCard): self
    {
        $this->actualCard = is_int($actualCard) ? (bool)$actualCard : $actualCard;

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
}
