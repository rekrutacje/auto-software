<?php

namespace App\Controller;

use App\Entity\Message;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MessageController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/api/message', name: 'api_save', methods: ['POST'])]
    public function save(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $message = new Message();
        $message->setText($data['message'] ?? null);
        $message->setTimestampFromDateTime(new DateTimeImmutable());

        $this->em->persist($message);
        $this->em->flush();

        return new JsonResponse(['id' => $message->getId()], Response::HTTP_CREATED);
    }

    #[Route('/api/message/{uuid}', name: 'api_read', methods: ['GET'])]
    public function read(string $uuid): JsonResponse
    {
        $message = $this->em
            ->getRepository(Message::class)
            ->findOneBy(['id' => $uuid]);

        if (!$message) {
            return new JsonResponse(['error' => 'Message not found'], 404);
        }

        return new JsonResponse([
            'uuid' => $message->getId(),
            'message' => $message->getText(),
            'timestamp' => $message->getTimestamp(),
        ]);
    }

}