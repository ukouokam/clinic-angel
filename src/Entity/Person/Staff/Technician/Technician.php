<?php

namespace App\Entity\Person\Staff\Technician;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\Person;
use App\Entity\Person\User\User;
use App\Repository\Person\Staff\Technician\TechnicianRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TechnicianRepository::class)
 * @ApiResource(
 *     normalizationContext={
 *     "groups" = {"technicians_read"}
 *     }
 * )
 * @ORM\Table(
 *     name="technicians",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={"person_id"})
 * })
 */
class Technician
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"technicians_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\OneToOne(targetEntity=Person::class, inversedBy="technician", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"technicians_read"})
     * @Assert\NotBlank(message="La personne est requise")
     */
    private ?Person $person = null;

    /**
     * @ORM\ManyToOne(targetEntity=TechnicianCategory::class, inversedBy="technicians")
     */
    private ?TechnicianCategory $category = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"technicians_read"})
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $saveAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="techniciansCreated")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"technicians_read"})
     * @Assert\NotBlank(message="Vous devez être connecté pour continuer")
     */
    private ?User $createdBy = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Groups("technicians_read")
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return !is_null($this->person) ? $this->person->getFullName() : null;
    }

    /**
     * @Groups({"technicians_read"})
     * @return DateTimeInterface|null
     */
    public function getBornAt(): ?DateTimeInterface
    {
        return !is_null($this->person) ? $this->person->getBornAt() : null;
    }

    /**
     * @Groups({"technicians_read"})
     * @return string|null
     */
    public function getPostal(): ?string
    {
        return !is_null($this->person) ? $this->person->getPostal() : null;
    }

    /**
     * @Groups({"technicians_read"})
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return !is_null($this->person) ? $this->person->getPhoneNumber() : null;
    }

    /**
     * @Groups({"technicians_read"})
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return !is_null($this->person) ? $this->person->getSlug() : null;
    }

    /**
     * @Groups({"technicians_read"})
     * @return string|null
     */
    public function getGender(): ?string
    {
        return !is_null($this->person) ? $this->person->getGender() : null;
    }

    /**
     * @Groups({"technicians_read"})
     * @return int|string|null
     */
    public function getIdentityNumber()
    {
        return !is_null($this->person) ? $this->person->getIdentityNumber() : null;
    }

    /**
     * @Groups({"technicians_read"})
     * @return bool|null
     */
    public function isValidIdentityCard(): ?bool
    {
        return !is_null($this->person) ? $this->person->isValidIdentityCard() : null;
    }

    /**
     * @Groups({"technicians_read"})
     * @return string|null
     */
    public function getAge(): ?string
    {
        return !is_null($this->person) ? $this->person->getAge() : null;
    }

    /**
     * @Groups({"technicians_read"})
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

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    /**
     * @Groups({"technicians_read"})
     * @return string
     */
    public function getCategoryName():string
    {
        return !is_null($this->category) ? $this->category->getName() : '';
    }

    public function getCategory(): ?TechnicianCategory
    {
        return $this->category;
    }

    public function setCategory(?TechnicianCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSaveAt(): ?DateTimeInterface
    {
        return $this->saveAt;
    }

    /**
     * @param DateTimeInterface|string|null $saveAt
     * @return $this
     */
    public function setSaveAt($saveAt): self
    {
        try {
            $this->saveAt = is_string($saveAt) ? new DateTime($saveAt) : $saveAt;
        } catch (Exception $exception) {
            $this->saveAt = null;
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
     * @Groups({"technicians_read"})
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return !is_null($this->createdBy) ? $this->createdBy->getFullName() ?? $this->createdBy->getUsername() : null;
    }
}
