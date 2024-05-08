<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orderproduct
 *
 * @ORM\Table(name="orderproduct", indexes={@ORM\Index(name="Id_Product", columns={"Id_Product"}), @ORM\Index(name="Id_Product_2", columns={"Id_Product"})})
 * @ORM\Entity
 */
class Orderproduct
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id_Order", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOrder;

    /**
     * @var float
     *
     * @ORM\Column(name="Price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="Title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="OrderDate", type="string", length=255, nullable=false)
     */
    private $orderdate;

    /**
     * @var string
     *
     * @ORM\Column(name="Prod_img", type="string", length=255, nullable=false)
     */
    private $prodImg;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Product", referencedColumnName="Id_Product")
     * })
     */
    private $idProduct;

    public function getIdOrder(): ?int
    {
        return $this->idOrder;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getOrderdate(): ?string
    {
        return $this->orderdate;
    }

    public function setOrderdate(string $orderdate): static
    {
        $this->orderdate = $orderdate;

        return $this;
    }

    public function getProdImg(): ?string
    {
        return $this->prodImg;
    }

    public function setProdImg(string $prodImg): static
    {
        $this->prodImg = $prodImg;

        return $this;
    }

    public function getIdProduct(): ?Product
    {
        return $this->idProduct;
    }

    public function setIdProduct(?Product $idProduct): static
    {
        $this->idProduct = $idProduct;

        return $this;
    }


}
