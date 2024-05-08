<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * AuctionParticipant
 *
 * @ORM\Table(name="auction_participant", indexes={@ORM\Index(name="Id_Auction", columns={"Id_Auction"}), @ORM\Index(name="Id_Participant", columns={"Id_Participant"})})
 * @ORM\Entity
 */
class AuctionParticipant
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=true)
     */
    private $prix;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $date = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @ORM\Column(name="Love", type="integer", nullable=false)
     */
    private $love = '0';

    /**
     * @var \Auction
     *
     * @ORM\ManyToOne(targetEntity="Auction")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Auction", referencedColumnName="id")
     * })
     */
    private $idAuction;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Participant", referencedColumnName="Id_User")
     * })
     */
    private $idParticipant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getLove(): ?int
    {
        return $this->love;
    }

    public function setLove(int $love): static
    {
        $this->love = $love;

        return $this;
    }

    public function getIdAuction(): ?Auction
    {
        return $this->idAuction;
    }

    public function setIdAuction(?Auction $idAuction): static
    {
        $this->idAuction = $idAuction;

        return $this;
    }

    public function getIdParticipant(): ?User
    {
        return $this->idParticipant;
    }

    public function setIdParticipant(?User $idParticipant): static
    {
        $this->idParticipant = $idParticipant;

        return $this;
    }


}
