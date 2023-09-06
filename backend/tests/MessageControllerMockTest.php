<?php

namespace App\Tests;

use App\Controller\MessageController;
use Doctrine\ORM\EntityManagerInterface;
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
}
