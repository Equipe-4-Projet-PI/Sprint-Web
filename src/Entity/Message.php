<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message", indexes={@ORM\Index(name="idsender", columns={"idsender"}), @ORM\Index(name="iddis", columns={"iddis"})})
 * @ORM\Entity
 */
class Message
{
    /**
     * @var int
     *
     * @ORM\Column(name="idmsg", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idmsg;

    /**
     * @var int
     *
     * @ORM\Column(name="idsender", type="integer", nullable=false)
     */
    private $idsender;

    /**
     * @var int
     *
     * @ORM\Column(name="iddis", type="integer", nullable=false)
     */
    private $iddis;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255, nullable=false)
     */
    private $content;

    /**
     * @var string|null
     *
     * @ORM\Column(name="reaction", type="string", length=255, nullable=true)
     */
    private $reaction;

    /**
     * @var int|null
     *
     * @ORM\Column(name="vu", type="integer", nullable=true)
     */
    private $vu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datasent", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $datasent = 'CURRENT_TIMESTAMP';

    public function getIdmsg(): ?int
    {
        return $this->idmsg;
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

    public function getIddis(): ?int
    {
        return $this->iddis;
    }

    public function setIddis(int $iddis): static
    {
        $this->iddis = $iddis;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getReaction(): ?string
    {
        return $this->reaction;
    }

    public function setReaction(?string $reaction): static
    {
        $this->reaction = $reaction;

        return $this;
    }

    public function getVu(): ?int
    {
        return $this->vu;
    }

    public function setVu(?int $vu): static
    {
        $this->vu = $vu;

        return $this;
    }

    public function getDatasent(): ?\DateTimeInterface
    {
        return $this->datasent;
    }

    public function setDatasent(\DateTimeInterface $datasent): static
    {
        $this->datasent = $datasent;

        return $this;
    }


}
