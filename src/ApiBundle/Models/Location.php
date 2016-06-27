<?php

namespace ApiBundle\Models;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Location model.
 *
 * @author    Piotr Żuralski <piotr@zuralski.net>
 * @copyright 2016 Piotr Żuralski
 * @package ApiBundle\Models
 * @since     2016-06-24
 */
class Location
{
    /**
     * Latitude
     *
     * @Assert\NotBlank()
     * @var float
     */
    protected $lat;

    /**
     * Longitude
     *
     * @Assert\NotBlank()
     * @var float
     */
    protected $lng;

    /**
     * Constructor.
     *
     * @param float $lat Latitude
     * @param float $lng Longitude
     */
    public function __construct($lat, $lng)
    {
        $this->lat = $lat;
        $this->lng = $lng;
    }

    /**
     * Returns Latitude
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Returns Longitude
     *
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

}