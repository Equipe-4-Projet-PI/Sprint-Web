<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity
 */
class Event
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id_Event", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEvent;

    /**
     * @var string
     *
     * @ORM\Column(name="E_Name", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Assert\Regex("/^[a-zA-Z\s]+$/")
     */
    private $eName;

    /**
     * @var string
     *
     * @ORM\Column(name="Place", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $place;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="E_Date", type="date", nullable=false)
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual("today")
     */
    private $eDate;

    /**
     * @var float
     *
     * @ORM\Column(name="Ticket_Price", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotBlank
     */
    private $ticketPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=500, nullable=false)
     */
    private $image;

    public function getIdEvent(): ?int
    {
        return $this->idEvent;
    }

    public function getEName(): ?string
    {
        return $this->eName;
    }

    public function setEName(string $eName): static
    {
        $this->eName = $eName;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getEDate(): ?\DateTimeInterface
    {
        return $this->eDate;
    }

    public function setEDate(\DateTimeInterface $eDate): static
    {
        $this->eDate = $eDate;

        return $this;
    }

    public function getTicketPrice(): ?float
    {
        return $this->ticketPrice;
    }

    public function setTicketPrice(float $ticketPrice): static
    {
        $this->ticketPrice = $ticketPrice;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }


}
