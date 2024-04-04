<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DiscussionRepository ;

#[ORM\Entity(repositoryClass: DiscussionRepository::class)]
class Discussion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $iddis=null;
    
    #[ORM\Column]
    private ?int $idreciever=null;

    #[ORM\Column]
    private ?int $idsender=null;

    #[ORM\Column]
    private ?string $sig=null;

    public function getIddis(): ?int
    {
        return $this->iddis;
    }

    public function getIdreciever(): ?int
    {
        return $this->idreciever;
    }

    public function setIdreciever(int $idreciever): static
    {
        $this->idreciever = $idreciever;

        return $this;
    }

    public function getIdsender(): ?int
    {
        return $this->idsender;
    }

    public function setIdsender(int $idsender): static
    {
        $this->idsender = $idsender;

        return $this;
    }

    public function getSig(): ?string
    {
        return $this->sig;
    }

    public function setSig(?string $sig): static
    {
        $this->sig = $sig;

        return $this;
    }


}
