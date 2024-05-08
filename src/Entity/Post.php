<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post", indexes={@ORM\Index(name="id_forum", columns={"id_forum"}), @ORM\Index(name="id_user", columns={"id_user"})})
 * @ORM\Entity
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_post", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPost;

    /**
     * @var int
     *
     * @ORM\Column(name="id_forum", type="integer", nullable=false)
     */
    private $idForum;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="TextMessage", type="string", length=255, nullable=false)
     */
    private $textmessage;

    /**
     * @var int
     *
     * @ORM\Column(name="like_number", type="integer", nullable=false)
     */
    private $likeNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="TimeofCreation", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $timeofcreation = 'CURRENT_TIMESTAMP';

    public function getIdPost(): ?int
    {
        return $this->idPost;
    }

    public function getIdForum(): ?int
    {
        return $this->idForum;
    }

    public function setIdForum(int $idForum): static
    {
        $this->idForum = $idForum;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTextmessage(): ?string
    {
        return $this->textmessage;
    }

    public function setTextmessage(string $textmessage): static
    {
        $this->textmessage = $textmessage;

        return $this;
    }

    public function getLikeNumber(): ?int
    {
        return $this->likeNumber;
    }

    public function setLikeNumber(int $likeNumber): static
    {
        $this->likeNumber = $likeNumber;

        return $this;
    }

    public function getTimeofcreation(): ?\DateTimeInterface
    {
        return $this->timeofcreation;
    }

    public function setTimeofcreation(\DateTimeInterface $timeofcreation): static
    {
        $this->timeofcreation = $timeofcreation;

        return $this;
    }


}
