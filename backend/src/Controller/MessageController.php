<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\MessageRepository;
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

    #[Route('/api/message', name: 'api_message_save', methods: ['POST'])]
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

    #[Route('/api/message/{uuid}', name: 'api_message_read', methods: ['GET'])]
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
        ], Response::HTTP_OK);
    }

    #[Route('/api/messages', name: 'api_messages_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        // Domyślnie sortowanie po 'id'
        $sortBy = $request->query->get('sortBy', 'id');
        // Domyślnie sortowanie rosnąco
        $order = $request->query->get('order', 'ASC');

        /** @var MessageRepository $repo */
        $repo = $this->em->getRepository(Message::class);

        // Sortowanie
        $messages = $repo->findBy([], [$sortBy => $order]);

        $response = array_map(function (Message $message) {
            return [
                'uuid' => $message->getId(),
                'message' => $message->getText(),
                'timestamp' => $message->getTimestamp(),
            ];
        }, $messages);

        return new JsonResponse($response, Response::HTTP_OK);
    }

}