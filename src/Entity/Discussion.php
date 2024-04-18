<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DiscussionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: DiscussionRepository::class)]
class Discussion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $iddis;
    
    #[ORM\Column]
    private ?int $idreciever = null;

    #[ORM\Column]
    private ?int $idsender = null;

    #[ORM\Column]
    private ?string $sig = null;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="discussion")
     */
    private $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getIddis(): ?int
    {
        return $this->iddis;
    }

    public function getIdreciever(): ?int
    {
        return $this->idreciever;
    }

    public function setIdreciever(?int $idreciever): self
    {
        $this->idreciever = $idreciever;

        return $this;
    }

    public function getIdsender(): ?int
    {
        return $this->idsender;
    }

    public function setIdsender(?int $idsender): self
    {
        $this->idsender = $idsender;

        return $this;
    }

    public function getSig(): ?string
    {
        return $this->sig;
    }

    public function setSig(?string $sig): self
    {
        $this->sig = $sig;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }


}
