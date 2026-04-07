<?php

namespace App\Controller\Api;

use App\Repository\StationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/stations')]
class StationController extends AbstractController
{
    public function __construct(
        private StationRepository $stationRepository,
        private SerializerInterface $serializer,
    ) {}

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $stations = $this->stationRepository->findAll();

        $data = array_map(fn($station) => [
            'id'              => $station->getId(),
            'name'            => $station->getName(),
            'latitude'        => $station->getLatitude(),
            'longitude'       => $station->getLongitude(),
            'totalSlots'      => $station->getTotalSlots(),
            'availableBikes'  => $station->getAvailableBikes(),
        ], $stations);

        return $this->json($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $station = $this->stationRepository->find($id);

        if (!$station) {
            return $this->json(['error' => 'Station introuvable'], 404);
        }

        return $this->json([
            'id'             => $station->getId(),
            'name'           => $station->getName(),
            'latitude'       => $station->getLatitude(),
            'longitude'      => $station->getLongitude(),
            'totalSlots'     => $station->getTotalSlots(),
            'availableBikes' => $station->getAvailableBikes(),
        ]);
    }
}
