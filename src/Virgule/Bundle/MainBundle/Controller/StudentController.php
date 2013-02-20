<?php

namespace Virgule\Bundle\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Virgule\Bundle\MainBundle\Controller\CommentController;
use Virgule\Bundle\MainBundle\Entity\Student;
use Virgule\Bundle\MainBundle\Entity\Comment;
use Virgule\Bundle\MainBundle\Form\StudentType;
use Virgule\Bundle\MainBundle\Form\CommentType;

/**
 * Student controller.
 *
 * @Route("/student")
 */
class StudentController extends AbstractVirguleController {

    /**
     * Lists all Student entities.
     *
     * @Route("/")
     * @Route("/page/{page}", requirements={"page" = "\d+"}, defaults={"page" = "1"}, name="student_index")
     * @Template()
     */
    public function indexAction($page=1) {
        $em = $this->getDoctrine()->getManager();

        $students = $em->getRepository('VirguleMainBundle:Student')->loadAll($this->getSelectedSemesterId());
    
        // sub array to group students enrolled to many courses
        $students_ids = Array();
        $courses_array = Array();
        foreach ($students as $key => $student) {
            
            // store courses for each student
            $courses_array[$student['id']][] = Array('course_id' => $student['course_id']);
            
            // delete doubled line in students results (if we already processed a line for the same student
            if (array_key_exists($student['id'], $students_ids)) {
                 unset($students[$key]);
            }
            // set flag: we processed a line for this student
            $students_ids[$student['id']] = 1;
        }
        $entities_paginated = $this->paginate($students, $page);
        $other_entities = Array('courses_array' => $courses_array);
        return array_merge($entities_paginated, $other_entities);
    }

    /**
     * Finds and displays a Student entity.
     *
     * @Route("/{id}/show", name="student_show")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VirguleMainBundle:Student')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Student entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        // coment form
        $comment = new Comment();
        $commentForm = $this->createForm(new CommentType(), $comment);
        
        $courses = $em->getRepository('VirguleMainBundle:Course')->getCoursesByStudent($id);
        
        $nbEnrollment = count($courses);
        if ($nbEnrollment > 0) {
           $previousSemester = $courses[0]->getSemester()->getId();
        }
        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
            'commentForm' => $commentForm->createView(),
            'courses' => $courses,
            'nbEnrollment' => $nbEnrollment,
            'previousSemester' => $previousSemester
        );
    }

    /**
     * Displays a form to create a new Student entity.
     *
     * @Route("/new", name="student_new")
     * @Template()
     */
    public function newAction() {
        $entity = new Student();
        $form = $this->createForm(new StudentType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Student entity.
     *
     * @Route("/create", name="student_create")
     * @Method("POST")
     * @Template("VirguleMainBundle:Student:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new Student();
        $form = $this->createForm(new StudentType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('student_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Student entity.
     *
     * @Route("/{id}/edit", name="student_edit")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VirguleMainBundle:Student')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Student entity.');
        }

        $editForm = $this->createForm(new StudentType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Student entity.
     *
     * @Route("/{id}/update", name="student_update")
     * @Method("POST")
     * @Template("VirguleMainBundle:Student:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VirguleMainBundle:Student')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Student entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new StudentType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('student_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Student entity.
     *
     * @Route("/{id}/delete", name="student_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VirguleMainBundle:Student')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Student entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('student'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }
}
