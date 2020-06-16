<?php

namespace App\DataFixtures;

use App\Entity\Payment\Payment;
use App\Entity\Person\Civility;
use App\Entity\Person\MaritalStatus;
use App\Entity\Person\Patient\BloodGroup;
use App\Entity\Person\Patient\Patient;
use App\Entity\Person\Staff\Doctor\Doctor;
use App\Entity\Person\Staff\Nurse\Nurse;
use App\Entity\Person\Staff\OtherStaff\OtherStaff;
use App\Entity\Person\Staff\Technician\Technician;
use App\Entity\Person\User\User;
use App\Entity\Service\Drug\Drug;
use App\Entity\Service\Drug\DrugCost;
use App\Entity\Service\Drug\DrugForm;
use App\Entity\Service\Drug\DrugPosology;
use App\Entity\Service\Drug\DrugRequested;
use App\Entity\Service\Drug\DrugRequestedDetail;
use App\Entity\Service\LabTest\LabTest;
use App\Entity\Service\LabTest\LabTestCategory;
use App\Entity\Service\LabTest\LabTestCost;
use App\Entity\Service\LabTest\LabTestRange;
use App\Entity\Service\LabTest\LabTestRequested;
use App\Entity\Service\LabTest\LabTestRequestedDetail;
use App\Entity\Service\LabTest\Model\LabTestModel;
use App\Entity\Service\LabTest\Model\LabTestModelDetail;
use App\Entity\Service\LabTest\Model\LabTestModelRate;
use App\Entity\Service\LabTest\Model\LabTestModelRequested;
use App\Entity\Service\LabTest\Model\LabTestModelRequestedDetail;
use App\Entity\Service\MedicalAct\MedicalActRequested;
use App\Entity\Service\MedicalAct\MedicalAct;
use App\Entity\Service\MedicalAct\MedicalActCategory;
use App\Entity\Service\MedicalAct\MedicalActCost;
use App\Entity\Person\FirstName;
use App\Entity\Person\IdentityCard;
use App\Entity\Person\Person;
use App\Entity\Person\PhoneNumber;
use App\Entity\Person\Sex;
use App\Entity\Service\MedicalAct\MedicalActRequestedDetail;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        //Sexe
        $sexes = [];
        for ($s = 0; $s <= 1; $s++) {
            $sex = new Sex();
            if ($s === 0) {
                $sex->setSexName('Male');
            } else {
                $sex->setSexName('Female');
            }
            $manager->persist($sex);
            $sexes [] = $sex;
        }

        //Statut matrimonial et civilité
        $civilities = [];
        $maritalStatus_ = [];
        for ($c=1, $cMax = random_int(5, 15); $c<= $cMax; $c++) {
            $civility = new Civility();
            $civility->setName($faker->title);
            $manager->persist($civility);
            $civilities[] = $civility;

            $maritalStatus = new MaritalStatus();
            $maritalStatus->setName($faker->name);
            $manager->persist($maritalStatus);
            $maritalStatus_[] = $maritalStatus;
        }

        //Groupe sanguin
        $bloodGroups = [];
        for ($blood = 1, $bloodMax = random_int(7, 10); $blood <= $bloodMax; $blood++) {
            $bloodGroup = new BloodGroup();
            $bloodGroup->setName($faker->word());
            $manager->persist($bloodGroup);
            $bloodGroups[] = $bloodGroup;
        }

        $users = [];
        //Création de l'utilisateur initial
        $initialUser = new User();
        $initialUser
            ->setUserName('admin')
            ->setRoles([])
            ->setPassword('123456')
            ->setIsBlocked(false);
        $manager->persist($initialUser);
        //$manager->flush();
        $users[] = $initialUser;
        //------------- Fin du premier commit ------------------//

        //Création des personnes et des dérivées
        $patients = [];
        $persons = [];
        for ($pers = 1; $pers <= 50; $pers++) {
            /** @var Sex $sex */
            $sex = $sexes[random_int(0, 1)];
            //Création d'une personne
            $person = new Person();
            $old = $faker->randomElement([43, 23, 55, 14, 65]);
            $person
                ->setLastName($faker->lastName)
                ->setSex($sex)
                ->setBornAt($faker->dateTimeBetween("-$old years"))
                ->setCreatedAt($faker->dateTime())
                ->setPostal($faker->postcode)
                ->setBirthPlace($faker->city)
                ->setCivility($civilities[random_int(0, count($civilities) - 1)])
                ->setMaritalStatus($maritalStatus_[random_int(0, count($maritalStatus_) -1)])
                ->setAddress($faker->address);
            if ($pers === 1) {
                $person->setCreatedBy($initialUser);
            } else {
                /** @var User $userSaved */
                $userSaved = $users[random_int(0, count($users) -1)];
                $person->setCreatedBy($userSaved);
            }
            $manager->persist($person);

            $isUser = random_int(1, 5);
            if ($pers === 1) {
                $user = new User();
                $user
                    ->setIsBlocked($faker->randomElement([true, false]))
                    ->setPassword('123')
                    ->setRoles([])
                    ->setPerson($person)
                    ->setUserName($faker->userName);
                $manager->persist($user);
                $users[] = $user;
            } elseif ($isUser === 3 || $isUser ===5) {
                $user = new User();
                $user
                    ->setIsBlocked($faker->randomElement([true, false]))
                    ->setPassword('123')
                    ->setRoles([])
                    ->setPerson($person)
                    ->setUserName($faker->userName);
                $manager->persist($user);
                $users[] = $user;
            }

            //Creation de ses prénoms
            $max = random_int(1, 4);
            for ($fn = 1; $fn <= $max; $fn++) {
                $firstName = new FirstName();
                $firstName
                    ->setPerson($person)
                    ->setValue($faker->firstName($sex->getSexName()));
                $manager->persist($firstName);
            }

            //Création de ses IdentityCards
            $max = random_int(1, 3);
            for ($idc = 1; $idc <= $max; $idc++) {
                $idCard = new IdentityCard();
                $idCardAt = random_int(3, 6);
                $idCard->setIdentityNumber($faker->randomNumber(9, true))
                    ->setComment($faker->sentence)
                    ->setIssueAt($faker->dateTimeBetween("-$idCardAt years"));
                $idCardAt = random_int(1, 4);
                $idCard
                    ->setExpirationAt($faker->dateTimeBetween('now', "$idCardAt years"))
                    ->setCreatedAt($faker->dateTime)
                    ->setPerson($person);
                if ($max === 1) {
                    $idCard->setActualCard(true);
                } else {
                    if ($idc % 2 === 0) {
                        $idCard->setActualCard(true);
                    } else {
                        $idCard->setActualCard(false);
                    }
                }
                $manager->persist($idCard);
            }
            //Création de ses numéros de téléphone
            $max = random_int(1, 5);
            for ($pn = 1; $pn <= $max; $pn++) {
                $phoneNumber = new PhoneNumber();
                $phoneNumber
                    ->setValue($faker->phoneNumber)
                    ->setComment($faker->sentence(5));
                if ($max === 1) {
                    $phoneNumber->setIsUse(true);
                } elseif ($pn % 2 !== 0) {
                    $phoneNumber->setIsUse(true);
                } else {
                    $phoneNumber->setIsUse(false);
                }
                $phoneNumber->setPerson($person);
                $manager->persist($phoneNumber);
            }

            //On indique si cette personne est un Patient
            $isPatient = $faker->randomElement([true, false]);
            if ($isPatient) {
                $patient = new Patient();
                $patient
                    ->setSaveAt($faker->dateTime)
                    ->setCreatedBy($users[random_int(0, count($users) - 1)])
                    ->setBloodGroup($bloodGroups[random_int(0, count($bloodGroups) - 1)])
                    ->setPerson($person);
                $manager->persist($patient);
                $patients [] = $patient;
            }

            //On indique si cette personne est un Médecin
            $isDoctor = $faker->randomElement([true, false]);
            if ($isDoctor && $pers % 2 === 0) {
                $doctor = new Doctor();
                $doctor
                    ->setSaveAt($faker->dateTime)
                    ->setCreatedBy($users[random_int(0, count($users) - 1)])
                    ->setPerson($person);
                $manager->persist($doctor);
            }

            //On indique si cette personne est un infirmier
            $isNurse = $faker->randomElement([true, false]);
            if ($isNurse && $pers % 3 === 0) {
                $nurse = new Nurse();
                $nurse
                    ->setSaveAt($faker->dateTime)
                    ->setCreatedBy($users[random_int(0, count($users) - 1)])
                    ->setPerson($person);
                $manager->persist($nurse);
            }

            //On indique si cette personne est un technicien
            $isTechnician = $faker->randomElement([true, false]);
            if ($isTechnician && $pers % 4 === 0) {
                $technician = new Technician();
                $technician
                    ->setSaveAt($faker->dateTime)
                    ->setCreatedBy($users[random_int(0, count($users) - 1)])
                    ->setPerson($person);
                $manager->persist($technician);
            }

            //On indique si cette personne fait parti d'autres personnels
            $isOtherStaff = $faker->randomElement([true, false]);
            if ($isOtherStaff && $pers % 5 === 0) {
                $otherStaff = new OtherStaff();
                $otherStaff
                    ->setSaveAt($faker->dateTime)
                    ->setCreatedBy($users[random_int(0, count($users) - 1)])
                    ->setPerson($person);
                $manager->persist($otherStaff);
            }
        }
        //$manager->flush();
        //----------------- Fin du deuxième COMMIT --------------------//

        //Création des catégories d'actes médicaux
        $medicalActsCat = [];
        $medicalActs = [];
        $medicalActCosts = [];
        for ($cat=1, $catMax = random_int(7, 15); $cat<= $catMax; $cat++) {
            $medicalActCat = new MedicalActCategory();
            $medicalActCat
                ->setCreatedAt($faker->dateTime)
                ->setWording($faker->sentence(4))
                ->setCreatedBy($users[random_int(0, count($users) - 1)]);
            $manager->persist($medicalActCat);
            $medicalActsCat[] = $medicalActCat;

            //Pour chaque catégorie, on cree des actes médicaux
            for ($act=1, $actMax = random_int(10, 20); $act<= $actMax; $act++) {
                $medicalAct = new MedicalAct();
                $medicalAct
                    ->setWording($faker->sentence(9, false))
                    ->setCreatedAt($faker->dateTime)
                    ->setCreatedBy($users[random_int(0, count($users) - 1)])
                    ->setCode($faker->isbn10)
                    ->setCategory($medicalActCat)
                    ->setIsBillable($faker->randomElement([true, false]));
                $manager->persist($medicalAct);
                $medicalActs[] = $medicalAct;

                //Création des coûts pour l'acte médical
                for ($p=1; $p <= 4; $p++) {
                    $price = new MedicalActCost();
                    $price
                        ->setMedicalAct($medicalAct)
                        ->setCreatedBy($users[random_int(0, count($users) - 1)])
                        ->setUnitPrice($faker->randomFloat())
                        ->setIsActual(false)
                        ->setCreatedAt($faker->dateTime);
                    if ($p === 2) {
                        $price->setIsActual(true);
                    }
                    $manager->persist($price);
                    $medicalActCosts[] = $price;
                }
                //Création des requêtes
                $haveRequest = $faker->randomElement([true, false]);
                if ($haveRequest) {
                    for ($mActR = 1, $mActRMax = random_int(7, 15); $mActR <= $mActRMax; $mActR++) {
                        $medicalActRequested = new MedicalActRequested();
                        $medicalActRequested
                            ->setComment($faker->text(100))
                            ->setIsUrgent($faker->randomElement([true, false]))
                            ->setPatient($patients[random_int(0, count($patients) - 1)])
                            ->setCreatedBy($users[random_int(0, count($users) - 1)])
                            ->setCreatedAt($faker->dateTime);
                        $manager->persist($medicalActRequested);
                        for ($mActRD = 1, $mActRDMax = random_int(1, 5); $mActRD <= $mActRDMax; $mActRD++) {
                            $medicalActRequestedDetail = new MedicalActRequestedDetail();
                            $havePayment = $faker->randomElement([true, false]);
                            $medicalActRequestedDetail
                                ->setMedicalAct($medicalAct)
                                ->setMedicalActCost($medicalActCosts[random_int(0, count($medicalActCosts) - 1)])
                                ->setMedicalActRequested($medicalActRequested);
                            if ($havePayment) {
                                $payment = new Payment();
                                $payment
                                    ->setCreatedBy($users[random_int(0, count($users) -1)])
                                    ->setCreatedAt($faker->dateTime)
                                    ->setReference($faker->uuid);
                                $manager->persist($payment);
                                $medicalActRequestedDetail->setPayment($payment);
                            }
                            $manager->persist($medicalActRequestedDetail);
                        }
                    }
                }
            }
        }

        //************************** Analyses médicales *******************************//
        // Catégories
        $labTestCategories = [];
        $labTests = [];
        $labTestCosts = [];
        $labTestRanges = [];
        $labTestsRequested = [];
        $labTestsRequestedDetails = [];
        for ($lbc = 1, $lbcMax = random_int(8, 15); $lbc <= $lbcMax; $lbc++) {
            //On cree les catégories d'analyses
            $labTestCategory = new LabTestCategory();
            $labTestCategory
                ->setCreatedAt($faker->dateTime)
                ->setCreatedBy($users[random_int(0, count($users) - 1)])
                ->setWording($faker->text(25));
            $manager->persist($labTestCategory);
            $labTestCategories[] = $labTestCategory;

            //Pour chaque catégorie, on cree plusieurs analyses
            for ($lTest = 1, $lTestMax = random_int(10, 25); $lTest <= $lTestMax; $lTest++) {
                $labTest = new LabTest();
                $labTest
                    ->setWording($faker->text(50))
                    ->setCategory($labTestCategory)
                    ->setCreatedBy($users[random_int(0, count($users) - 1)])
                    ->setCreatedAt($faker->dateTime)
                    ->setCode($faker->creditCardNumber);
                $manager->persist($labTest);
                $labTests[] = $labTest;
                //Pour chaque analyse, on cree les coûts unitaires
                for ($ltCost = 1, $ltCostMax = random_int(3, 4); $ltCost <= $ltCostMax; $ltCost++) {
                    $labTestCost = new LabTestCost();
                    $labTestCost
                        ->setCreatedAt($faker->dateTime)
                        ->setCreatedBy($users[random_int(0, count($users) - 1)])
                        ->setLabTest($labTest)
                        ->setUnitPrice($faker->randomFloat(2, 20000, 35000))
                        ->setIsActual(false);
                    if ($ltCost === 2) {
                        $labTestCost->setIsActual(true);
                    }
                    $manager->persist($labTestCost);
                    $labTestCosts[] = $labTestCost;

                }
                //Pour chaque analyse, on cree les valeurs normales
                for ($lTestRange = 1, $lTestRangeMax = random_int(3, 5); $lTestRange <= $lTestRangeMax; $lTestRange++) {
                    $labTestRange = new LabTestRange();
                    $labTestRange
                        ->setLabTest($labTest)
                        ->setCreatedBy($users[random_int(0, count($users) - 1)])
                        ->setCreatedAt($faker->dateTime)
                        ->setWording($faker->text(30))
                        ->setMinValue($faker->iban())
                        ->setMaxValue($faker->iban())
                        ->setUnit($faker->swiftBicNumber);
                    $manager->persist($labTestRange);
                    $labTestRanges[] = $labTestRange;

                }
                $haveRequest = $faker->randomElement([true, false]);
                if ($haveRequest) {
                    //Création des demandes d'analyses
                    for ($ltRequest = 1, $ltRequestMax = random_int(5, 13); $ltRequest <= $ltRequestMax; $ltRequest++) {
                        $labTestRequested = new LabTestRequested();
                        $labTestRequested
                            ->setPatient($patients[random_int(0, count($patients) - 1)])
                            ->setComment($faker->paragraph)
                            ->setCreatedAt($faker->dateTime)
                            ->setCreatedBy($users[random_int(0, count($users) - 1)]);
                        $manager->persist($labTestRequested);
                        $labTestsRequested[] = $labTestRequested;

                        for ($requestDetail = 1, $requestDetailMax = random_int(2, 5); $requestDetail <= $requestDetailMax; $requestDetail++) {
                            $labTestRequestedDetail = new LabTestRequestedDetail();
                            $labTestRequestedDetail
                                ->setLabTestRequested($labTestRequested)
                                ->setLabTest($labTests[random_int(0, count($labTests) -1)])
                                ->setLabTestCost($labTestCosts[random_int(0, count($labTestCosts) -1)]);
                            $havePayment = $faker->randomElement([true, false]);
                            if ($havePayment) {
                                $payment = new Payment();
                                $payment
                                    ->setCreatedBy($users[random_int(0, count($users) -1)])
                                    ->setCreatedAt($faker->dateTime)
                                    ->setReference($faker->isbn10);
                                $manager->persist($payment);
                                $labTestRequestedDetail->setPayment($payment);
                            }
                            $manager->persist($labTestRequestedDetail);
                            $labTestsRequestedDetails[] = $labTestRequestedDetail;
                        }
                    }
                }

            }
        }

        //********************* Les modèles d'ananlyses ******************//
        $labTestModels = [];
        $labTestModelRates = [];
        $labTestModelDetails = [];
        $labTestModelsRequested = [];
        $labTestModelRequestedDetails = [];
        for ($lTestModel = 0, $lTestModelMax = random_int(7, 15); $lTestModel <= $lTestModelMax; $lTestModel++) {
            $labTestModel = new LabTestModel();
            $labTestModel
                ->setWording($faker->text(25))
                ->setCode($faker->postcode)
                ->setCreatedAt($faker->dateTime)
                ->setCreatedBy($users[random_int(0, count($users) - 1)]);
            $manager->persist($labTestModel);
            $labTestModels[] = $labTestModel;

            //Pour chaque modèle d'analyse, on cree les détails du modèle d'analyse
            $labTestsUse = [];
            for ($lTMDetail = 0, $lTMDetailMax = random_int(4, 7); $lTMDetail <= $lTMDetailMax; $lTMDetail++) {
                $labTestModelDetail = new LabTestModelDetail();
                check:
                $labTestKey = random_int(0, count($labTests) - 1);

                if (array_key_exists($labTestKey, $labTestsUse)) {
                    goto check;
                }
                $labTest = $labTests[$labTestKey];
                $labTestModelDetail
                    ->setCreatedBy($users[random_int(0, count($users) - 1)])
                    ->setCreatedAt($faker->dateTime)
                    ->setLabTest($labTest)
                    ->setLabTestModel($labTestModel);
                $manager->persist($labTestModelDetail);
                $labTestModelDetails[] = $labTestModelDetail;
                $labTestsUse[$labTestKey] = $labTest;
            }
            //Pour chaque modèle d'analyse, on cree les taux de réduction
            for ($rate = 1, $rateMax = random_int(3, 5); $rate <= $rateMax; $rate++) {
                $labTestModelRate = new LabTestModelRate();
                $labTestModelRate
                    ->setLabTestModel($labTestModel)
                    ->setCreatedAt($faker->dateTime)
                    ->setCreatedBy($users[random_int(0, count($users) - 1)])
                    ->setRate($faker->randomFloat(2, 15, 20))
                    ->setIsActual(false);
                if ($rate === 3) {
                    $labTestModelRate->setIsActual(true);
                }
                $manager->persist($labTestModelRate);
                $labTestModelRates[] = $labTestModelRate;
            }

            $haveRequest = $faker->randomElement([true, false]);
            if ($haveRequest) {
                //Création des demandes de test de modèles d'analyses
                for ($ltmRequest = 0, $ltmRequestMax = random_int(4, 15); $ltmRequest <= $ltmRequestMax; $ltmRequest++) {
                    $labTestModelRequested = new LabTestModelRequested();
                    $labTestModelRequested
                        ->setCreatedBy($users[random_int(0, count($users) - 1)])
                        ->setCreatedAt($faker->dateTime)
                        ->setComment($faker->paragraph)
                        ->setPatient($patients[random_int(0, count($patients) - 1)])
                        ->setLabTestModelRate($labTestModelRates[random_int(0, count($labTestModelRates) - 1)]);
                    $manager->persist($labTestModelRequested);
                    $labTestModelsRequested[] = $labTestModelRequested;

                    //Pour chaque requête, on cree plusieurs détails
                    for ($rDetail = 0, $rDetailMax = random_int(3, 5); $rDetail <= $rDetailMax; $rDetail++) {
                        $labTestModelRequestDetail = new LabTestModelRequestedDetail();
                        $labTestModelRequestDetail
                            ->setLabTestCost($labTestCosts[random_int(0, count($labTestCosts) - 1)])
                            ->setLabTestModelRequested($labTestModelRequested)
                            ->setLabTestModel($labTestModel)
                            ->setLabTestModelDetail($labTestModelDetails[random_int(0, count($labTestModelDetails) - 1)]);
                        $havePayment = $faker->randomElement([true, false]);
                        if ($havePayment) {
                            $payment = new Payment();
                            $payment
                                ->setCreatedBy($users[random_int(0, count($users) -1)])
                                ->setCreatedAt($faker->dateTime)
                                ->setReference($faker->uuid);
                            $manager->persist($payment);
                            $labTestModelRequestDetail->setPayment($payment);
                        }
                        $manager->persist($labTestModelRequestDetail);
                        $labTestModelRequestedDetails[] = $labTestModelRequestDetail;
                    }
                }
            }

        }

        //************************** Gestion des médicaments **************************//
        //Empaquetage des médicaments
        $drugForms = [];
        for ($drf = 1, $drfMax = random_int(15, 25); $drf <= $drfMax; $drf++) {
            $drugForm = new DrugForm();
            $drugForm
                ->setWording($faker->text(50))
                ->setCode($faker->uuid)
                ->setCreatedAt($faker->dateTime)
                ->setCreatedBy($users[random_int(0, count($users) - 1)]);
            $manager->persist($drugForm);
            $drugForms[] = $drugForm;
        }
        //Création des médicaments
        for ($dr = 1, $drMax = random_int(100, 150); $dr <= $drMax; $dr++) {
            $drug = new Drug();
            $drug
                ->setCreatedBy($users[random_int(0, count($users) - 1)])
                ->setCode($faker->uuid)
                ->setWording($faker->text(75))
                ->setCreateAt($faker->dateTime)
                ->setPacking($faker->text(50))
                ->setDrugForm($drugForms[random_int(0, count($drugForms) - 1)]);
            $manager->persist($drug);
            //Création des Prix unitaire des médicaments
            $drugCosts = [];
            for ($drc = 1, $drcMax = random_int(2, 5); $drc <= $drcMax; $drc++) {
                $drugCost = new DrugCost();
                $drugCost
                    ->setCreatedBy($users[random_int(0, count($users) - 1)])
                    ->setCreatedAt($faker->dateTime)
                    ->setUnitPrice($faker->randomFloat(2, 3500, 25000))
                    ->setIsActual(false)
                    ->setDrug($drug);
                if ($drc === 2) {
                    $drugCost->setIsActual(true);
                }
                $manager->persist($drugCost);
                $drugCosts[] = $drugCost;
            }
            //Création de la posologie du médicament
            for ($drp = 1, $drpMax = random_int(1, 4); $drp <= $drpMax; $drp++) {
                $drugPosology = new DrugPosology();
                $drugPosology
                    ->setDrug($drug)
                    ->setLabel($faker->text(25))
                    ->setExplanation($faker->text(50));
                $manager->persist($drugPosology);
            }
            $haveRequest = $faker->randomElement([true, false]);
            if ($haveRequest) {
                for ($drRequest = 1, $drRequestMax = random_int(7, 15); $drRequest <= $drRequestMax; $drRequest++) {
                    $drugRequested = new DrugRequested();
                    $drugRequested
                        ->setCreatedAt($faker->dateTime)
                        ->setCreatedBy($users[random_int(0, count($users) - 1)])
                        ->setPatient($patients[random_int(0, count($patients) - 1)])
                        ->setIsUrgent($faker->randomElement([true, false]));
                    $manager->persist($drugRequested);
                    //Création des détails de la requête
                    for ($drRD = 1, $drRDMax = random_int(4, 7); $drRD <= $drRDMax; $drRD++) {
                        $drugRequestedDetail = new DrugRequestedDetail();
                        $havePayment = $faker->randomElement([true, false]);
                        $drugRequestedDetail
                            ->setDrugRequested($drugRequested)
                            ->setDrug($drug)
                            ->setComment($faker->text(70))
                            ->setDrugCost($drugCosts[random_int(0, count($drugCosts) - 1)])
                            ->setQuantity(random_int(1, 3));
                        if ($havePayment) {
                            $payment = new Payment();
                            $payment
                                ->setCreatedBy($users[random_int(0, count($users) -1)])
                                ->setCreatedAt($faker->dateTime)
                                ->setReference($faker->uuid);
                            $manager->persist($payment);
                            $drugRequestedDetail->setPayment($payment);
                        }
                        $manager->persist($drugRequestedDetail);
                    }
                }
            }
        }
        $manager->flush();
    }
}
