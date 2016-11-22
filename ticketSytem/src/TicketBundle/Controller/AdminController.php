<?php

namespace TicketBundle\Controller;

use Symfony\Component\Validator\Constraints\Date;
use TicketBundle\Entity\Answer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Admin controller.
 *
 * @Route("admin")
 */
class AdminController extends Controller
{

    public function indexAction()
    {
        $tickets = $this->getDoctrine()
            ->getRepository('TicketBundle:Ticket')
            ->findIdsAndTitles();

        $users = $this->getDoctrine()
            ->getRepository('TicketBundle:User')
            ->findIdsAndNames();

        /*return $this->render('admin/assign.html.twig', array(
            'users' => $users,
            'tickets' => $tickets
        ));*/
    }

    /**
     * Creates a new answer entity.
     *
     * @Route("/assign", name="assign_index")
     * @Method({"GET", "POST"})
     */
    public function assignAction(Request $request, $ticket_id, $users_id) {

        $tickets = $this->getDoctrine()
            ->getRepository('TicketBundle:Ticket')
            ->findIdsAndTitles();

        $users = $this->getDoctrine()
            ->getRepository('TicketBundle:User')
            ->findIdsAndNames();

        if ($request->isMethod('POST')) {
            $ticket_name = $request->get('ticket');
            $users_array = $request->get('users');

            $ticket = $this->getDoctrine()
                ->getRepository('TicketBundle:Ticket')
                ->findById($ticket_name);

            $selected_users = $this->getDoctrine()
                ->getRepository('TicketBundle:User')
                ->findUsersById($users_array);

            $em = $this->getDoctrine()->getManager();

            for($i = 0; $i < count($selected_users);$i++) {
                $ticket[0]->addUser($selected_users[$i]);
                $em->persist($ticket[0]);
            }
            $em->flush();
        }



        return $this->render('admin/assign.html.twig', [
            'tickets' => $tickets,
            'users' => $users
        ]);
    }
}
