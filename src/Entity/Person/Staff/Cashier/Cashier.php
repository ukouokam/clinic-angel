<?php

namespace App\Entity\Person\Staff\Cashier;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\Person;
use App\Entity\Person\User\User;
use App\Repository\Person\Staff\Cashier\CashierRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CashierRepository::class)
 * @ORM\Table(name="cashiers")
 * @ApiResource()
 */
class Cashier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"cashiers_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\OneToOne(targetEntity=Person::class, inversedBy="cashier", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="La personne est requise")
     */
    private ?Person $person = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="cashiers")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Vous devez être connecté pour créer un caissier")
     */
    private ?User $createdBy = null;

    /**
     * @Groups({"cashiers_read"})
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création doit être au format valide (YYYY-MM-DD H:i:s)")
     */
    private ?DateTimeInterface $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Groups("cashiers_read")
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return !is_null($this->person) ? $this->person->getFullName() : null;
    }

    /**
     * @Groups({"cashiers_read"})
     * @return DateTimeInterface|null
     */
    public function getBornAt(): ?DateTimeInterface
    {
        return !is_null($this->person) ? $this->person->getBornAt() : null;
    }

    /**
     * @Groups({"cashiers_read"})
     * @return string|null
     */
    public function getPostal(): ?string
    {
        return !is_null($this->person) ? $this->person->getPostal() : null;
    }

    /**
     * @Groups({"cashiers_read"})
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return !is_null($this->person) ? $this->person->getPhoneNumber() : null;
    }

    /**
     * @Groups({"cashiers_read"})
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return !is_null($this->person) ? $this->person->getSlug() : null;
    }

    /**
     * @Groups({"cashiers_read"})
     * @return string|null
     */
    public function getGender(): ?string
    {
        return !is_null($this->person) ? $this->person->getGender() : null;
    }

    /**
     * @Groups({"cashiers_read"})
     * @return int|string|null
     */
    public function getIdentityNumber()
    {
        return !is_null($this->person) ? $this->person->getIdentityNumber() : null;
    }

    /**
     * @Groups({"cashiers_read"})
     * @return bool|null
     */
    public function isValidIdentityCard(): ?bool
    {
        return !is_null($this->person) ? $this->person->isValidIdentityCard() : null;
    }

    /**
     * @Groups({"cashiers_read"})
     * @return string|null
     */
    public function getAge(): ?string
    {
        return !is_null($this->person) ? $this->person->getAge() : null;
    }

    /**
     * @Groups({"cashiers_read"})
     * @return string|null
     */
    public function getBirthPlace(): ?string
    {
        return !is_null($this->person) ? $this->person->getBirthPlace() : null;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(Person $person): self
    {
        $this->person = $person;

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
     * @Groups({"cashiers_read"})
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
            $this->createdAt = !is_string($createdAt) ? new DateTime($createdAt) : $createdAt;
        } catch (Exception $exception) {
            $this->createdAt = null;
        }

        return $this;
    }
}
