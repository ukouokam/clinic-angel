<?php

namespace App\Entity\Person;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Person\EmailRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EmailRepository::class)
 * @ORM\Table(name="emails")
 * @UniqueEntity(fields={"address"}, message="Cette adresse est déjà attribuée à un autre utilisateur")
 * @ApiResource(
 *     denormalizationContext={"disable_type_enforcement"="true"}
 * )
 */
class Email
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="emails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="La personne ayant l'email est requise")
     */
    private ?Person $person = null;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="L'adresse email est obligatoire")
     * @Assert\Email(message="Cette adresse mail n'est pas valide")
     * @Assert\Length(max="255", maxMessage="L'adresse de ne peut pas dépasser 255 caractères")
     */
    private ?string $address = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Cette date n'est pas valide (YYYY-MM-DD H:i:s)")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="emails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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
