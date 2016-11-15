<?php

namespace TicketBundle\Controller;

use Symfony\Component\Validator\Constraints\Date;
use TicketBundle\Entity\Answer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Answer controller.
 *
 * @Route("answer")
 */
class AnswerController extends Controller
{
    /**
     * Creates a new answer entity.
     *
     * @Route("/new/{ticket_id}", name="answer_new")
     * @Method({"GET", "POST"})
     */
    public function newAction($ticket_id, Request $request)
    {
        $ticket = $this->getDoctrine()
            ->getRepository('TicketBundle:Ticket')
            ->findOneById($ticket_id);

        $answer = new Answer();
        $answer->addUser($this->getUser());
        $form = $this->createForm('TicketBundle\Form\AnswerType', $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $answer->setTicket($ticket);
            $answer->setCreated(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($answer);
            $em->flush($answer);

            return $this->redirectToRoute('ticket_show', array('id' => $ticket_id));
        }

        return $this->render('answer/new.html.twig', array(
            'answer' => $answer,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing answer entity.
     *
     * @Route("/{id}/edit/{ticket_id}", name="answer_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Answer $answer, $ticket_id)
    {
        $deleteForm = $this->createDeleteForm($answer, $ticket_id);
        $editForm = $this->createForm('TicketBundle\Form\AnswerType', $answer);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $answer->setUpdated(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ticket_show', array('id' => $ticket_id));
        }

        return $this->render('answer/edit.html.twig', array(
            'answer' => $answer,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a answer entity.
     *
     * @Route("/{id}/{ticket_id}", name="answer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Answer $answer, $ticket_id)
    {
        $form = $this->createDeleteForm($answer, $ticket_id);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($answer);
            $em->flush($answer);
        }

        return $this->redirectToRoute('ticket_show', array('id' => $ticket_id));
    }

    /**
     * Creates a form to delete a answer entity.
     *
     * @param Answer $answer The answer entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Answer $answer, $ticket_id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('answer_delete', array('id' => $answer->getId(), 'ticket_id' => $ticket_id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
