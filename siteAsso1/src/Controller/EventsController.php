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
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

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
        // Récupérer tous les événements
        $events = $this->entityManager->getRepository(Events::class)->findAll();

        // Créer un tableau d'options pour le champ 'events'
        $eventsChoices = [];
        foreach ($events as $event) {
            $eventsChoices[$event->getLocation() . ' - ' . $event->getDate()->format('d-m-Y')] = $event;
        }

        // Créer une nouvelle instance de Volunteer
        $volunteer = new Volunteers();

        // Créer le formulaire en utilisant VolunteerFormType et passer les événements
        $form = $this->createForm(VolunteerFormType::class, $volunteer, [
            'events' => $eventsChoices
        ]);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persister le volontaire dans la base de données
            $this->entityManager->persist($volunteer);
            $this->entityManager->flush();

            // Rediriger vers une page de succès (page d'accueil pour le moment)
            return $this->redirectToRoute('app_home');
        }

        // Afficher le formulaire d'inscription
        return $this->render('events/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

