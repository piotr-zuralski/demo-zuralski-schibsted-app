<?php

namespace ApiBundle\Models;

use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Place model
 *
 * @Hateoas\Relation("self", href=@Hateoas\Route(name="api_place_placesdetails", parameters={"id" = "expr(object.getId())"}))
 *
 * @author    Piotr Żuralski <piotr@zuralski.net>
 * @copyright 2016 Piotr Żuralski
 * @package   ApiBundle\Models
 * @since     2016-06-24
 */
class Place
{
    /**
     * PlaceId
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=27)
     * @var string
     */
    protected $id;

    /**
     * Name
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @var string
     */
    protected $name;

    /**
     * Location
     *
     * @var Location
     */
    protected $location;

    /**
     * Address
     *
     * @Assert\Type(type="string")
     * @var string
     */
    protected $address;

    /**
     * Place constructor.
     *
     * @param string   $id       PlaceId
     * @param string   $name     Place name
     * @param Location $location Location
     * @param string   $address  [OPTIONAL] Address
     */
    public function __construct($id, $name, Location $location, $address = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
        $this->address = $address;
    }

    /**
     * Returns PlaceId
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns location
     *
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Returns address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

}