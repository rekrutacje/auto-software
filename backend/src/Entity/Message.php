<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID)]
    private string $id;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    #[ORM\Column(type: Types::TEXT)]
    private string $text;

    // Ta definicja zmiennej $timestamp uwzględnia używanie Sqlite3
    #[ORM\Column(type: Types::STRING, length: 26)]
    private string $timestamp;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    public function setTimestamp(string $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function setTimestampFromDateTime(DateTimeInterface $dateTime): self
    {
        $this->timestamp = $dateTime->format('Y-m-d H:i:s.u');

        return $this;
    }

}
