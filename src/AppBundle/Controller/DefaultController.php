<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use AppBundle\Entity\Venue;
use AppBundle\Form\AddAttendeesType;
use AppBundle\Form\ChooseVenueType;
use AppBundle\Form\EventType;
use AppBundle\Service\BestVenueChoice;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->findAll();

        return $this->render('default/index.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * @Route("/create-event/", name="create_event")
     *
     * @param Request $request
     * @return Response
     */
    public function createEventAction(Request $request)
    {
        $form = $this->createForm(EventType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventData = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($eventData);
            $entityManager->flush();

            return $this->redirectToRoute('add_attendees', ['id' => $eventData->getId()]);
        }

        return $this->render('default/create_event.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/add-attendees/{id}/", name="add_attendees")
     *
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function addAttendeeAction(Request $request, Event $event)
    {
        $form = $this->createForm(AddAttendeesType::class);

        $form->handleRequest($request);

        $message = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $attendeesData = $data['users'];

            if (count($attendeesData) > 0) {
                $entityManager = $this->getDoctrine()->getManager();

                /* @var User $attendee */
                foreach ($attendeesData as $attendee) {
                    $attendee->addEvent($event);
                    $entityManager->persist($attendee);
                    $event->addUser($attendee);
                }

                $entityManager->persist($event);
                $entityManager->flush();

                return $this->redirectToRoute('choose_venue', ['id' => $event->getId()]);
            } else {
                $message .= 'Please select at least one attendee';
            }
        }

        return $this->render('default/add_attendees.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
            'message' => $message
        ]);
    }

    /**
     * @Route("/choose-venue/{id}/", name="choose_venue")
     *
     * @param Request $request
     * @param Event $event
     * @param BestVenueChoice $bestVenueChoice
     * @return Response
     */
    public function chooseVenueAction(Request $request, Event $event, BestVenueChoice $bestVenueChoice)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(ChooseVenueType::class);

        $form->handleRequest($request);

        $message = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $venueData = $form->getData();

            if (count($venueData) > 0) {
                /* @var Venue $venue*/
                foreach ($venueData as $venue) {
                    $venue->addEvent($event);
                    $entityManager->persist($venue);
                    $event->setVenue($venue);
                }

                $entityManager->persist($event);
                $entityManager->flush();

                return $this->redirectToRoute('confirm', ['id' => $event->getId()]);
            } else {
                $message .= 'Please select at least one venue.';
            }
        }

        $venues = new ArrayCollection($entityManager->getRepository('AppBundle:Venue')->findAll());

        $attendees = $event->getUsers();

        $summary = $bestVenueChoice->identify($venues, $attendees);

        return $this->render('default/choose_venue.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
            'message' => $message,
            'summary' => $summary
        ]);
    }

    /**
     * @Route("/confirm/{id}/", name="confirm")
     *
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function confirmAction(Request $request, Event $event)
    {
        return $this->render('default/confirm.html.twig', [
            'event' => $event
        ]);
    }
}
