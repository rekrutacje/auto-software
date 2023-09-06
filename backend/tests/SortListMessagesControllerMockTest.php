<?php

namespace App\Tests;

use App\Controller\MessageController;
use App\Entity\Message;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SortListMessagesControllerMockTest extends TestCase
{

    public function testSortListByIdAsc()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        $repo = $this->createMock(MessageRepository::class);

        $message = $this->createMock(Message::class);
        $message->method('getId')->willReturn('sample-uuid');
        $message->method('getText')->willReturn('sample message');
        $message->method('getTimestamp')->willReturn('sample timestamp');
        $repo->method('findBy')->willReturn([$message]);

        $em->method('getRepository')->willReturn($repo);

        $controller = new MessageController($em);

        // Testujemy sortowanie po id rosnąco
        $request = new Request([], [], [], [], [], [], 'sortBy=id&order=ASC');

        $response = $controller->list($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertEquals('sample-uuid', $data[0]['uuid']);
        $this->assertEquals('sample message', $data[0]['message']);
        $this->assertEquals('sample timestamp', $data[0]['timestamp']);
    }

    public function testSortListByIdDesc()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        $repo = $this->createMock(MessageRepository::class);

        $message = $this->createMock(Message::class);
        $message->method('getId')->willReturn('sample-uuid');
        $message->method('getText')->willReturn('sample message');
        $message->method('getTimestamp')->willReturn('sample timestamp');
        $repo->method('findBy')->willReturn([$message]);

        $em->method('getRepository')->willReturn($repo);

        $controller = new MessageController($em);

        // Testujemy sortowanie po id malejąco
        $request = new Request([], [], [], [], [], [], 'sortBy=id&order=DESC');

        $response = $controller->list($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertEquals('sample-uuid', $data[0]['uuid']);
        $this->assertEquals('sample message', $data[0]['message']);
        $this->assertEquals('sample timestamp', $data[0]['timestamp']);
    }

    public function testSortListByTimestampAsc()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        $repo = $this->createMock(MessageRepository::class);

        $message1 = $this->createMock(Message::class);
        $message1->method('getId')->willReturn('sample-uuid-1');
        $message1->method('getText')->willReturn('sample message 1');
        $message1->method('getTimestamp')->willReturn('2021-01-01 00:00:00.000000');

        $message2 = $this->createMock(Message::class);
        $message2->method('getId')->willReturn('sample-uuid-2');
        $message2->method('getText')->willReturn('sample message 2');
        $message2->method('getTimestamp')->willReturn('2022-01-01 00:00:00.000000');

        $repo->method('findBy')->willReturn([$message1, $message2]);

        $em->method('getRepository')->willReturn($repo);

        $controller = new MessageController($em);

        // Testujemy sortowanie po timestamp rosnąco
        $request = new Request([], [], [], [], [], [], 'sortBy=timestamp&order=ASC');

        $response = $controller->list($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertIsArray($data);
        $this->assertCount(2, $data);
        $this->assertEquals('sample-uuid-1', $data[0]['uuid']);
        $this->assertEquals('sample message 1', $data[0]['message']);
        $this->assertEquals('2021-01-01 00:00:00.000000', $data[0]['timestamp']);

        $this->assertEquals('sample-uuid-2', $data[1]['uuid']);
        $this->assertEquals('sample message 2', $data[1]['message']);
        $this->assertEquals('2022-01-01 00:00:00.000000', $data[1]['timestamp']);
    }

    public function testSortListByTimestampDesc()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        $repo = $this->createMock(MessageRepository::class);

        $message1 = $this->createMock(Message::class);
        $message1->method('getId')->willReturn('sample-uuid-1');
        $message1->method('getText')->willReturn('sample message 1');
        $message1->method('getTimestamp')->willReturn('2021-01-01 00:00:00.000000');

        $message2 = $this->createMock(Message::class);
        $message2->method('getId')->willReturn('sample-uuid-2');
        $message2->method('getText')->willReturn('sample message 2');
        $message2->method('getTimestamp')->willReturn('2022-01-01 00:00:00.000000');

        $repo->method('findBy')->willReturn([$message2, $message1]); // Odwrotna kolejność

        $em->method('getRepository')->willReturn($repo);

        $controller = new MessageController($em);

        // Testujemy sortowanie po timestamp malejąco
        $request = new Request([], [], [], [], [], [], 'sortBy=timestamp&order=DESC');

        $response = $controller->list($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertIsArray($data);
        $this->assertCount(2, $data);
        $this->assertEquals('sample-uuid-2', $data[0]['uuid']);
        $this->assertEquals('sample message 2', $data[0]['message']);
        $this->assertEquals('2022-01-01 00:00:00.000000', $data[0]['timestamp']);

        $this->assertEquals('sample-uuid-1', $data[1]['uuid']);
        $this->assertEquals('sample message 1', $data[1]['message']);
        $this->assertEquals('2021-01-01 00:00:00.000000', $data[1]['timestamp']);
    }

}
