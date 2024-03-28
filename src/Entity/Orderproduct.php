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


}
