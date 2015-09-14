<?php

namespace Virgule\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Semester
 *
 * @ORM\Table(name="semester")
 * @ORM\Entity(repositoryClass="Virgule\Bundle\MainBundle\Repository\SemesterRepository")
 */
class Semester {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=false)
     */
    protected $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date", nullable=false)
     */
    protected $endDate;

    /**
     * @ORM\OneToMany(targetEntity="Course", mappedBy="semester")
     */
    protected $courses;

    /**
     * @ORM\ManyToOne(targetEntity="OrganizationBranch", inversedBy="semesters")
     * @ORM\JoinColumn(name="fk_organization_branch", referencedColumnName="id")
     */
    protected $organizationBranch;    
    
    /**
     * @ORM\OneToMany(targetEntity="OpenHouse", mappedBy="semester", cascade={"persist"})
     */
    protected $openHouses;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Semester
     */
    public function setStartDate($startDate) {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate() {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Semester
     */
    public function setEndDate($endDate) {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate() {
        return $this->endDate;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->courses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->openHouses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add courses
     *
     * @param \Virgule\Bundle\MainBundle\Entity\Course $courses
     * @return Semester
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
     * Set organizationBranch
     *
     * @param \Virgule\Bundle\MainBundle\Entity\OrganizationBranch $organizationBranch
     * @return Semester
     */
    public function setOrganizationBranch(\Virgule\Bundle\MainBundle\Entity\OrganizationBranch $organizationBranch = null) {
        $this->organizationBranch = $organizationBranch;

        return $this;
    }

    /**
     * Get organizationBranch
     *
     * @return \Virgule\Bundle\MainBundle\Entity\OrganizationBranch 
     */
    public function getOrganizationBranch() {
        return $this->organizationBranch;
    }
    
    public function __toString() {
        return 'du ' . $this->getStartDate()->format('d/m/Y') . ' au ' . $this->getEndDate()->format('d/m/Y');
    }
    
   /**
     * Add courses
     *
     * @param \Virgule\Bundle\MainBundle\Entity\Course $courses
     * @return Semester
     */
    public function addOpenHouse(\Virgule\Bundle\MainBundle\Entity\OpenHouse $openHouse) {
        $this->openHouses[] = $openHouse;

        return $this;
    }
    
    /**
     * 
     * @return ArrayCollection OpenHouse
     */
    public function getOpenHouses() {
        return $this->openHouses;
    }
}