<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Book::class)]
    private Collection $books;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: BookAd::class)]
    private Collection $bookAds;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->bookAds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setCategory($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getCategory() === $this) {
                $book->setCategory(null);
            }
        }

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
            $bookAd->setCategory($this);
        }

        return $this;
    }

    public function removeBookAd(BookAd $bookAd): self
    {
        if ($this->bookAds->removeElement($bookAd)) {
            // set the owning side to null (unless already changed)
            if ($bookAd->getCategory() === $this) {
                $bookAd->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
