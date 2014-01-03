<?php

namespace Virgule\Bundle\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\DBAL\Connection;

/**
 * ClassSessionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClassSessionRepository extends EntityRepository {
    /* Query builders */
    private function getDefaultQueryBuilder() {
        return $this->createQueryBuilder('cs');
    }
    
    private function getBasicQueryBuilder($semesterId = null, $limit = null) {
         $qb = $this->getDefaultQueryBuilder()
            ->addSelect('cs.id as id, cs.reportDate, cs.sessionDate, cs.summary as summary')
            ->addSelect('c2.id as course_id, c2.dayOfWeek as course_dayOfWeek,
                c2.startTime as course_startTime, c2.endTime as course_endTime')
            ->addSelect('t1.id as sessionTeacher_id, t1.firstName as sessionTeacher_firstName, t1.lastName as sessionTeacher_lastName')
            ->addSelect('t2.id as reportTeacher_id, t2.firstName as reportTeacher_firstName, t2.lastName as reportTeacher_lastName')
            ->addSelect('cl.label as classLevelLabel, cl.htmlColorCode as classLevelHtmlColorCode')
            ->innerJoin('cs.course', 'c2')
            ->innerJoin('c2.classLevel', 'cl')
            ->innerJoin('c2.semester', 's')
            ->innerJoin('cs.sessionTeacher', 't1')
            ->innerJoin('cs.reportTeacher', 't2')
            ->add('orderBy', 'cs.reportDate DESC')
            ->add('groupBy', 'cs.id');
        
        if (null !== $semesterId) {
            $qb->where('s.id = :semesterId')
            ->setParameter('semesterId', $semesterId);
        }
        if ($limit != null) {
            $qb->setMaxResults($limit);
        }
         return $qb;
    }
    
    private function getNbStudentsQueryBuilder($semesterId, $limit = null)  {
        $qb = $this->getBasicQueryBuilder($semesterId, $limit) 
                ->leftJoin('cs.classSessionStudents', 'st')    
                ->leftJoin('cs.nonEnrolledClassSessionStudents', 'st_no')
                ->addSelect('count(distinct st.id) + count(distinct st_no.id) as nb_students');
        return $qb;
    }
    
    private function getNbCommentsQueryBuilder($semesterId, $limit = null) {
        $qb = $this->getBasicQueryBuilder($semesterId, $limit)
                ->leftJoin('cs.comments', 'cm')
                ->addSelect('count(cm.id) as nb_comments');
        return $qb;
    }   
    
    
    
    public function loadAllClassSessionByTeacher($semesterId, $teacherId, $limit = null) {
        $qb = $this->getNbCommentsQueryBuilder($semesterId, $limit)
            ->andwhere('t1.id = :teacherId')             
            ->setParameter('teacherId', $teacherId)
        ;        
        $q = $qb->getQuery();                
        $results = $q->execute(array(), Query::HYDRATE_ARRAY);
        return $results;   
    }
    
    public function loadAllClassSessionByClassLevel($classLevelId, $semesterId) {
        $qb = $this->getNbStudentsQueryBuilder($semesterId)
            ->andWhere('cl.id = :classLevelId')
            ->setParameter('classLevelId', $classLevelId)
        ;        
        $q = $qb->getQuery();                
        $results = $q->execute(array(), Query::HYDRATE_ARRAY);
        return $results;   
    }    
    
    public function loadAllClassSessionByCourse($courseId, $limit = null) {
        $qb = $this->getNbCommentsQueryBuilder(null, $limit)
            ->andWhere('c2.id = :courseId')   
            ->setParameter('courseId', $courseId)
        ;
        $q = $qb->getQuery();                
        $results = $q->execute(array(), Query::HYDRATE_ARRAY);
        return $results;   
    }
    
    public function loadAllClassSessionByDocument($documentId, $limit = null) {
        $qb = $this->getNbCommentsQueryBuilder(null, $limit)
            ->innerJoin('cs.documents', 'd')
            ->andWhere('d.id = :documentId')   
            ->setParameter('documentId', $documentId)
        ;
        $q = $qb->getQuery();                
        $results = $q->execute(array(), Query::HYDRATE_ARRAY);
        return $results;   
    }  
    
    public function loadAll($semesterId, $limit = null) {
        $qb = $this->getNbStudentsQueryBuilder($semesterId, $limit);
        $q = $qb->getQuery();
        $results = $q->execute(array(), Query::HYDRATE_ARRAY);
        return $results;  
    }
    
    public function loadAllForMiniList($semesterId, $limit = null) {
        $qb = $this->getNbCommentsQueryBuilder($semesterId, $limit);
        
        $q = $qb->getQuery();
        $results = $q->execute(array(), Query::HYDRATE_ARRAY);
        return $results;  
    }
    
    public function getNumberOfClassSessionsPerSemester($semesterId) {        
         $qb = $this->getDefaultQueryBuilder()
                 ->addSelect('count(cs.id) as nb_classsessions')
                 ->innerJoin('cs.course', 'c')
                 ->innerJoin('c.semester', 's')
                ->where('s.id = :semesterId')  
                ->setParameter('semesterId', $semesterId);
         
        $q = $qb->getQuery();
        $nbClassSessions = $q->getSingleResult();
        return $nbClassSessions;  
    }
    
    public function getNumberOfClassSessionsPerCourse(Array $courseIds) {        
         $qb = $this->getDefaultQueryBuilder()
                 ->addSelect('c.id as course_id, count(cs.id) as nb_classsessions')
                 ->innerJoin('cs.course', 'c', 'WITH', 'c.id IN (:coursesIds)')
                 ->setParameter('coursesIds', $courseIds, Connection::PARAM_INT_ARRAY)
                 ->groupBy('c.id');
         
        $q = $qb->getQuery();
        $results = $q->execute(array(), Query::HYDRATE_ARRAY);
        return $results;  
    }
    
    public function getNumberOfClassSessionsPerCourseAndStudent(Array $courseIds, $studentId) {        
         $qb = $this->getDefaultQueryBuilder()
                 ->addSelect('c.id as course_id, count(cs.id) as nb_classsessions')
                 ->innerJoin('cs.course', 'c', 'WITH', 'c.id IN (:coursesIds)')
                 ->innerJoin('cs.classSessionStudents', 's', 'WITH', 's.id = :studentId')
                 ->setParameter('coursesIds', $courseIds, Connection::PARAM_INT_ARRAY)
                 ->setParameter('studentId', $studentId)
                 ->groupBy('c.id');
         
        $q = $qb->getQuery();
        $results = $q->execute(array(), Query::HYDRATE_ARRAY);
        return $results;  
    }
}
