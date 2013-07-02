<?php

namespace Virgule\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Virgule\Bundle\MainBundle\Entity\Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity(repositoryClass="Virgule\Bundle\MainBundle\Repository\StudentRepository")
 */
class Student {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime $registrationDate
     *
     * @ORM\Column(name="registration_date", type="datetime", nullable=false)
     */
    private $registrationDate;

    /**
     * @var string $lastname
     *
     * @ORM\Column(name="lastname", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Merci de saisir un nom de famille")
     * @Assert\NotNull()
     */
    private $lastname;

    /**
     * @var string $firstname
     *
     * @ORM\Column(name="firstname", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Merci de saisir un prénom")
     * @Assert\NotNull()
     */
    private $firstname;

    /**
     * @var \DateTime $birthdate
     *
     * @ORM\Column(name="birthdate", type="date", nullable=true)
     */
    private $birthdate;

    /**
     * @var string $phoneNumber
     *
     * @ORM\Column(name="phone_number", type="string", length=10, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string $cellphoneNumber
     *
     * @ORM\Column(name="cellphone_number", type="string", length=10, nullable=true)
     */
    private $cellphoneNumber;

    /**
     * @var string $address
     *
     * @ORM\Column(name="address", type="string", length=50, nullable=true)
     */
    private $address;

    /**
     * @var string $zipcode
     *
     * @ORM\Column(name="zipcode", type="string", length=10, nullable=true)
     */
    private $zipcode;

    /**
     * @var string $city
     *
     * @ORM\Column(name="city", type="string", length=50, nullable=true)
     */
    private $city;

    /**
     * @var string $gender
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=true)
     */
    private $gender;

    /**
     * @var string $nationality
     *
     * @ORM\Column(name="nationality", type="string", length=50, nullable=true)
     */
    private $nationality;

