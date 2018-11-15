<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChannelRepository")
 */
class Channel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="channels")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="channel", orphanRemoval=true)
     * @ORM\OrderBy({"date" = "DESC"})
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LastView", mappedBy="channel")
     */
    private $lastViews;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->lastViews = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setChannel($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getChannel() === $this) {
                $message->setChannel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LastView[]
     */
    public function getLastViews(): Collection
    {
        return $this->lastViews;
    }

    public function addLastView(LastView $lastView): self
    {
        if (!$this->lastViews->contains($lastView)) {
            $this->lastViews[] = $lastView;
            $lastView->setChannel($this);
        }

        return $this;
    }

    public function removeLastView(LastView $lastView): self
    {
        if ($this->lastViews->contains($lastView)) {
            $this->lastViews->removeElement($lastView);
            // set the owning side to null (unless already changed)
            if ($lastView->getChannel() === $this) {
                $lastView->setChannel(null);
            }
        }

        return $this;
    }
}
