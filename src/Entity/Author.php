<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: AuthorBook::class)]
    private Collection $authorBooks;

    public function __construct()
    {
        $this->authorBooks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return Collection<int, AuthorBook>
     */
    public function getAuthorBooks(): Collection
    {
        return $this->authorBooks;
    }

    public function addAuthorBook(AuthorBook $authorBook): self
    {
        if (!$this->authorBooks->contains($authorBook)) {
            $this->authorBooks->add($authorBook);
            $authorBook->setAuthor($this);
        }

        return $this;
    }

    public function removeAuthorBook(AuthorBook $authorBook): self
    {
        if ($this->authorBooks->removeElement($authorBook)) {
            // set the owning side to null (unless already changed)
            if ($authorBook->getAuthor() === $this) {
                $authorBook->setAuthor(null);
            }
        }

        return $this;
    }
}