    /**
     * @var string $maritalStatus
     *
     * @ORM\Column(name="marital_status", type="string", length=20, nullable=true)
     */
    private $maritalStatus;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="student", cascade={"persist", "remove"})
     * @ORM\OrderBy({"date" = "DESC"}).
     */
    private $comments;

    /**
     * @var string $picturePath
     *
     * @ORM\Column(name="picturePath", type="string", length=50, nullable=true)
     */
    private $picturePath;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $pictureFile;

    /**
     * @var \DateTime $arrivalDate
     *
     * @ORM\Column(name="arrival_date", type="date", nullable=true)
     */
    private $arrivalDate;

    /**
     * @var boolean $scholarized
     *
     * @ORM\Column(name="scholarized", type="boolean", nullable=true)
     */
    private $scholarized;

    /**
     * @var string $profession
     *
     * @ORM\Column(name="profession", type="string", length=45, nullable=true)
     */
    private $profession;

    /**
     * @var boolean $scholarizedInTheCountry
     *
     * @ORM\Column(name="scholarized_in_the_country", type="boolean", nullable=true)
     */
    private $scholarizedInTheCountry;

    /**
     * @var boolean $scholarizedInAForeignCountry
     *
     * @ORM\Column(name="scholarized_in_a_foreign_country", type="boolean", nullable=true)
     */
    private $scholarizedInAForeignCountry;

    /**
     * @var boolean $scholarizationLevel
     *
     * @ORM\Column(name="scholarization_level", type="boolean", nullable=true)
     */
    private $scholarizationLevel;

    /**
     * @var string $emergencyContactLastname
     *
     * @ORM\Column(name="emergency_contact_lastname", type="string", length=45, nullable=true)
     */
    private $emergencyContactLastname;

    /**
     * @var string $emergencyContactFirstname
     *
     * @ORM\Column(name="emergency_contact_firstname", type="string", length=45, nullable=true)
     */
    private $emergencyContactFirstname;

    /**
     * @var string $emergencyContactPhoneNumber
     *
     * @ORM\Column(name="emergency_contact_phone_number", type="string", length=45, nullable=true)
     */
    private $emergencyContactPhoneNumber;

    /**
     * @var string $emergencyContactConnectionType
     *
     * @ORM\Column(name="emergency_contact_connection_type", type="string", length=45, nullable=true)
     */
    private $emergencyContactConnectionType;

    /**
     * @var string $nativeCountry
     *
     * @ORM\Column(name="nativeCountry", type="string", length=2, nullable=true)
     */
    private $nativeCountry;

    /**
     * @ORM\ManyToOne(targetEntity="Teacher", inversedBy="studentsWelcomed")
     * @ORM\JoinColumn(name="fk_welcomed_by_teacher", referencedColumnName="id")
     */
    private $welcomedByTeacher;

    /**
     * @ORM\ManyToOne(targetEntity="OrganizationBranch")
     * @ORM\JoinColumn(name="fk_organization_branch", referencedColumnName="id")
     */
    private $welcomedInOrganizationBranch;

    /**
     * @var Spoken Languages
     *
     * @ORM\ManyToMany(targetEntity="Language", inversedBy="students")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $spokenLanguages;

    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_scholarization_language_id", referencedColumnName="id")
     * })
     */
    private $fkScholarizationLanguage;

    /**
     * @ORM\ManyToMany(targetEntity="Course", inversedBy="students")
     */
    private $courses;

    /**
     * @ORM\ManyToMany(targetEntity="ClassSession", mappedBy="classSessionStudents", cascade={"persist"})
     */
    private $classSessions;

    /**
     * @ORM\OneToMany(targetEntity="ClassLevelSuggested", mappedBy="student", cascade={"persist", "remove"})
     * @ORM\OrderBy({"dateOfChange" = "DESC"}).
     */
    private $suggestedClassLevel;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set registrationDate
     *
     * @param \DateTime $registrationDate
     * @return Student
     */
    public function setRegistrationDate($registrationDate) {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * Get registrationDate
     *
     * @return \DateTime 
     */
    public function getRegistrationDate() {
        return $this->registrationDate;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Student
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Student
     */
    public function setFirstname($firstname) {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname() {
        return $this->firstname;
    }

    public function getFullName() {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return Student
     */
    public function setBirthdate($birthdate) {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime 
     */
    public function getBirthdate() {
        return $this->birthdate;
    }

    /**
     * Get age
     * Calculates and returns it in years
     *
     * @return \DateTime 
     */
    public function getAge() {
        $now = new \DateTime('now');
        return $this->birthdate->diff($now)->format('%Y');
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     * @return Student
     */
    public function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string 
     */
    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    /**
     * Set cellphoneNumber
     *
     * @param string $cellphoneNumber
     * @return Student
     */
    public function setCellphoneNumber($cellphoneNumber) {
        $this->cellphoneNumber = $cellphoneNumber;

        return $this;
    }

    /**
     * Get cellphoneNumber
     *
     * @return string 
     */
    public function getCellphoneNumber() {
        return $this->cellphoneNumber;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Student
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     * @return Student
     */
    public function setZipcode($zipcode) {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string 
     */
    public function getZipcode() {
        return $this->zipcode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Student
     */
    public function setCity($city) {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return Student
     */
    public function setGender($gender) {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Set nationality
     *
     * @param string $nationality
     * @return Student
     */
    public function setNationality($nationality) {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * Get nationality
     *
     * @return string 
     */
    public function getNationality() {
        return $this->nationality;
    }

    /**
     * Set maritalStatus
     *
     * @param string $maritalStatus
     * @return Student
     */
    public function setMaritalStatus($maritalStatus) {
        $this->maritalStatus = $maritalStatus;

        return $this;
    }

    /**
     * Get maritalStatus
     *
     * @return string 
     */
    public function getMaritalStatus() {
        return $this->maritalStatus;
    }

    /**
     * Set arrivalDate
     *
     * @param \DateTime $arrivalDate
     * @return Student
     */
    public function setArrivalDate($arrivalDate) {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    /**
     * Get arrivalDate
     *
     * @return \DateTime 
     */
    public function getArrivalDate() {
        return $this->arrivalDate;
    }

    /**
     * Set scholarized
     *
     * @param boolean $scholarized
     * @return Student
     */
    public function setScholarized($scholarized) {
        $this->scholarized = $scholarized;

        return $this;
    }

    /**
     * Get scholarized
     *
     * @return boolean 
     */
    public function getScholarized() {
        return $this->scholarized;
    }

    /**
     * Set profession
     *
     * @param string $profession
     * @return Student
     */
    public function setProfession($profession) {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get profession
     *
     * @return string 
     */
    public function getProfession() {
        return $this->profession;
    }

    /**
     * Set scholarizedInTheCountry
     *
     * @param boolean $scholarizedInTheCountry
     * @return Student
     */
    public function setScholarizedInTheCountry($scholarizedInTheCountry) {
        $this->scholarizedInTheCountry = $scholarizedInTheCountry;

        return $this;
    }

    /**
     * Get scholarizedInTheCountry
     *
     * @return boolean 
     */
    public function getScholarizedInTheCountry() {
        return $this->scholarizedInTheCountry;
    }

    /**
     * Set scholarizedInAForeignCountry
     *
     * @param boolean $scholarizedInAForeignCountry
     * @return Student
     */
    public function setScholarizedInAForeignCountry($scholarizedInAForeignCountry) {
        $this->scholarizedInAForeignCountry = $scholarizedInAForeignCountry;

        return $this;
    }

    /**
     * Get scholarizedInAForeignCountry
     *
     * @return boolean 
     */
    public function getScholarizedInAForeignCountry() {
        return $this->scholarizedInAForeignCountry;
    }

    /**
     * Set scholarizationLevel
     *
     * @param boolean $scholarizationLevel
     * @return Student
     */
    public function setScholarizationLevel($scholarizationLevel) {
        $this->scholarizationLevel = $scholarizationLevel;

        return $this;
    }

    /**
     * Get scholarizationLevel
     *
     * @return boolean 
     */
    public function getScholarizationLevel() {
        return $this->scholarizationLevel;
    }

    /**
     * Set emergencyContactLastname
     *
     * @param string $emergencyContactLastname
     * @return Student
     */
    public function setEmergencyContactLastname($emergencyContactLastname) {
        $this->emergencyContactLastname = $emergencyContactLastname;

        return $this;
    }

    /**
     * Get emergencyContactLastname
     *
     * @return string 
     */
    public function getEmergencyContactLastname() {
        return $this->emergencyContactLastname;
    }

    /**
     * Set emergencyContactFirstname
     *
     * @param string $emergencyContactFirstname
     * @return Student
     */
    public function setEmergencyContactFirstname($emergencyContactFirstname) {
        $this->emergencyContactFirstname = $emergencyContactFirstname;

        return $this;
    }

    /**
     * Get emergencyContactFirstname
     *
     * @return string 
     */
    public function getEmergencyContactFirstname() {
        return $this->emergencyContactFirstname;
    }

    /**
     * Set emergencyContactPhoneNumber
     *
     * @param string $emergencyContactPhoneNumber
     * @return Student
     */
    public function setEmergencyContactPhoneNumber($emergencyContactPhoneNumber) {
        $this->emergencyContactPhoneNumber = $emergencyContactPhoneNumber;

        return $this;
    }

    /**
     * Get emergencyContactPhoneNumber
     *
     * @return string 
     */
    public function getEmergencyContactPhoneNumber() {
        return $this->emergencyContactPhoneNumber;
    }

    /**
     * Set emergencyContactConnectionType
     *
     * @param string $emergencyContactConnectionType
     * @return Student
     */
    public function setEmergencyContactConnectionType($emergencyContactConnectionType) {
        $this->emergencyContactConnectionType = $emergencyContactConnectionType;

        return $this;
    }

    /**
     * Get emergencyContactConnectionType
     *
     * @return string 
     */
    public function getEmergencyContactConnectionType() {
        return $this->emergencyContactConnectionType;
    }

    /**
     * Set fkNativeCountry
     *
     * @param  $fkNativeCountry
     * @return Student
     */
    public function setFkNativeCountry($fkNativeCountry = null) {
        $this->fkNativeCountry = $fkNativeCountry;

        return $this;
    }

    /**
     * Get fkNativeCountry
     *
     * @return 
     */
    public function getFkNativeCountry() {
        return $this->fkNativeCountry;
    }

    /**
     * Set fkScholarizationLanguage
     *
     * @param Virgule\Bundle\MainBundle\Entity\Language $fkScholarizationLanguage
     * @return Student
     */
    public function setFkScholarizationLanguage(\Virgule\Bundle\MainBundle\Entity\Language $fkScholarizationLanguage = null) {
        $this->fkScholarizationLanguage = $fkScholarizationLanguage;

        return $this;
    }

    /**
     * Get fkScholarizationLanguage
     *
     * @return Virgule\Bundle\MainBundle\Entity\Language 
     */
    public function getFkScholarizationLanguage() {
        return $this->fkScholarizationLanguage;
    }

    /**
     * Set nativeCountry
     *
     * @param String $nativeCountry
     * @return Student
     */
    public function setNativeCountry($nativeCountry = null) {
        $this->nativeCountry = $nativeCountry;

        return $this;
    }

    /**
     * Get nativeCountry
     *
     * @return $nativeCountry
     */
    public function getNativeCountry() {
        return $this->nativeCountry;
    }

    /**
     * Set welcomedByTeacher
     *
     * @param \Virgule\Bundle\MainBundle\Entity\Teacher $welcomedByTeacher
     * @return Student
     */
    public function setWelcomedByTeacher(\Virgule\Bundle\MainBundle\Entity\Teacher $welcomedByTeacher = null) {
        $this->welcomedByTeacher = $welcomedByTeacher;

        return $this;
    }

    /**
     * Get welcomedByTeacher
     *
     * @return \Virgule\Bundle\MainBundle\Entity\Teacher 
     */
    public function getWelcomedByTeacher() {
        return $this->welcomedByTeacher;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->registrationDate = new \DateTime('now');
        $this->courses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->classSessions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->suggestedClassLevel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * Add comments
     *
     * @param \Virgule\Bundle\MainBundle\Entity\Comment $comments
     * @return Student
     */
    public function addComment(\Virgule\Bundle\MainBundle\Entity\Comment $comments) {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Virgule\Bundle\MainBundle\Entity\Comment $comments
     */
    public function removeComment(\Virgule\Bundle\MainBundle\Entity\Comment $comments) {
        $this->comments->removeElement($comments);
    }

    /**
     * Set comments
     *
     * @param string $comments
     * @return Student
     */
    public function setComments($comments) {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string 
     */
    public function getComments() {
        return $this->comments;
    }

    /**
     * Add courses
     *
     * @param \Virgule\Bundle\MainBundle\Entity\Course $courses
     * @return Student
     */
    public function addCourse(\Virgule\Bundle\MainBundle\Entity\Course $courses) {
        $this->courses[] = $courses;

        return $this;
    }

    /**
     * Remove courses
     *
     * @param \Virgule\Bundle\MainBundle\Entity\Course $courses
     */
    public function removeCourse(\Virgule\Bundle\MainBundle\Entity\Course $courses) {
        $this->courses->removeElement($courses);
    }

    /**
     * Get courses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCourses() {
        return $this->courses;
    }

    /**
     * Set welcomedInOrganizationBranch
     *
     * @param \Virgule\Bundle\MainBundle\Entity\OrganizationBranch $welcomedInOrganizationBranch
     * @return Student
     */
    public function setWelcomedInOrganizationBranch(\Virgule\Bundle\MainBundle\Entity\OrganizationBranch $welcomedInOrganizationBranch = null) {
        $this->welcomedInOrganizationBranch = $welcomedInOrganizationBranch;

        return $this;
    }

    /**
     * Get welcomedInOrganizationBranch
     *
     * @return \Virgule\Bundle\MainBundle\Entity\OrganizationBranch 
     */
    public function getWelcomedInOrganizationBranch() {
        return $this->welcomedInOrganizationBranch;
    }

    /**
     * Add classSessions
     *
     * @param \Virgule\Bundle\MainBundle\Entity\ClassSession $classSessions
     * @return Student
     */
    public function addClassSession(\Virgule\Bundle\MainBundle\Entity\ClassSession $classSessions) {
        $this->classSessions[] = $classSessions;

        return $this;
    }

    /**
     * Remove classSessions
     *
     * @param \Virgule\Bundle\MainBundle\Entity\ClassSession $classSessions
     */
    public function removeClassSession(\Virgule\Bundle\MainBundle\Entity\ClassSession $classSessions) {
        $this->classSessions->removeElement($classSessions);
    }

    /**
     * Get classSessions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClassSessions() {
        return $this->classSessions;
    }

    /**
     * Add suggestedClassLevel
     *
     * @param \Virgule\Bundle\MainBundle\Entity\ClassLevelSuggested $suggestedClassLevel
     * @return Student
     */
    public function addSuggestedClassLevel(\Virgule\Bundle\MainBundle\Entity\ClassLevelSuggested $suggestedClassLevel) {
        $this->suggestedClassLevel[] = $suggestedClassLevel;

        return $this;
    }

    /**
     * Remove suggestedClassLevel
     *
     * @param \Virgule\Bundle\MainBundle\Entity\ClassLevelSuggested $suggestedClassLevel
     */
    public function removeSuggestedClassLevel(\Virgule\Bundle\MainBundle\Entity\ClassLevelSuggested $suggestedClassLevel) {
        $this->suggestedClassLevel->removeElement($suggestedClassLevel);
    }

    /**
     * Get suggestedClassLevel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSuggestedClassLevel() {
        return $this->suggestedClassLevel;
    }

    /**
     * Add spokenLanguages
     *
     * @param \Virgule\Bundle\MainBundle\Entity\Language $spokenLanguages
     * @return Student
     */
    public function addSpokenLanguage(\Virgule\Bundle\MainBundle\Entity\Language $spokenLanguages) {
        $this->spokenLanguages[] = $spokenLanguages;

        return $this;
    }

    /**
     * Remove spokenLanguages
     *
     * @param \Virgule\Bundle\MainBundle\Entity\Language $spokenLanguages
     */
    public function removeSpokenLanguage(\Virgule\Bundle\MainBundle\Entity\Language $spokenLanguages) {
        $this->spokenLanguages->removeElement($spokenLanguages);
    }

    /**
     * Get spokenLanguages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSpokenLanguages() {
        return $this->spokenLanguages;
    }

    public function getAbsolutePath() {
        return null === $this->picturePath ? null : $this->getUploadRootDir() . '/' . $this->picturePath;
    }

    public function getWebPath() {
        return null === $this->picturePath ? null : $this->getUploadDir() . '/' . $this->picturePath;
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/student_pictures';
    }

    /**
     * Sets pictureFile.
     *
     * @param UploadedPictureFile $pictureFile
     */
    public function setPictureFile(UploadedFile $pictureFile = null) {
        $this->pictureFile = $pictureFile;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getPictureFile() {
        return $this->pictureFile;
    }

    public function upload() {
        // the file property can be empty if the field is not required
        if (null === $this->getPictureFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // move takes the target directory and then the
        // target filename to move to
        $this->getPictureFile()->move(
                $this->getUploadRootDir(), $this->getPictureFile()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $this->picturePath = $this->getPictureFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->pictureFile = null;
    }

}