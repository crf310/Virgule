<?php

namespace Virgule\Bundle\MainBundle\Manager;

use Doctrine\ORM\EntityManager;
use Virgule\Bundle\MainBundle\Manager\BaseManager;
use Virgule\Bundle\MainBundle\Entity\Student;

class StudentManager extends BaseManager {

  protected $em;

  public function __construct(EntityManager $em) {
    $this->em = $em;
  }

  public function getRepository() {
    return $this->em->getRepository('VirguleMainBundle:Student');
  }

  /**
   * Load all students
   * @return type ArrayCollection
   */
  public function loadAll() {
    $students = $this->getRepository()->loadAll();
    return $this->mergeStudentLines($students);
  }

  /**
   * Load all students enrolled in at least one class
   * @param type $semesterId
   * @return type
   */
  public function loadAllEnrolled($semesterId) {
    $students = $this->getRepository()->loadAllEnrolled($semesterId);
    return $this->mergeStudentLines($students);
  }

  /**
   * Load all students enrolled in more than one class
   * @param type $semesterId
   * @return type
   */
  public function loadAllEnrolledTwice($semesterId) {
    $students = $this->getRepository()->loadAllEnrolled($semesterId);
    $students_merged = $this->mergeStudentLines($students);
    return $this->removeStudentsWithOnlyWithEnrollment($students_merged['students_array'], $students_merged['courses_array']);
  }

  /**
   * Load all students not enrolled in any class of the current semester
   * @param type $semesterId
   * @return type
   */
  public function loadAllNotEnrolled($semesterId) {
    $courseRepository = $this->em->getRepository('VirguleMainBundle:Course');
    $coursesIds = $courseRepository->loadAllIdsForSemester($semesterId);

    $students = $this->getRepository()->loadNotEnrolledInCourses($coursesIds);

    return Array('students_array' => $students);
  }

  /**
   * Remove line in double (students with more than one course)
   * @param type $students
   */
  private function mergeStudentLines($students) {
    // array to keep students, with key == student_id
    $new_students = Array();
    // sub array to group students enrolled to many courses
    $students_ids = Array();
    $courses_array = Array();
    $i = 0;
    foreach ($students as $key => $student) {

      // store courses for each student
      if (!key_exists($student['student_id'], $courses_array)) {
        $courses_array[$student['student_id']] = Array();
      }
      if (!empty($student['course_id'])) {
        $courses_array[$student['student_id']][] = Array('course_id' => $student['course_id'], 'level' => $student['level'], 'levelColorCode' => $student['levelColorCode'], 'semester_id' => $student['semester_id']);
      }

      // only keep the line if the students has not been processed already
      if (!array_key_exists($student['student_id'], $students_ids)) {
        $new_students[$student['student_id']] = $student;
      }
      // set flag: we processed a line for this student
      $students_ids[$student['student_id']] = 1;
    }

    return array_merge(Array('students_array' => $new_students), Array('courses_array' => $courses_array));
  }

  private function removeStudentsWithOnlyWithEnrollment(Array $students, Array $students_courses) {
    foreach ($students_courses as $student_id => $courses) {
      if (count($courses) < 2) {
        unset($students[$student_id]);
      }
    }
    return array_merge(Array('students_array' => $students), Array('courses_array' => $students_courses));
  }

  public function getAttendanceRate(Array $courses, $studentId) {
    $courseIds = Array();
    foreach ($courses as $course) {
      $courseIds[] = $course->getId();
    }

    $courses = Array();
    if (count($courseIds) > 0) {
      $courseRepository = $this->em->getRepository('VirguleMainBundle:Course');
      $classSessions = $courseRepository->getNumberOfClassSessionsPerCourse($courseIds);
      $classSessionsAttended = $courseRepository->getNumberOfClassSessionsPerCourseAndStudent($courseIds, $studentId);

      foreach ($classSessions as $courseId => $classSession) {
        $courses[$courseId] = Array('nb_classsessions' => $classSession['nb_classsessions'], 'nb_classsessions_attended' => 0);
        if (array_key_exists($courseId, $classSessionsAttended)) {
          $courses[$courseId]['nb_classsessions_attended'] = $classSessionsAttended[$courseId]['nb_classsessions'];
        }
      }
    }
    return $courses;
  }

  /**
   * Get number of new student newly registered for the semester
   */
  public function getNumberOfNewStudents($semester) {
    $dates = Array();
    $dates[] = $semester->getStartDate();
    foreach ($semester->getOpenHouses() as $openHouse) {
      $dates[] = $openHouse->getDate();
    }
    return $this->getRepository()->getNumberOfStudentRegisteredAfterDates($dates);
  }

  public function searchStudent($name) {
    $students = $this->getRepository()->search($name);
    $students_merged = $this->mergeStudentLines($students);
    return $students_merged;
  }

}

?>
