<?php

namespace Virgule\Bundle\MainBundle\Manager;

use Doctrine\ORM\EntityManager;
use Virgule\Bundle\MainBundle\Manager\BaseManager;
use \Virgule\Bundle\MainBundle\Entity\Course;

class CourseManager extends BaseManager {

    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function getRepository() {
        return $this->em->getRepository('VirguleMainBundle:Course');
    }

    public function getNumberOfOverlapingCourses(Course $course) {

        $semesterId = $course->getSemester()->getId();
        $dayOfWeek = $course->getDayOfWeek();
        $classRoomId = $course->getClassRoom()->getId();
        $startTime = $course->getStartTime();
        $endTime = $course->getEndTime();
        
        $nbCourses = $this->getRepository()->getNumberOfOverlapingCourses($semesterId, $dayOfWeek, $classRoomId, $startTime, $endTime);
        return $nbCourses;
    }

}

?>