<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Volunteers;
use App\Entity\Events;
use App\Form\VolunteerFormType;
use Doctrine\ORM\EntityManagerInterface;

class EventsController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/evenements', name: 'app_events')]
    public function volunteerRegister(Request $request): Response
    {
        $email = null;
        $volunteerFormData = $request->request->get('volunteer_form');
        if ($volunteerFormData && array_key_exists('email', $volunteerFormData)) {
            $email = $volunteerFormData['email'];
        }

        // Récupérer le volontaire par son email s'il existe
        $volunteerRepository = $this->entityManager->getRepository(Volunteers::class);
        $volunteer = $volunteerRepository->findOneBy(['email' => $email]);

        // Si le volontaire n'existe pas, créer une nouvelle instance
        if (!$volunteer) {
            $volunteer = new Volunteers();
        }

        // Récupérer tous les événements
        $events = $this->entityManager->getRepository(Events::class)->findAll();

        // Créer le formulaire en utilisant VolunteerFormType et passer les événements
        $form = $this->createForm(VolunteerFormType::class, $volunteer, [
            'events' => $events
        ]);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        // Traiter le formulaire si soumis et valide
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
