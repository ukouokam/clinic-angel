<?php

namespace App\Entity\Person\User;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Payment\AdvancePayment;
use App\Entity\Person\Civility;
use App\Entity\Person\Email;
use App\Entity\Person\FirstName;
use App\Entity\Person\IdentityCard;
use App\Entity\Person\MaritalStatus;
use App\Entity\Person\Patient\BloodGroup;
use App\Entity\Person\Patient\Patient;
use App\Entity\Person\PhoneNumber;
use App\Entity\Person\Sex;
use App\Entity\Person\Staff\Cashier\Cashier;
use App\Entity\Person\Staff\Doctor\Doctor;
use App\Entity\Person\Staff\Doctor\DoctorCategory;
use App\Entity\Person\Staff\Nurse\Nurse;
use App\Entity\Person\Staff\Nurse\NurseCategory;
use App\Entity\Person\Staff\OtherStaff\OtherStaff;
use App\Entity\Person\Staff\OtherStaff\OtherTypeStaff;
use App\Entity\Person\Staff\Technician\Technician;
use App\Entity\Person\Staff\Technician\TechnicianCategory;
use App\Entity\Service\Consultation\ConsultationCategory;
use App\Entity\Service\Consultation\ConsultationCategoryCost;
use App\Entity\Service\Consultation\ConsultationRequested;
use App\Entity\Service\Consultation\ConsultationRequestedParameter;
use App\Entity\Service\Consultation\HealthParameter;
use App\Entity\Service\Consultation\ValidityConsultation;
use App\Entity\Service\Drug\Drug;
use App\Entity\Service\Drug\DrugCost;
use App\Entity\Service\Drug\DrugForm;
use App\Entity\Service\Drug\DrugPosology;
use App\Entity\Service\Drug\DrugRequested;
use App\Entity\Service\LabTest\LabTest;
use App\Entity\Service\LabTest\LabTestCategory;
use App\Entity\Service\LabTest\LabTestCost;
use App\Entity\Service\LabTest\LabTestRange;
use App\Entity\Service\LabTest\LabTestRequested;
use App\Entity\Service\LabTest\Model\LabTestModel;
use App\Entity\Service\LabTest\Model\LabTestModelDetail;
use App\Entity\Service\LabTest\Model\LabTestModelRate;
use App\Entity\Service\LabTest\Model\LabTestModelRequested;
use App\Entity\Service\MedicalAct\MedicalAct;
use App\Entity\Service\MedicalAct\MedicalActCategory;
use App\Entity\Service\MedicalAct\MedicalActCost;
use App\Entity\Service\MedicalAct\MedicalActRequested;
use App\Entity\Payment\Payment;
use App\Entity\Person\Person;
use App\Repository\Person\User\UserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 *     normalizationContext={
 *     "groups" = {"users_read"}
 *     }
 * )
 * @ORM\Table(name="`users`")
 * @UniqueEntity(fields={"userName"}, message="Une autre personne utilise déjà ce nom utilisateur")
 *
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"users_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"users_read"})
     * @Assert\NotBlank(message="Le nom utilisateur doit être renseigner")
     * @Assert\Length(
     *     min="3", minMessage="Le nom utilisateur doit avoir 03 caractères minimum",
     *     max="30", maxMessage="Le nom utilisateur doit avoir 30 caractères au maximum"
     * )
     * @Assert\Regex(
     *     pattern="/^[A-Za-z0-9_]+$/",
     *     message="Le nom utilisateur peut contenir les lettres (majuscules et miniscule), les chiffres, le tiret bas"
     * )
     */
    private ?string $userName = null;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le mot de passe est obligatoire")
     * @Assert\Regex(
     *     pattern="/^(?=.{8})(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^a-zA-Z\d])/",
     *     message="Le mot de passe ne respecte pas les critères recommandés"
     * )
     * Complexité mot de passe:
     * -08 caractères min au total: (?=.{8})
     * -Au moins 01 majuscule: (?=.*[A-Z])
     * -Au moins 01 minuscule: (?=.*[a-z])
     * -Au moins 01 chiffre: (?=.*\d)
     * -Au moins 01 caractère spécial: (?=.*[^a-zA-Z\d])
     * @Assert\Length(max="15", maxMessage="Le mot de passe ne peut contenir que 15 caractères")
     */
    private ?string $password = null;

    /**
     * @ORM\OneToOne(targetEntity=Person::class, inversedBy="userPerson", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * @Assert\NotBlank(message="Une personne est requise pour l'utilisateur que vous voulez créer")
     */
    private ?Person $person = null;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"users_read"})
     */
    private ?bool $isBlocked = false;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="users")
     * @Assert\NotBlank(message="L'utilisateur de création est requis")
     */
    private ?User $createdBy = null;

    /**
     * @Groups({"users_read"})
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\OneToMany(targetEntity=Person::class, mappedBy="createdBy")
     * @var Person|ArrayCollection
     * @Groups({"users_read"})
     */
    private $peopleCreated;

    /**
     * @ORM\OneToMany(targetEntity=Patient::class, mappedBy="createdBy")
     * @var Patient[]|ArrayCollection
     * @Groups({"users_read"})
     */
    private $patientsCreated;

    /**
     * @ORM\OneToMany(targetEntity=Doctor::class, mappedBy="createdBy")
     * @var Doctor[]|ArrayCollection
     * @Groups({"users_read"})
     */
    private $doctorsCreated;

    /**
     * @ORM\OneToMany(targetEntity=Nurse::class, mappedBy="createdBy")
     * @var Nurse[]|ArrayCollection
     * @Groups({"users_read"})
     */
    private $nursesCreated;

    /**
     * @ORM\OneToMany(targetEntity=Technician::class, mappedBy="createdBy")
     * @var Technician[]|ArrayCollection
     * @Groups({"users_read"})
     */
    private $techniciansCreated;

    /**
     * @ORM\OneToMany(targetEntity=OtherStaff::class, mappedBy="createdBy")
     * @var OtherStaff[]|ArrayCollection
     * @Groups({"users_read"})
     */
    private $otherStaffsCreated;

    /**
     * @ORM\OneToMany(targetEntity=MedicalActCost::class, mappedBy="createdBy")
     * @var MedicalActCost[]|ArrayCollection
     */
    private $medicalActCosts;

    /**
     * @ORM\OneToMany(targetEntity=MedicalAct::class, mappedBy="createdBy")
     * @var MedicalAct[]|ArrayCollection
     */
    private $medicalActs;

    /**
     * @ORM\OneToMany(targetEntity=MedicalActCategory::class, mappedBy="createdBy")
     * @var MedicalActCategory[]|ArrayCollection
     */
    private $medicalActCategories;

    /**
     * @ORM\OneToMany(targetEntity=MedicalActRequested::class, mappedBy="createdBy")
     * @var MedicalActRequested[]|ArrayCollection
     */
    private $medicalActsRequested;

    /**
     * @ORM\OneToMany(targetEntity=Cashier::class, mappedBy="createdBy")
     * @var Cashier[]|ArrayCollection
     */
    private $cashiers;

    /**
     * @ORM\OneToMany(targetEntity=Payment::class, mappedBy="createdBy")
     * @var Payment[]|ArrayCollection
     */
    private $payments;

    /**
     * @ORM\OneToMany(targetEntity=AdvancePayment::class, mappedBy="createdBy")
     * @var AdvancePayment[]|ArrayCollection
     */
    private $advancePayments;

    /**
     * @ORM\OneToMany(targetEntity=LabTest::class, mappedBy="createdBy")
     * @var LabTest[]|ArrayCollection
     */
    private $labTests;

    /**
     * @ORM\OneToMany(targetEntity=LabTestCategory::class, mappedBy="createdBy")
     * @var LabTestCategory[]|ArrayCollection
     */
    private $labTestCategories;

    /**
     * @ORM\OneToMany(targetEntity=LabTestRange::class, mappedBy="createdBy")
     * @var LabTestRange[]|ArrayCollection
     */
    private $labTestRanges;

    /**
     * @ORM\OneToMany(targetEntity=LabTestCost::class, mappedBy="createdBy")
     * @var LabTestCost[]|ArrayCollection
     */
    private $labTestCosts;

    /**
     * @ORM\OneToMany(targetEntity=LabTestRequested::class, mappedBy="createdBy")
     * @var LabTestRequested[]|ArrayCollection
     */
    private $labTestsRequested;

    /**
     * @ORM\OneToMany(targetEntity=LabTestModel::class, mappedBy="createdBy")
     * @var LabTestModel[]|ArrayCollection
     */
    private $labTestModels;

    /**
     * @ORM\OneToMany(targetEntity=LabTestModelDetail::class, mappedBy="createdBy")
     * @var LabTestModelDetail[]|ArrayCollection
     */
    private $labTestModelDetails;

    /**
     * @ORM\OneToMany(targetEntity=LabTestModelRequested::class, mappedBy="createdBy")
     * @var LabTestModelRequested[]|ArrayCollection
     */
    private $labTestModelsRequested;

    /**
     * @ORM\OneToMany(targetEntity=LabTestModelRate::class, mappedBy="createdBy")
     * @var LabTestModelRate[]|ArrayCollection
     */
    private $labTestModelRates;

    /**
     * @ORM\OneToMany(targetEntity=Drug::class, mappedBy="createdBy")
     * @var Drug[]|ArrayCollection
     */
    private $drugs;

    /**
     * @ORM\OneToMany(targetEntity=DrugCost::class, mappedBy="createdBy")
     * @var DrugCost[]|ArrayCollection
     */
    private $drugCosts;

    /**
     * @ORM\OneToMany(targetEntity=DrugForm::class, mappedBy="createdBy")
     * @var DrugForm[]|ArrayCollection
     */
    private $drugForms;

    /**
     * @ORM\OneToMany(targetEntity=DrugRequested::class, mappedBy="createdBy")
     * @var DrugRequested[]|ArrayCollection
     */
    private $drugsRequested;

    /**
     * @ORM\OneToMany(targetEntity=ConsultationRequested::class, mappedBy="createdBy")
     * @var ConsultationRequested[]|ArrayCollection
     */
    private $consultationsRequested;

    /**
     * @ORM\OneToMany(targetEntity=ConsultationCategory::class, mappedBy="createdBy")
     * @var ConsultationCategory[]|ArrayCollection
     */
    private $consultationCategories;

    /**
     * @ORM\OneToMany(targetEntity=ConsultationCategoryCost::class, mappedBy="createdBy")
     * @var ConsultationCategoryCost[]|ArrayCollection
     */
    private $consultationCategoryCosts;

    /**
     * @ORM\OneToMany(targetEntity=Email::class, mappedBy="createdBy")
     * @var Email[]|ArrayCollection
     */
    private $emails;

    /**
     * @ORM\OneToMany(targetEntity=PhoneNumber::class, mappedBy="createdBy")
     * @var PhoneNumber[]|ArrayCollection
     */
    private $phoneNumbers;

    /**
     * @ORM\OneToMany(targetEntity=IdentityCard::class, mappedBy="createdBy")
     * @var IdentityCard[]|ArrayCollection
     */
    private $identityCards;

    /**
     * @ORM\OneToMany(targetEntity=FirstName::class, mappedBy="createdBy")
     * @var FirstName[]|ArrayCollection
     */
    private $firstNames;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="createdBy")
     * @var User[]|ArrayCollection
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=DrugPosology::class, mappedBy="createdBy")
     * @var DrugPosology[]|ArrayCollection
     */
    private $drugPosologies;

    /**
     * @ORM\OneToMany(targetEntity=ConsultationRequestedParameter::class, mappedBy="createdBy")
     * @var ConsultationRequestedParameter[]|ArrayCollection
     */
    private $consultationRequestedParameters;

    /**
     * @ORM\OneToMany(targetEntity=HealthParameter::class, mappedBy="createdBy")
     * @var HealthParameter[]|ArrayCollection
     */
    private $healthParameters;

    /**
     * @ORM\OneToMany(targetEntity=ValidityConsultation::class, mappedBy="createdBy")
     * @var ValidityConsultation[]|ArrayCollection
     */
    private $validityConsultations;

    /**
     * @ORM\OneToMany(targetEntity=MaritalStatus::class, mappedBy="createdBy")
     * @var MaritalStatus[]|ArrayCollection
     */
    private $maritalStatus;

    /**
     * @ORM\OneToMany(targetEntity=Sex::class, mappedBy="createdBy")
     * @var Sex[]|ArrayCollection
     */
    private $sexes;

    /**
     * @ORM\OneToMany(targetEntity=Civility::class, mappedBy="createdBy")
     */
    private $civilities;

    /**
     * @ORM\OneToMany(targetEntity=BloodGroup::class, mappedBy="createdBy")
     */
    private $bloodGroups;

    /**
     * @ORM\OneToMany(targetEntity=DoctorCategory::class, mappedBy="createdBy")
     */
    private $doctorCategories;

    /**
     * @ORM\OneToMany(targetEntity=NurseCategory::class, mappedBy="createdBy")
     */
    private $nurseCategories;

    /**
     * @ORM\OneToMany(targetEntity=OtherTypeStaff::class, mappedBy="createdBy")
     */
    private $otherTypeStaff;

    /**
     * @ORM\OneToMany(targetEntity=TechnicianCategory::class, mappedBy="createdBy")
     */
    private $technicianCategories;


    public function __construct()
    {
        $this->peopleCreated = new ArrayCollection();
        $this->patientsCreated = new ArrayCollection();
        $this->doctorsCreated = new ArrayCollection();
        $this->nursesCreated = new ArrayCollection();
        $this->techniciansCreated = new ArrayCollection();
        $this->otherStaffsCreated = new ArrayCollection();
        $this->medicalActCosts = new ArrayCollection();
        $this->medicalActs = new ArrayCollection();
        $this->medicalActCategories = new ArrayCollection();
        $this->medicalActsRequested = new ArrayCollection();
        $this->cashiers = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->advancePayments = new ArrayCollection();
        $this->labTests = new ArrayCollection();
        $this->labTestCategories = new ArrayCollection();
        $this->labTestRanges = new ArrayCollection();
        $this->labTestCosts = new ArrayCollection();
        $this->labTestsRequested = new ArrayCollection();
        $this->labTestModels = new ArrayCollection();
        $this->labTestModelDetails = new ArrayCollection();
        $this->labTestModelsRequested = new ArrayCollection();
        $this->labTestModelRates = new ArrayCollection();
        $this->drugs = new ArrayCollection();
        $this->drugCosts = new ArrayCollection();
        $this->drugForms = new ArrayCollection();
        $this->drugsRequested = new ArrayCollection();
        $this->consultationsRequested = new ArrayCollection();
        $this->consultationCategories = new ArrayCollection();
        $this->consultationCategoryCosts = new ArrayCollection();
        $this->emails = new ArrayCollection();
        $this->phoneNumbers = new ArrayCollection();
        $this->identityCards = new ArrayCollection();
        $this->firstNames = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->drugPosologies = new ArrayCollection();
        $this->consultationRequestedParameters = new ArrayCollection();
        $this->healthParameters = new ArrayCollection();
        $this->validityConsultations = new ArrayCollection();
        $this->maritalStatus = new ArrayCollection();
        $this->sexes = new ArrayCollection();
        $this->civilities = new ArrayCollection();
        $this->bloodGroups = new ArrayCollection();
        $this->doctorCategories = new ArrayCollection();
        $this->nurseCategories = new ArrayCollection();
        $this->otherTypeStaff = new ArrayCollection();
        $this->technicianCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Groups({"users_read"})
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return !is_null($this->person) ? $this->person->getFullName() : null;
    }

    /**
     * @Groups({"users_read"})
     * @return DateTimeInterface|null
     */
    public function getBornAt(): ?DateTimeInterface
    {
        return !is_null($this->person) ? $this->person->getBornAt() : null;
    }

    /**
     * @Groups({"users_read"})
     * @return string|null
     */
    public function getPostal(): ?string
    {
        return !is_null($this->person) ? $this->person->getPostal() : null;
    }

    /**
     * @Groups({"users_read"})
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return !is_null($this->person) ? $this->person->getPhoneNumber() : null;
    }

    /**
     * @Groups({"users_read"})
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return !is_null($this->person) ? $this->person->getSlug() : null;
    }

    /**
     * @Groups({"users_read"})
     * @return string|null
     */
    public function getGender():?string
    {
        return !is_null($this->person) ? $this->person->getGender(): null;
    }

    /**
     * @Groups({"users_read"})
     * @return int|string|null
     */
    public function getIdentityNumber()
    {
        return !is_null($this->person) ? $this->person->getIdentityNumber(): null;
    }

    /**
     * @Groups({"users_read"})
     * @return bool|null
     */
    public function isValidIdentityCard(): ?bool
    {
        return !is_null($this->person) ? $this->person->isValidIdentityCard() : null;
    }

    /**
     * @Groups({"users_read"})
     * @return string|null
     */
    public function getAge(): ?string
    {
        return !is_null($this->person) ? $this->person->getAge() : null;
    }

    /**
     * @Groups({"users_read"})
     * @return string|null
     */
    public function getBirthPlace(): ?string
    {
        return !is_null($this->person) ?  $this->person->getBirthPlace() : null;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     * @return mixed
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getIsBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    /**
     * @param bool|int|null $isBlocked
     * @return $this
     */
    public function setIsBlocked($isBlocked): self
    {
        $this->isBlocked = is_int($isBlocked) ? (bool)$isBlocked  : $isBlocked;

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPeopleCreated(): Collection
    {
        return $this->peopleCreated;
    }

    public function addPeopleCreated(Person $peopleCreated): self
    {
        if (!$this->peopleCreated->contains($peopleCreated)) {
            $this->peopleCreated[] = $peopleCreated;
            $peopleCreated->setCreatedBy($this);
        }

        return $this;
    }

    public function removePeopleCreated(Person $peopleCreated): self
    {
        if ($this->peopleCreated->contains($peopleCreated)) {
            $this->peopleCreated->removeElement($peopleCreated);
            // set the owning side to null (unless already changed)
            if ($peopleCreated->getCreatedBy() === $this) {
                $peopleCreated->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Patient[]
     */
    public function getPatientsCreated(): Collection
    {
        return $this->patientsCreated;
    }

    public function addPatientsCreated(Patient $patientsCreated): self
    {
        if (!$this->patientsCreated->contains($patientsCreated)) {
            $this->patientsCreated[] = $patientsCreated;
            $patientsCreated->setCreatedBy($this);
        }

        return $this;
    }

    public function removePatientsCreated(Patient $patientsCreated): self
    {
        if ($this->patientsCreated->contains($patientsCreated)) {
            $this->patientsCreated->removeElement($patientsCreated);
            // set the owning side to null (unless already changed)
            if ($patientsCreated->getCreatedBy() === $this) {
                $patientsCreated->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Doctor[]
     */
    public function getDoctorsCreated(): Collection
    {
        return $this->doctorsCreated;
    }

    public function addDoctorsCreated(Doctor $doctorsCreated): self
    {
        if (!$this->doctorsCreated->contains($doctorsCreated)) {
            $this->doctorsCreated[] = $doctorsCreated;
            $doctorsCreated->setCreatedBy($this);
        }

        return $this;
    }

    public function removeDoctorsCreated(Doctor $doctorsCreated): self
    {
        if ($this->doctorsCreated->contains($doctorsCreated)) {
            $this->doctorsCreated->removeElement($doctorsCreated);
            // set the owning side to null (unless already changed)
            if ($doctorsCreated->getCreatedBy() === $this) {
                $doctorsCreated->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Nurse[]
     */
    public function getNursesCreated(): Collection
    {
        return $this->nursesCreated;
    }

    public function addNursesCreated(Nurse $nursesCreated): self
    {
        if (!$this->nursesCreated->contains($nursesCreated)) {
            $this->nursesCreated[] = $nursesCreated;
            $nursesCreated->setCreatedBy($this);
        }

        return $this;
    }

    public function removeNursesCreated(Nurse $nursesCreated): self
    {
        if ($this->nursesCreated->contains($nursesCreated)) {
            $this->nursesCreated->removeElement($nursesCreated);
            // set the owning side to null (unless already changed)
            if ($nursesCreated->getCreatedBy() === $this) {
                $nursesCreated->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Technician[]
     */
    public function getTechniciansCreated(): Collection
    {
        return $this->techniciansCreated;
    }

    public function addTechniciansCreated(Technician $techniciansCreated): self
    {
        if (!$this->techniciansCreated->contains($techniciansCreated)) {
            $this->techniciansCreated[] = $techniciansCreated;
            $techniciansCreated->setCreatedBy($this);
        }

        return $this;
    }

    public function removeTechniciansCreated(Technician $techniciansCreated): self
    {
        if ($this->techniciansCreated->contains($techniciansCreated)) {
            $this->techniciansCreated->removeElement($techniciansCreated);
            // set the owning side to null (unless already changed)
            if ($techniciansCreated->getCreatedBy() === $this) {
                $techniciansCreated->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OtherStaff[]
     */
    public function getOtherStaffsCreated(): Collection
    {
        return $this->otherStaffsCreated;
    }

    public function addOtherStaffsCreated(OtherStaff $otherStaffsCreated): self
    {
        if (!$this->otherStaffsCreated->contains($otherStaffsCreated)) {
            $this->otherStaffsCreated[] = $otherStaffsCreated;
            $otherStaffsCreated->setCreatedBy($this);
        }

        return $this;
    }

    public function removeOtherStaffsCreated(OtherStaff $otherStaffsCreated): self
    {
        if ($this->otherStaffsCreated->contains($otherStaffsCreated)) {
            $this->otherStaffsCreated->removeElement($otherStaffsCreated);
            // set the owning side to null (unless already changed)
            if ($otherStaffsCreated->getCreatedBy() === $this) {
                $otherStaffsCreated->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MedicalActCost[]
     */
    public function getMedicalActCosts(): Collection
    {
        return $this->medicalActCosts;
    }

    public function addMedicalActCost(MedicalActCost $medicalActCost): self
    {
        if (!$this->medicalActCosts->contains($medicalActCost)) {
            $this->medicalActCosts[] = $medicalActCost;
            $medicalActCost->setCreatedBy($this);
        }

        return $this;
    }

    public function removeMedicalActCost(MedicalActCost $medicalActCost): self
    {
        if ($this->medicalActCosts->contains($medicalActCost)) {
            $this->medicalActCosts->removeElement($medicalActCost);
            // set the owning side to null (unless already changed)
            if ($medicalActCost->getCreatedBy() === $this) {
                $medicalActCost->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MedicalAct[]
     */
    public function getMedicalActs(): Collection
    {
        return $this->medicalActs;
    }

    public function addMedicalAct(MedicalAct $medicalAct): self
    {
        if (!$this->medicalActs->contains($medicalAct)) {
            $this->medicalActs[] = $medicalAct;
            $medicalAct->setCreatedBy($this);
        }

        return $this;
    }

    public function removeMedicalAct(MedicalAct $medicalAct): self
    {
        if ($this->medicalActs->contains($medicalAct)) {
            $this->medicalActs->removeElement($medicalAct);
            // set the owning side to null (unless already changed)
            if ($medicalAct->getCreatedBy() === $this) {
                $medicalAct->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MedicalActCategory[]
     */
    public function getMedicalActCategories(): Collection
    {
        return $this->medicalActCategories;
    }

    public function addMedicalActCategory(MedicalActCategory $medicalActCategory): self
    {
        if (!$this->medicalActCategories->contains($medicalActCategory)) {
            $this->medicalActCategories[] = $medicalActCategory;
            $medicalActCategory->setCreatedBy($this);
        }

        return $this;
    }

    public function removeMedicalActCategory(MedicalActCategory $medicalActCategory): self
    {
        if ($this->medicalActCategories->contains($medicalActCategory)) {
            $this->medicalActCategories->removeElement($medicalActCategory);
            // set the owning side to null (unless already changed)
            if ($medicalActCategory->getCreatedBy() === $this) {
                $medicalActCategory->setCreatedBy(null);
            }
        }

        return $this;
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
            $medicalActRequested->setCreatedBy($this);
        }

        return $this;
    }

    public function removeMedicalActRequested(MedicalActRequested $medicalActRequested): self
    {
        if ($this->medicalActsRequested->contains($medicalActRequested)) {
            $this->medicalActsRequested->removeElement($medicalActRequested);
            // set the owning side to null (unless already changed)
            if ($medicalActRequested->getCreatedBy() === $this) {
                $medicalActRequested->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cashier[]
     */
    public function getCashiers(): Collection
    {
        return $this->cashiers;
    }

    public function addCashier(Cashier $cashier): self
    {
        if (!$this->cashiers->contains($cashier)) {
            $this->cashiers[] = $cashier;
            $cashier->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCashier(Cashier $cashier): self
    {
        if ($this->cashiers->contains($cashier)) {
            $this->cashiers->removeElement($cashier);
            // set the owning side to null (unless already changed)
            if ($cashier->getCreatedBy() === $this) {
                $cashier->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Payment[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setCreatedBy($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->contains($payment)) {
            $this->payments->removeElement($payment);
            // set the owning side to null (unless already changed)
            if ($payment->getCreatedBy() === $this) {
                $payment->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AdvancePayment[]
     */
    public function getAdvancePayments(): Collection
    {
        return $this->advancePayments;
    }

    public function addAdvancePayment(AdvancePayment $advancePayment): self
    {
        if (!$this->advancePayments->contains($advancePayment)) {
            $this->advancePayments[] = $advancePayment;
            $advancePayment->setCreatedBy($this);
        }

        return $this;
    }

    public function removeAdvancePayment(AdvancePayment $advancePayment): self
    {
        if ($this->advancePayments->contains($advancePayment)) {
            $this->advancePayments->removeElement($advancePayment);
            // set the owning side to null (unless already changed)
            if ($advancePayment->getCreatedBy() === $this) {
                $advancePayment->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LabTest[]
     */
    public function getLabTests(): Collection
    {
        return $this->labTests;
    }

    public function addLabTest(LabTest $labTest): self
    {
        if (!$this->labTests->contains($labTest)) {
            $this->labTests[] = $labTest;
            $labTest->setCreatedBy($this);
        }

        return $this;
    }

    public function removeLabTest(LabTest $labTest): self
    {
        if ($this->labTests->contains($labTest)) {
            $this->labTests->removeElement($labTest);
            // set the owning side to null (unless already changed)
            if ($labTest->getCreatedBy() === $this) {
                $labTest->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LabTestCategory[]
     */
    public function getLabTestCategories(): Collection
    {
        return $this->labTestCategories;
    }

    public function addLabTestCategory(LabTestCategory $labTestCategory): self
    {
        if (!$this->labTestCategories->contains($labTestCategory)) {
            $this->labTestCategories[] = $labTestCategory;
            $labTestCategory->setCreatedBy($this);
        }

        return $this;
    }

    public function removeLabTestCategory(LabTestCategory $labTestCategory): self
    {
        if ($this->labTestCategories->contains($labTestCategory)) {
            $this->labTestCategories->removeElement($labTestCategory);
            // set the owning side to null (unless already changed)
            if ($labTestCategory->getCreatedBy() === $this) {
                $labTestCategory->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LabTestRange[]
     */
    public function getLabTestRanges(): Collection
    {
        return $this->labTestRanges;
    }

    public function addLabTestRange(LabTestRange $labTestRange): self
    {
        if (!$this->labTestRanges->contains($labTestRange)) {
            $this->labTestRanges[] = $labTestRange;
            $labTestRange->setCreatedBy($this);
        }

        return $this;
    }

    public function removeLabTestRange(LabTestRange $labTestRange): self
    {
        if ($this->labTestRanges->contains($labTestRange)) {
            $this->labTestRanges->removeElement($labTestRange);
            // set the owning side to null (unless already changed)
            if ($labTestRange->getCreatedBy() === $this) {
                $labTestRange->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LabTestCost[]
     */
    public function getLabTestCosts(): Collection
    {
        return $this->labTestCosts;
    }

    public function addLabTestCost(LabTestCost $labTestCost): self
    {
        if (!$this->labTestCosts->contains($labTestCost)) {
            $this->labTestCosts[] = $labTestCost;
            $labTestCost->setCreatedBy($this);
        }

        return $this;
    }

    public function removeLabTestCost(LabTestCost $labTestCost): self
    {
        if ($this->labTestCosts->contains($labTestCost)) {
            $this->labTestCosts->removeElement($labTestCost);
            // set the owning side to null (unless already changed)
            if ($labTestCost->getCreatedBy() === $this) {
                $labTestCost->setCreatedBy(null);
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
            $labTestsRequested->setCreatedBy($this);
        }

        return $this;
    }

    public function removeLabTestsRequested(LabTestRequested $labTestsRequested): self
    {
        if ($this->labTestsRequested->contains($labTestsRequested)) {
            $this->labTestsRequested->removeElement($labTestsRequested);
            // set the owning side to null (unless already changed)
            if ($labTestsRequested->getCreatedBy() === $this) {
                $labTestsRequested->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LabTestModel[]
     */
    public function getLabTestModels(): Collection
    {
        return $this->labTestModels;
    }

    public function addLabTestModel(LabTestModel $labTestModel): self
    {
        if (!$this->labTestModels->contains($labTestModel)) {
            $this->labTestModels[] = $labTestModel;
            $labTestModel->setCreatedBy($this);
        }

        return $this;
    }

    public function removeLabTestModel(LabTestModel $labTestModel): self
    {
        if ($this->labTestModels->contains($labTestModel)) {
            $this->labTestModels->removeElement($labTestModel);
            // set the owning side to null (unless already changed)
            if ($labTestModel->getCreatedBy() === $this) {
                $labTestModel->setCreatedBy(null);
            }
        }

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
            $labTestModelDetail->setCreatedBy($this);
        }

        return $this;
    }

    public function removeLabTestModelDetail(LabTestModelDetail $labTestModelDetail): self
    {
        if ($this->labTestModelDetails->contains($labTestModelDetail)) {
            $this->labTestModelDetails->removeElement($labTestModelDetail);
            // set the owning side to null (unless already changed)
            if ($labTestModelDetail->getCreatedBy() === $this) {
                $labTestModelDetail->setCreatedBy(null);
            }
        }

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
            $labTestModelsRequested->setCreatedBy($this);
        }

        return $this;
    }

    public function removeLabTestModelsRequested(LabTestModelRequested $labTestModelsRequested): self
    {
        if ($this->labTestModelsRequested->contains($labTestModelsRequested)) {
            $this->labTestModelsRequested->removeElement($labTestModelsRequested);
            // set the owning side to null (unless already changed)
            if ($labTestModelsRequested->getCreatedBy() === $this) {
                $labTestModelsRequested->setCreatedBy(null);
            }
        }

        return $this;
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
            $labTestModelRate->setCreatedBy($this);
        }

        return $this;
    }

    public function removeLabTestModelRate(LabTestModelRate $labTestModelRate): self
    {
        if ($this->labTestModelRates->contains($labTestModelRate)) {
            $this->labTestModelRates->removeElement($labTestModelRate);
            // set the owning side to null (unless already changed)
            if ($labTestModelRate->getCreatedBy() === $this) {
                $labTestModelRate->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Drug[]
     */
    public function getDrugs(): Collection
    {
        return $this->drugs;
    }

    public function addDrug(Drug $drug): self
    {
        if (!$this->drugs->contains($drug)) {
            $this->drugs[] = $drug;
            $drug->setCreatedBy($this);
        }

        return $this;
    }

    public function removeDrug(Drug $drug): self
    {
        if ($this->drugs->contains($drug)) {
            $this->drugs->removeElement($drug);
            // set the owning side to null (unless already changed)
            if ($drug->getCreatedBy() === $this) {
                $drug->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DrugCost[]
     */
    public function getDrugCosts(): Collection
    {
        return $this->drugCosts;
    }

    public function addDrugCost(DrugCost $drugCost): self
    {
        if (!$this->drugCosts->contains($drugCost)) {
            $this->drugCosts[] = $drugCost;
            $drugCost->setCreatedBy($this);
        }

        return $this;
    }

    public function removeDrugCost(DrugCost $drugCost): self
    {
        if ($this->drugCosts->contains($drugCost)) {
            $this->drugCosts->removeElement($drugCost);
            // set the owning side to null (unless already changed)
            if ($drugCost->getCreatedBy() === $this) {
                $drugCost->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DrugForm[]
     */
    public function getDrugForms(): Collection
    {
        return $this->drugForms;
    }

    public function addDrugForm(DrugForm $drugForm): self
    {
        if (!$this->drugForms->contains($drugForm)) {
            $this->drugForms[] = $drugForm;
            $drugForm->setCreatedBy($this);
        }

        return $this;
    }

    public function removeDrugForm(DrugForm $drugForm): self
    {
        if ($this->drugForms->contains($drugForm)) {
            $this->drugForms->removeElement($drugForm);
            // set the owning side to null (unless already changed)
            if ($drugForm->getCreatedBy() === $this) {
                $drugForm->setCreatedBy(null);
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
            $drugsRequested->setCreatedBy($this);
        }

        return $this;
    }

    public function removeDrugsRequested(DrugRequested $drugsRequested): self
    {
        if ($this->drugsRequested->contains($drugsRequested)) {
            $this->drugsRequested->removeElement($drugsRequested);
            // set the owning side to null (unless already changed)
            if ($drugsRequested->getCreatedBy() === $this) {
                $drugsRequested->setCreatedBy(null);
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
            $consultationsRequested->setCreatedBy($this);
        }

        return $this;
    }

    public function removeConsultationsRequested(ConsultationRequested $consultationsRequested): self
    {
        if ($this->consultationsRequested->contains($consultationsRequested)) {
            $this->consultationsRequested->removeElement($consultationsRequested);
            // set the owning side to null (unless already changed)
            if ($consultationsRequested->getCreatedBy() === $this) {
                $consultationsRequested->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConsultationCategory[]
     */
    public function getConsultationCategories(): Collection
    {
        return $this->consultationCategories;
    }

    public function addConsultationCategory(ConsultationCategory $consultationCategory): self
    {
        if (!$this->consultationCategories->contains($consultationCategory)) {
            $this->consultationCategories[] = $consultationCategory;
            $consultationCategory->setCreatedBy($this);
        }

        return $this;
    }

    public function removeConsultationCategory(ConsultationCategory $consultationCategory): self
    {
        if ($this->consultationCategories->contains($consultationCategory)) {
            $this->consultationCategories->removeElement($consultationCategory);
            // set the owning side to null (unless already changed)
            if ($consultationCategory->getCreatedBy() === $this) {
                $consultationCategory->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConsultationCategoryCost[]
     */
    public function getConsultationCategoryCosts(): Collection
    {
        return $this->consultationCategoryCosts;
    }

    public function addConsultationCategoryCost(ConsultationCategoryCost $consultationCategoryCost): self
    {
        if (!$this->consultationCategoryCosts->contains($consultationCategoryCost)) {
            $this->consultationCategoryCosts[] = $consultationCategoryCost;
            $consultationCategoryCost->setCreatedBy($this);
        }

        return $this;
    }

    public function removeConsultationCategoryCost(ConsultationCategoryCost $consultationCategoryCost): self
    {
        if ($this->consultationCategoryCosts->contains($consultationCategoryCost)) {
            $this->consultationCategoryCosts->removeElement($consultationCategoryCost);
            // set the owning side to null (unless already changed)
            if ($consultationCategoryCost->getCreatedBy() === $this) {
                $consultationCategoryCost->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Email[]
     */
    public function getEmails(): Collection
    {
        return $this->emails;
    }

    public function addEmail(Email $email): self
    {
        if (!$this->emails->contains($email)) {
            $this->emails[] = $email;
            $email->setCreatedBy($this);
        }

        return $this;
    }

    public function removeEmail(Email $email): self
    {
        if ($this->emails->contains($email)) {
            $this->emails->removeElement($email);
            // set the owning side to null (unless already changed)
            if ($email->getCreatedBy() === $this) {
                $email->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PhoneNumber[]
     */
    public function getPhoneNumbers(): Collection
    {
        return $this->phoneNumbers;
    }

    public function addPhoneNumber(PhoneNumber $phoneNumber): self
    {
        if (!$this->phoneNumbers->contains($phoneNumber)) {
            $this->phoneNumbers[] = $phoneNumber;
            $phoneNumber->setCreatedBy($this);
        }

        return $this;
    }

    public function removePhoneNumber(PhoneNumber $phoneNumber): self
    {
        if ($this->phoneNumbers->contains($phoneNumber)) {
            $this->phoneNumbers->removeElement($phoneNumber);
            // set the owning side to null (unless already changed)
            if ($phoneNumber->getCreatedBy() === $this) {
                $phoneNumber->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|IdentityCard[]
     */
    public function getIdentityCards(): Collection
    {
        return $this->identityCards;
    }

    public function addIdentityCard(IdentityCard $identityCard): self
    {
        if (!$this->identityCards->contains($identityCard)) {
            $this->identityCards[] = $identityCard;
            $identityCard->setCreatedBy($this);
        }

        return $this;
    }

    public function removeIdentityCard(IdentityCard $identityCard): self
    {
        if ($this->identityCards->contains($identityCard)) {
            $this->identityCards->removeElement($identityCard);
            // set the owning side to null (unless already changed)
            if ($identityCard->getCreatedBy() === $this) {
                $identityCard->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FirstName[]
     */
    public function getFirstNames(): Collection
    {
        return $this->firstNames;
    }

    public function addFirstName(FirstName $firstName): self
    {
        if (!$this->firstNames->contains($firstName)) {
            $this->firstNames[] = $firstName;
            $firstName->setCreatedBy($this);
        }

        return $this;
    }

    public function removeFirstName(FirstName $firstName): self
    {
        if ($this->firstNames->contains($firstName)) {
            $this->firstNames->removeElement($firstName);
            // set the owning side to null (unless already changed)
            if ($firstName->getCreatedBy() === $this) {
                $firstName->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function getCreatedBy(): ?self
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?self $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(self $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCreatedBy($this);
        }

        return $this;
    }

    public function removeUser(self $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getCreatedBy() === $this) {
                $user->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
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
        } catch (Exception $exception) {
            $this->createdAt = null;
        }

        return $this;
    }

    /**
     * @return Collection|DrugPosology[]
     */
    public function getDrugPosologies(): Collection
    {
        return $this->drugPosologies;
    }

    public function addDrugPosology(DrugPosology $drugPosology): self
    {
        if (!$this->drugPosologies->contains($drugPosology)) {
            $this->drugPosologies[] = $drugPosology;
            $drugPosology->setCreatedBy($this);
        }

        return $this;
    }

    public function removeDrugPosology(DrugPosology $drugPosology): self
    {
        if ($this->drugPosologies->contains($drugPosology)) {
            $this->drugPosologies->removeElement($drugPosology);
            // set the owning side to null (unless already changed)
            if ($drugPosology->getCreatedBy() === $this) {
                $drugPosology->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConsultationRequestedParameter[]
     */
    public function getConsultationRequestedParameters(): Collection
    {
        return $this->consultationRequestedParameters;
    }

    public function addConsultationRequestedParameter(ConsultationRequestedParameter $consultationRequestedParameter): self
    {
        if (!$this->consultationRequestedParameters->contains($consultationRequestedParameter)) {
            $this->consultationRequestedParameters[] = $consultationRequestedParameter;
            $consultationRequestedParameter->setCreatedBy($this);
        }

        return $this;
    }

    public function removeConsultationRequestedParameter(ConsultationRequestedParameter $consultationRequestedParameter): self
    {
        if ($this->consultationRequestedParameters->contains($consultationRequestedParameter)) {
            $this->consultationRequestedParameters->removeElement($consultationRequestedParameter);
            // set the owning side to null (unless already changed)
            if ($consultationRequestedParameter->getCreatedBy() === $this) {
                $consultationRequestedParameter->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HealthParameter[]
     */
    public function getHealthParameters(): Collection
    {
        return $this->healthParameters;
    }

    public function addHealthParameter(HealthParameter $healthParameter): self
    {
        if (!$this->healthParameters->contains($healthParameter)) {
            $this->healthParameters[] = $healthParameter;
            $healthParameter->setCreatedBy($this);
        }

        return $this;
    }

    public function removeHealthParameter(HealthParameter $healthParameter): self
    {
        if ($this->healthParameters->contains($healthParameter)) {
            $this->healthParameters->removeElement($healthParameter);
            // set the owning side to null (unless already changed)
            if ($healthParameter->getCreatedBy() === $this) {
                $healthParameter->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ValidityConsultation[]
     */
    public function getValidityConsultations(): Collection
    {
        return $this->validityConsultations;
    }

    public function addValidityConsultation(ValidityConsultation $validityConsultation): self
    {
        if (!$this->validityConsultations->contains($validityConsultation)) {
            $this->validityConsultations[] = $validityConsultation;
            $validityConsultation->setCreatedBy($this);
        }

        return $this;
    }

    public function removeValidityConsultation(ValidityConsultation $validityConsultation): self
    {
        if ($this->validityConsultations->contains($validityConsultation)) {
            $this->validityConsultations->removeElement($validityConsultation);
            // set the owning side to null (unless already changed)
            if ($validityConsultation->getCreatedBy() === $this) {
                $validityConsultation->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MaritalStatus[]
     */
    public function getMaritalStatus(): Collection
    {
        return $this->maritalStatus;
    }

    public function addMaritalStatus(MaritalStatus $maritalStatus): self
    {
        if (!$this->maritalStatus->contains($maritalStatus)) {
            $this->maritalStatus[] = $maritalStatus;
            $maritalStatus->setCreatedBy($this);
        }

        return $this;
    }

    public function removeMaritalStatus(MaritalStatus $maritalStatus): self
    {
        if ($this->maritalStatus->contains($maritalStatus)) {
            $this->maritalStatus->removeElement($maritalStatus);
            // set the owning side to null (unless already changed)
            if ($maritalStatus->getCreatedBy() === $this) {
                $maritalStatus->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sex[]
     */
    public function getSexes(): Collection
    {
        return $this->sexes;
    }

    public function addSex(Sex $sex): self
    {
        if (!$this->sexes->contains($sex)) {
            $this->sexes[] = $sex;
            $sex->setCreatedBy($this);
        }

        return $this;
    }

    public function removeSex(Sex $sex): self
    {
        if ($this->sexes->contains($sex)) {
            $this->sexes->removeElement($sex);
            // set the owning side to null (unless already changed)
            if ($sex->getCreatedBy() === $this) {
                $sex->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Civility[]
     */
    public function getCivilities(): Collection
    {
        return $this->civilities;
    }

    public function addCivility(Civility $civility): self
    {
        if (!$this->civilities->contains($civility)) {
            $this->civilities[] = $civility;
            $civility->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCivility(Civility $civility): self
    {
        if ($this->civilities->contains($civility)) {
            $this->civilities->removeElement($civility);
            // set the owning side to null (unless already changed)
            if ($civility->getCreatedBy() === $this) {
                $civility->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BloodGroup[]
     */
    public function getBloodGroups(): Collection
    {
        return $this->bloodGroups;
    }

    public function addBloodGroup(BloodGroup $bloodGroup): self
    {
        if (!$this->bloodGroups->contains($bloodGroup)) {
            $this->bloodGroups[] = $bloodGroup;
            $bloodGroup->setCreatedBy($this);
        }

        return $this;
    }

    public function removeBloodGroup(BloodGroup $bloodGroup): self
    {
        if ($this->bloodGroups->contains($bloodGroup)) {
            $this->bloodGroups->removeElement($bloodGroup);
            // set the owning side to null (unless already changed)
            if ($bloodGroup->getCreatedBy() === $this) {
                $bloodGroup->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DoctorCategory[]
     */
    public function getDoctorCategories(): Collection
    {
        return $this->doctorCategories;
    }

    public function addDoctorCategory(DoctorCategory $doctorCategory): self
    {
        if (!$this->doctorCategories->contains($doctorCategory)) {
            $this->doctorCategories[] = $doctorCategory;
            $doctorCategory->setCreatedBy($this);
        }

        return $this;
    }

    public function removeDoctorCategory(DoctorCategory $doctorCategory): self
    {
        if ($this->doctorCategories->contains($doctorCategory)) {
            $this->doctorCategories->removeElement($doctorCategory);
            // set the owning side to null (unless already changed)
            if ($doctorCategory->getCreatedBy() === $this) {
                $doctorCategory->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|NurseCategory[]
     */
    public function getNurseCategories(): Collection
    {
        return $this->nurseCategories;
    }

    public function addNurseCategory(NurseCategory $nurseCategory): self
    {
        if (!$this->nurseCategories->contains($nurseCategory)) {
            $this->nurseCategories[] = $nurseCategory;
            $nurseCategory->setCreatedBy($this);
        }

        return $this;
    }

    public function removeNurseCategory(NurseCategory $nurseCategory): self
    {
        if ($this->nurseCategories->contains($nurseCategory)) {
            $this->nurseCategories->removeElement($nurseCategory);
            // set the owning side to null (unless already changed)
            if ($nurseCategory->getCreatedBy() === $this) {
                $nurseCategory->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OtherTypeStaff[]
     */
    public function getOtherTypeStaff(): Collection
    {
        return $this->otherTypeStaff;
    }

    public function addOtherTypeStaff(OtherTypeStaff $otherTypeStaff): self
    {
        if (!$this->otherTypeStaff->contains($otherTypeStaff)) {
            $this->otherTypeStaff[] = $otherTypeStaff;
            $otherTypeStaff->setCreatedBy($this);
        }

        return $this;
    }

    public function removeOtherTypeStaff(OtherTypeStaff $otherTypeStaff): self
    {
        if ($this->otherTypeStaff->contains($otherTypeStaff)) {
            $this->otherTypeStaff->removeElement($otherTypeStaff);
            // set the owning side to null (unless already changed)
            if ($otherTypeStaff->getCreatedBy() === $this) {
                $otherTypeStaff->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TechnicianCategory[]
     */
    public function getTechnicianCategories(): Collection
    {
        return $this->technicianCategories;
    }

    public function addTechnicianCategory(TechnicianCategory $technicianCategory): self
    {
        if (!$this->technicianCategories->contains($technicianCategory)) {
            $this->technicianCategories[] = $technicianCategory;
            $technicianCategory->setCreatedBy($this);
        }

        return $this;
    }

    public function removeTechnicianCategory(TechnicianCategory $technicianCategory): self
    {
        if ($this->technicianCategories->contains($technicianCategory)) {
            $this->technicianCategories->removeElement($technicianCategory);
            // set the owning side to null (unless already changed)
            if ($technicianCategory->getCreatedBy() === $this) {
                $technicianCategory->setCreatedBy(null);
            }
        }

        return $this;
    }
}
