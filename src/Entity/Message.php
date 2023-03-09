<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    use TimestampableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Conversation $conversation = null;

    #[ORM\ManyToOne(inversedBy: 'messages_user_destination')]
    private ?User $user_destination = null;

    #[ORM\Column(nullable: true)]
    private ?bool $seenByUserDestination = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getUserDestination(): ?User
    {
        return $this->user_destination;
    }

    public function setUserDestination(?User $user_destination): self
    {
        $this->user_destination = $user_destination;

        return $this;
    }

    public function isSeenByUserDestination(): ?bool
    {
        return $this->seenByUserDestination;
    }

    public function setSeenByUserDestination(?bool $seenByUserDestination): self
    {
        $this->seenByUserDestination = $seenByUserDestination;

        return $this;
    }
}
