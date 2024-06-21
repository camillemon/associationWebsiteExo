<?php
// src/Controller/EventsController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Volunteers;
use App\Entity\Events;
use App\Form\VolunteerFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

class EventsController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/evenements', name: 'app_events')]
    public function volunteerRegister(Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        try {
            dump('Start of method');

            $email = null;
            $volunteerFormData = $request->request->get('volunteer_form');
            dump('Form data from request', $volunteerFormData);

            if ($volunteerFormData && array_key_exists('email', $volunteerFormData)) {
                $email = $volunteerFormData['email'];
            }

            dump('Email extracted', $email);

            $volunteerRepository = $this->entityManager->getRepository(Volunteers::class);
            $volunteer = $volunteerRepository->findOneBy(['email' => $email]);

            dump('Volunteer found or new instance', $volunteer);

            if (!$volunteer) {
                $volunteer = new Volunteers();
            }

            dump('Volunteer after possible creation', $volunteer);

            $events = $this->entityManager->getRepository(Events::class)->findAll();
            dump('Events fetched', $events);

            $eventsChoices = [];
            foreach ($events as $event) {
                $eventsChoices[$event->getLocation() . ' - ' . $event->getDate()->format('d-m-Y')] = $event;
            }

            dump('Events choices', $eventsChoices);

            $form = $this->createForm(VolunteerFormType::class, $volunteer, [
                'events' => $eventsChoices
            ]);

            dump('Form created', $form);

            // Vérification explicite des données avant handleRequest
            if (is_array($volunteerFormData)) {
                foreach ($volunteerFormData as $key => $value) {
                    if (is_array($value)) {
                        dump("Non-scalar value found in volunteer_form: $key");
                    }
                }
            } else {
                dump("volunteerFormData is not an array");
            }

            $form->handleRequest($request);

            dump('After handleRequest');
            dump('Request data', $request->request->all());
            dump('Form data', $form->getData());

            if ($form->isSubmitted()) {
                dump('Form is submitted');

                $token = new CsrfToken('volunteer_form', $request->request->get('_token'));
                if (!$csrfTokenManager->isTokenValid($token)) {
                    throw new BadRequestHttpException('Invalid CSRF token');
                }

                $data = $request->request->all();
                dump('Submitted data', $data);

                if ($form->isValid()) {
                    dump('Form is valid');

                    $this->entityManager->persist($volunteer);
                    $this->entityManager->flush();

                    return $this->redirectToRoute('app_home');
                } else {
                    dump('Form is not valid');
                    $errors = $form->getErrors(true);
                    foreach ($errors as $error) {
                        dump('Form error', $error->getMessage());
                    }
                    throw new BadRequestHttpException('Invalid form submission');
                }
            }

            return $this->render('events/index.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $e) {
            dump('Exception caught', $e->getMessage());
            throw $e;
        }
    }
}
