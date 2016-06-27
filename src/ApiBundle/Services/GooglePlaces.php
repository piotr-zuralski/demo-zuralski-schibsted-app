<?php

namespace ApiBundle\Services;

use RuntimeException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use ApiBundle\Models\Place;
use ApiBundle\Models\Location;

/**
 * Google Places service
 *
 * @author    Piotr Żuralski <piotr.zuralski@gmail.com>
 * @copyright 2016 Piotr Żuralski
 * @since     2016-06-24
 */
class GooglePlaces
{

    const TYPE_BAR = 'bar';

    const RESPONSE_TYPE_JSON = 'json';

    const ENDPOINT_NEARBY_SEARCH = 'nearbysearch';
    const ENDPOINT_DETAILS = 'details';

    /**
     * Google Places API key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * HTTP Client
     *
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * GooglePlaces constructor.
     *
     * @param                 $apiKey
     * @param ClientInterface $httpClient
     */
    public function __construct($apiKey, ClientInterface $httpClient)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = $httpClient;
    }


    /**
     * Lists places nearby given GEO coordinates
     *
     * @param Location $location GEO coordinates around which to retrieve places
     * @param integer  $radius  Distance in meters within place results
     * @param string   $q       [OPTIONAL]
     *
     * @return array
     */
    public function placeList(Location $location, $radius, $q = '')
    {
        $requestData = [
            'location' => sprintf('%s,%s', $location->getLat(), $location->getLng()),
            'radius' => $radius,
            'types' => self::TYPE_BAR,
            'key' => $this->apiKey,
            'language' => 'en-GB',
        ];

        if (!empty($q)) {
            $requestData['name'] = $q;
        }

        $uri = sprintf('%s/%s', self::ENDPOINT_NEARBY_SEARCH, self::RESPONSE_TYPE_JSON);
        $response = $this->httpClient->request('GET', $uri, [RequestOptions::QUERY => $requestData]);

        $responseBodyJson = json_decode($response->getBody()->getContents());
        if (!empty($responseBodyJson->error_message)) {
            throw new RuntimeException($responseBodyJson->error_message);
        }

        if (!empty($responseBodyJson->results)) {
            $result = [];
            foreach ($responseBodyJson->results as $place) {
                $result[] = new Place(
                    $place->place_id,
                    $place->name,
                    (new Location($place->geometry->location->lat, $place->geometry->location->lng)),
                    $place->vicinity
                );
            }
            return $result;
        }

        throw new RuntimeException('Error in communication with API');
    }

    /**
     * Returns place details
     *
     * @param string $placeId PlaceId
     *
     * @return Place|null
     */
    public function placeDetails($placeId)
    {
        $requestData = [
            'placeid' => $placeId,
            'key' => $this->apiKey,
            'language' => 'en-GB',
        ];

        $uri = sprintf('%s/%s', self::ENDPOINT_DETAILS, self::RESPONSE_TYPE_JSON);
        $response = $this->httpClient->request('GET', $uri, [RequestOptions::QUERY => $requestData]);

        $responseBodyJson = json_decode($response->getBody()->getContents());
        if (!empty($responseBodyJson->error_message)) {
            throw new RuntimeException($responseBodyJson->error_message);
        }

        $result = null;
        if (!empty($responseBodyJson->result)) {

            $result = new Place(
                $responseBodyJson->result->place_id,
                $responseBodyJson->result->name,
                (new Location($responseBodyJson->result->geometry->location->lat, $responseBodyJson->result->geometry->location->lng)),
                $responseBodyJson->result->formatted_address
            );
            return $result;
        }

        throw new RuntimeException('Error in communication with API');
    }

}