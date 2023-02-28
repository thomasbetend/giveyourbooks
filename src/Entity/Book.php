<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $purchaseDate = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?State $state = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: BookAd::class)]
    private Collection $bookAds;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: AuthorBook::class)]
    private Collection $authorBooks;

    public function __construct()
    {
        $this->bookAds = new ArrayCollection();
        $this->authorBooks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPurchaseDate(): ?\DateTimeInterface
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate(?\DateTimeInterface $purchaseDate): self
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

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

    /**
     * @return Collection<int, BookAd>
     */
    public function getBookAds(): Collection
    {
        return $this->bookAds;
    }

    public function addBookAd(BookAd $bookAd): self
    {
        if (!$this->bookAds->contains($bookAd)) {
            $this->bookAds->add($bookAd);
            $bookAd->setBook($this);
        }

        return $this;
    }

    public function removeBookAd(BookAd $bookAd): self
    {
        if ($this->bookAds->removeElement($bookAd)) {
            // set the owning side to null (unless already changed)
            if ($bookAd->getBook() === $this) {
                $bookAd->setBook(null);
            }
        }

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
            $authorBook->setBook($this);
        }

        return $this;
    }

    public function removeAuthorBook(AuthorBook $authorBook): self
    {
        if ($this->authorBooks->removeElement($authorBook)) {
            // set the owning side to null (unless already changed)
            if ($authorBook->getBook() === $this) {
                $authorBook->setBook(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

}
