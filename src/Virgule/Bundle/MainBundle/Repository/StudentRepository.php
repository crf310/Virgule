<?php

namespace Virgule\Bundle\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;

/**
 * StudentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StudentRepository extends EntityRepository {

    private function createDefaultQueryBuilder() {
        return $this->createQueryBuilder('s')->add('orderBy', 's.lastname ASC, s.firstname ASC');
    }
    
    private function getBasicQueryBuilder() {
        $qb = $this
            ->createDefaultQueryBuilder()
            ->select('s.id, s.firstname as firstname, s.lastname as lastname, s.gender as gender, s.phoneNumber as phoneNumber, s.cellphoneNumber, s.registrationDate')
            ->addSelect('c.isoCode, c.label')
            ->innerJoin('s.nativeCountry', 'c');
        return $qb;
    }
    
    public function getQueryBuilderForStudentEnrolledInCourses(Array $courseIds) {
        $ids = implode(',', $courseIds);
        $qb = $this->getBasicQueryBuilder()
            ->innerJoin('s.courses', 'c2', 'WITH', 'c2.id IN (:coursesIds)')
            ->setParameter('coursesIds', $ids);
        return $qb;
    }
    
    /**
     * Select all students enrolled in a class of the selected semester
     * @return type
     */
    public function loadAll($semesterId) {
        $qb = $this->getBasicQueryBuilder()
                ->addSelect('t.id as teacher_id, t.firstName as teacher_firstName, t.lastName as teacher_lastName')
                ->addSelect('c2.id as course_id, l.label as level')
                ->addSelect('count(cm.id) as nb_comments')
                ->innerJoin('s.courses', 'c2')
                ->innerJoin('c2.classLevel', 'l')
                ->leftJoin('s.welcomedByTeacher', 't')
                ->leftJoin('s.comments', 'cm')
                ->where('c2.semester = :semesterId')
                ->add('orderBy', 's.lastname ASC, s.firstname ASC')
                ->add('groupBy', 's.id')
                ->setParameter('semesterId', $semesterId);
        
        $q = $qb->getQuery();
        $students = $q->execute(array(), Query::HYDRATE_ARRAY);
        return $students;
    }

    public function loadAllEnrolledInCourses(Array $courseIds) {
        $qb = $this->getQueryBuilderForStudentEnrolledInCourses($courseIds);
        $qb->addSelect('count(cm.id) as nb_comments')
            ->leftJoin('s.comments', 'cm')
            ->add('groupBy', 's.id');
        return $qb->getQuery()->execute(array(), Query::HYDRATE_ARRAY);
    }
    
    /**
     * 
     * @param array $courseIds
     * @return type
     */
    public function loadAllEnrolledInCourse($courseId) {
        return $this->loadAllEnrolledInCourses(Array($courseId));
    }

    public function loadAllPresentAtClassSession($classSessionId) {
        $qb = $this->getBasicQueryBuilder()
                ->innerJoin('s.classSessions', 'cs')
                ->where('cs.id = :classSessionId')
                ->setParameter('classSessionId', $classSessionId)
                ->addSelect('count(cm.id) as nb_comments')
                ->leftJoin('s.comments', 'cm')
                ->add('groupBy', 's.id');
        
        return $q->getQuery()->execute(array(), Query::HYDRATE_ARRAY);
    }
    
    public function getStudentsInformation($semesterId) {
        $q = $this
                ->createDefaultQueryBuilder()
                ->addSelect('s.id as student_id, s.gender as student_gender')
                ->addSelect('c1.isoCode as country_code, c1.label as country_label')
                ->innerJoin('s.nativeCountry', 'c1')
                ->innerJoin('s.courses', 'c2')
                ->where('c2.semester = :semesterId')
                ->setParameter('semesterId', $semesterId)
                ->distinct()
                ->getQuery()
        ;
        $students = $q->execute(array(), Query::HYDRATE_ARRAY);
        return $students;
    }
}