<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Auction
 *
 * @ORM\Table(name="auction")
 * @ORM\Entity
 */
class Auction
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_cloture", type="date", nullable=true)
     */
    private $dateCloture;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_lancement", type="date", nullable=true)
     */
    private $dateLancement;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prix_initial", type="float", precision=10, scale=0, nullable=true)
     */
    private $prixInitial;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prix_final", type="float", precision=10, scale=0, nullable=true)
     */
    private $prixFinal;

    /**
     * @var int
     *
     * @ORM\Column(name="id_produit", type="integer", nullable=false)
     */
    private $idProduit;

    /**
     * @var int
     *
     * @ORM\Column(name="id_artist", type="integer", nullable=false)
     */
    private $idArtist;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateCloture(): ?\DateTimeInterface
    {
        return $this->dateCloture;
    }

    public function setDateCloture(?\DateTimeInterface $dateCloture): static
    {
        $this->dateCloture = $dateCloture;

        return $this;
    }

    public function getDateLancement(): ?\DateTimeInterface
    {
        return $this->dateLancement;
    }

    public function setDateLancement(?\DateTimeInterface $dateLancement): static
    {
        $this->dateLancement = $dateLancement;

        return $this;
    }

    public function getPrixInitial(): ?float
    {
        return $this->prixInitial;
    }

    public function setPrixInitial(?float $prixInitial): static
    {
        $this->prixInitial = $prixInitial;

        return $this;
    }

    public function getPrixFinal(): ?float
    {
        return $this->prixFinal;
    }

    public function setPrixFinal(?float $prixFinal): static
    {
        $this->prixFinal = $prixFinal;

        return $this;
    }

    public function getIdProduit(): ?int
    {
        return $this->idProduit;
    }

    public function setIdProduit(int $idProduit): static
    {
        $this->idProduit = $idProduit;

        return $this;
    }

    public function getIdArtist(): ?int
    {
        return $this->idArtist;
    }

    public function setIdArtist(int $idArtist): static
    {
        $this->idArtist = $idArtist;

        return $this;
    }


}
