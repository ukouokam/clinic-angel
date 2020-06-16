<?php

namespace App\Entity\Person\Patient;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Entity\Service\Consultation\ConsultationRequested;
use App\Entity\Service\Drug\DrugRequested;
use App\Entity\Service\LabTest\LabTestRequested;
use App\Entity\Service\LabTest\Model\LabTestModelRequested;
use App\Entity\Service\MedicalAct\MedicalActRequested;
use App\Entity\Person\Person;
use App\Repository\Person\Patient\PatientRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PatientRepository::class)
 * @ORM\Table(
 *     name="patients",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={"person_id"})
 * })
 * @ApiResource(
 *     normalizationContext={
 *          "groups" = {"patients_read"}
 *     }
 * )
 */
class Patient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({
     *     "patients_read"
     * })
     */
    private ?int $id = null;

    /**
     * @Groups("patients_read")
     * @ORM\OneToOne(targetEntity=Person::class, inversedBy="patient", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, unique=true)
     * @Assert\NotBlank(message="Une personne est requise pour créer un patient")
     */
    private ? Person $person;

    /**
     * @Groups("patients_read")
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création doit être au format valide (YYYY-MM-DD H:i:s)")
     */
    private ?DateTimeInterface $saveAt = null;

    /**
     * @Groups("patients_read")
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="patientsCreated")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur qui cree le patient est requis")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\OneToMany(targetEntity=MedicalActRequested::class, mappedBy="patient")
     * @var MedicalActRequested[]|ArrayCollection
     */
    private $medicalActsRequested;

    /**
     * @ORM\OneToMany(targetEntity=LabTestRequested::class, mappedBy="patient")
     * @var LabTestRequested[]|ArrayCollection
     */
    private $labTestsRequested;

    /**
     * @ORM\ManyToOne(targetEntity=BloodGroup::class, inversedBy="patients")
     */
    private ?BloodGroup $bloodGroup = null;

    /**
     * @ORM\OneToMany(targetEntity=LabTestModelRequested::class, mappedBy="patient")
     * @var LabTestModelRequested[]|ArrayCollection
     */
    private $labTestModelsRequested;

    /**
     * @ORM\OneToMany(targetEntity=DrugRequested::class, mappedBy="patient")
     * @var DrugRequested[]|ArrayCollection
     */
    private $drugsRequested;

    /**
     * @ORM\OneToMany(targetEntity=ConsultationRequested::class, mappedBy="patient")
     * @var ConsultationRequested[]|ArrayCollection
     */
    private $consultationsRequested;


    public function __construct()
    {
        $this->medicalActsRequested = new ArrayCollection();
        $this->labTestsRequested = new ArrayCollection();
        $this->labTestModelsRequested = new ArrayCollection();
        $this->drugsRequested = new ArrayCollection();
        $this->consultationsRequested = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Groups("patients_read")
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->person->getFullName();
    }

    /**
     * @Groups({"patients_read"})
     * @return DateTimeInterface|null
     */
    public function getBornAt(): ?DateTimeInterface
    {
        return $this->person->getBornAt();
    }

    /**
     * @Groups({"patients_read"})
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->person->getCreatedAt();
    }

    /**
     * @Groups({"patients_read"})
     * @return string|null
     */
    public function getPostal(): ?string
    {
        return $this->person->getPostal();
    }

    /**
     * @Groups({"patients_read"})
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->person->getPhoneNumber();
    }

    /**
     * @Groups({"patients_read"})
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->person->getSlug();
    }

    /**
     * @Groups({"patients_read"})
     * @return string
     */
    public function getGender():string
    {
        return $this->person->getGender();
    }

    /**
     * @Groups({"patients_read"})
     * @return int|string|null
     */
    public function getIdentityNumber()
    {
        return $this->person->getIdentityNumber();
    }

    /**
     * @Groups({"patients_read"})
     * @return bool|null
     */
    public function isValidIdentityCard(): ?bool
    {
        return $this->person->isValidIdentityCard();
    }

    /**
     * @Groups({"patients_read"})
     * @return string|null
     */
    public function getAge(): ?string
    {
        return $this->person->getAge();
    }

    /**
     * @Groups({"patients_read"})
     * @return string|null
     */
    public function getBirthPlace(): ?string
    {
        return $this->person->getBirthPlace();
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

    public function getAuthor(): ?string
    {
        return !is_null($this->createdBy) ? $this->createdBy->getFullName() ?? $this->createdBy->getUsername() : null;
    }

    /**
     * @return Collection|MedicalActRequested[]
     */
    public function getMedicalActsRequested(): Collection
    {
        return $this->medicalActsRequested;
    }

    public function addMedicalActRequested(MedicalActRequested $medicalActRequested): self
    {
        if (!$this->medicalActsRequested->contains($medicalActRequested)) {
            $this->medicalActsRequested[] = $medicalActRequested;
            $medicalActRequested->setPatient($this);
        }

        return $this;
    }

    public function removeMedicalActRequested(MedicalActRequested $medicalActRequested): self
    {
        if ($this->medicalActsRequested->contains($medicalActRequested)) {
            $this->medicalActsRequested->removeElement($medicalActRequested);
            // set the owning side to null (unless already changed)
            if ($medicalActRequested->getPatient() === $this) {
                $medicalActRequested->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LabTestRequested[]
     */
    public function getLabTestsRequested(): Collection
    {
        return $this->labTestsRequested;
    }

    public function addLabTestsRequested(LabTestRequested $labTestsRequested): self
    {
        if (!$this->labTestsRequested->contains($labTestsRequested)) {
            $this->labTestsRequested[] = $labTestsRequested;
            $labTestsRequested->setPatient($this);
        }

        return $this;
    }

    public function removeLabTestsRequested(LabTestRequested $labTestsRequested): self
    {
        if ($this->labTestsRequested->contains($labTestsRequested)) {
            $this->labTestsRequested->removeElement($labTestsRequested);
            // set the owning side to null (unless already changed)
            if ($labTestsRequested->getPatient() === $this) {
                $labTestsRequested->setPatient(null);
            }
        }

        return $this;
    }

    public function getBloodGroup(): ?BloodGroup
    {
        return $this->bloodGroup;
    }

    public function setBloodGroup(?BloodGroup $bloodGroup): self
    {
        $this->bloodGroup = $bloodGroup;

        return $this;
    }

    /**
     * @Groups({"patients_read"})
     * @return string|null
     */
    public function getBloodGroupName(): ?string
    {
        return !is_null($this->bloodGroup) ? $this->bloodGroup->getName() : null;
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
            $labTestModelsRequested->setPatient($this);
        }

        return $this;
    }

    public function removeLabTestModelsRequested(LabTestModelRequested $labTestModelsRequested): self
    {
        if ($this->labTestModelsRequested->contains($labTestModelsRequested)) {
            $this->labTestModelsRequested->removeElement($labTestModelsRequested);
            // set the owning side to null (unless already changed)
            if ($labTestModelsRequested->getPatient() === $this) {
                $labTestModelsRequested->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DrugRequested[]
     */
    public function getDrugsRequested(): Collection
    {
        return $this->drugsRequested;
    }

    public function addDrugsRequested(DrugRequested $drugsRequested): self
    {
        if (!$this->drugsRequested->contains($drugsRequested)) {
            $this->drugsRequested[] = $drugsRequested;
            $drugsRequested->setPatient($this);
        }

        return $this;
    }

    public function removeDrugsRequested(DrugRequested $drugsRequested): self
    {
        if ($this->drugsRequested->contains($drugsRequested)) {
            $this->drugsRequested->removeElement($drugsRequested);
            // set the owning side to null (unless already changed)
            if ($drugsRequested->getPatient() === $this) {
                $drugsRequested->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConsultationRequested[]
     */
    public function getConsultationsRequested(): Collection
    {
        return $this->consultationsRequested;
    }

    public function addConsultationsRequested(ConsultationRequested $consultationsRequested): self
    {
        if (!$this->consultationsRequested->contains($consultationsRequested)) {
            $this->consultationsRequested[] = $consultationsRequested;
            $consultationsRequested->setPatient($this);
        }

        return $this;
    }

    public function removeConsultationsRequested(ConsultationRequested $consultationsRequested): self
    {
        if ($this->consultationsRequested->contains($consultationsRequested)) {
            $this->consultationsRequested->removeElement($consultationsRequested);
            // set the owning side to null (unless already changed)
            if ($consultationsRequested->getPatient() === $this) {
                $consultationsRequested->setPatient(null);
            }
        }

        return $this;
    }
}
