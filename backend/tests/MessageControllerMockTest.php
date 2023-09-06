<?php

namespace App\Tests;

use App\Controller\MessageController;
use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Uid\Uuid;

class MessageControllerMockTest extends TestCase
{
    function testSave()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('persist');
        $em->expects($this->once())->method('flush');

        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn(json_encode(['message' => 'Test message']));

        $controller = new MessageController($em);
        $response = $controller->save($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $data);
        $this->assertNotNull($data['id']);
        $this->assertTrue(Uuid::isValid($data['id']));

    }

    public function testRead()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        $message = $this->createMock(Message::class);
        $message->method('getId')->willReturn('sample-uuid');
        $message->method('getText')->willReturn('sample text');
        $message->method('getTimestamp')->willReturn('2023-09-01 12:34:56.789');

        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 'sample-uuid'])
            ->willReturn($message);

        $em->expects($this->once())
            ->method('getRepository')
            ->with(Message::class)
            ->willReturn($repository);

        $controller = new MessageController($em);

        $response = $controller->read('sample-uuid');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('uuid', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('timestamp', $data);

        $this->assertEquals('sample-uuid', $data['uuid']);
        $this->assertEquals('sample text', $data['message']);
        $this->assertEquals('2023-09-01 12:34:56.789', $data['timestamp']);
    }
}
