<?php

namespace App\Controller\Api;

use App\Entity\Reservation;
use App\Repository\StationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ReservationController extends AbstractController
{
    public function __construct(
        private StationRepository $stationRepository,
        private EntityManagerInterface $em,
    ) {}

    #[Route('/stations/{id}/reserve', methods: ['POST'])]
    public function reserve(int $id, Request $request): JsonResponse
    {
        $station = $this->stationRepository->find($id);

        if (!$station) {
            return $this->json(['error' => 'Station introuvable'], 404);
        }

        // Trouver un vélo disponible dans cette station
        $availableBike = null;
        foreach ($station->getBikes() as $bike) {
            if ($bike->getStatus() === 'available') {
                $availableBike = $bike;
                break;
            }
        }

        if (!$availableBike) {
            return $this->json(['error' => 'Aucun vélo disponible dans cette station'], 400);
        }

        $body = json_decode($request->getContent(), true);
        $userName = $body['userName'] ?? 'Anonyme';

        // Réserver le vélo
        $availableBike->setStatus('reserved');

        $reservation = new Reservation();
        $reservation->setBike($availableBike);
        $reservation->setStation($station);
        $reservation->setUserName($userName);

        $this->em->persist($reservation);
        $this->em->flush();

        return $this->json([
            'success'        => true,
            'reservationId'  => $reservation->getId(),
            'stationName'    => $station->getName(),
            'availableBikes' => $station->getAvailableBikes(),
            'message'        => 'Vélo réservé avec succès !',
        ], 201);
    }

    #[Route('/reservations/{id}/pay', methods: ['POST'])]
    public function pay(int $id): JsonResponse
    {
        $reservation = $this->em->find(Reservation::class, $id);

        if (!$reservation) {
            return $this->json(['error' => 'Réservation introuvable'], 404);
        }

        // Simulation du paiement (toujours succès)
        $reservation->setPaymentStatus('paid');
        $this->em->flush();

        return $this->json([
            'success' => true,
            'message' => 'Paiement validé ! Bonne balade 🚲',
        ]);
    }
}
