<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product", indexes={@ORM\Index(name="Id_User", columns={"Id_User"})})
 * @ORM\Entity
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id_Product", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProduct;

    /**
     * @var int
     *
     * @ORM\Column(name="Id_User", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="Title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="ForSale", type="boolean", nullable=false)
     */
    private $forsale;

    /**
     * @var float
     *
     * @ORM\Column(name="Price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="CreationDate", type="string", length=255, nullable=false)
     */
    private $creationdate;

    /**
     * @var string
     *
     * @ORM\Column(name="ProductImage", type="string", length=255, nullable=false)
     */
    private $productimage;

    public function getIdProduct(): ?int
    {
        return $this->idProduct;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isForsale(): ?bool
    {
        return $this->forsale;
    }

    public function setForsale(bool $forsale): static
    {
        $this->forsale = $forsale;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCreationdate(): ?string
    {
        return $this->creationdate;
    }

    public function setCreationdate(string $creationdate): static
    {
        $this->creationdate = $creationdate;

        return $this;
    }

    public function getProductimage(): ?string
    {
        return $this->productimage;
    }

    public function setProductimage(string $productimage): static
    {
        $this->productimage = $productimage;

        return $this;
    }


}
