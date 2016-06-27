<?php

namespace ApiBundle\Controller;

use ApiBundle\Models\Location;
use Sensio\Bundle\FrameworkExtraBundle\Configuration;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use ApiBundle\Services\GooglePlaces;

/**
 * Places controller
 *
 * @author    Piotr Żuralski <piotr@zuralski.net>
 * @copyright 2016 Piotr Żuralski
 * @package   ApiBundle\Controller
 * @since     2016-06-24
 *
 * @Rest\RouteResource(resource="place")
 */
class PlaceController extends AbstractRestController
{

    /**
     * Returns Google Places API Service
     *
     * @return GooglePlaces
     */
    private function getGooglePlaces()
    {
        return $this->container->get('api.services.google_places');
    }

    /**
     * Returns list of places
     *
     * @ApiDoc(resource=true, statusCodes={
     *      Codes::HTTP_OK                      = "Success",
     *      Codes::HTTP_BAD_REQUEST             = "Missing required parameters",
     *      Codes::HTTP_INTERNAL_SERVER_ERROR   = "Internal Server Error",
     *      Codes::HTTP_SERVICE_UNAVAILABLE     = "Service unavailable / server unavailable",
     * })
     * @Rest\QueryParam(name="lat", description="Latitude", requirements="(\-?\d+(\.\d+)?)", strict=true)
     * @Rest\QueryParam(name="lng", description="Longitude", requirements="(\-?\d+(\.\d+)?)", strict=true)
     * @Rest\QueryParam(name="q", description="Query", requirements="\w+", strict=true, default="")
     * @Rest\QueryParam(name="radius", description="Radius", requirements="\d+", default=2000, strict=true)
     * @Rest\Get(path="/places/", defaults={"_format" = "json"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function placesListAction(ParamFetcherInterface $paramFetcher)
    {
        $requestParameters = [
            'lat'       => $paramFetcher->get('lat'),
            'lng'       => $paramFetcher->get('lng'),
            'radius'    => $paramFetcher->get('radius'),
            'q'         => $paramFetcher->get('q'),
            '_format'   => $this->getRequestFormat(),
        ];

        $this->logApiStartMethod(__METHOD__, $requestParameters);

        try {
            $location = new Location($paramFetcher->get('lat'), $paramFetcher->get('lng'));
            $result = $this->getGooglePlaces()->placeList(
                $location,
                $paramFetcher->get('radius'),
                $paramFetcher->get('q')
            );

        } catch (\Exception $exception) {
            return $this->handleException(__METHOD__, $exception, $requestParameters);
        }

        return $this->returnSuccess(
            $result,
            Codes::HTTP_OK,
            $requestParameters
        );

    }

    /**
     * Returns places details
     *
     * @ApiDoc(resource=true, statusCodes={
     *      Codes::HTTP_OK                      = "Success",
     *      Codes::HTTP_BAD_REQUEST             = "Missing required parameters",
     *      Codes::HTTP_INTERNAL_SERVER_ERROR   = "Internal Server Error",
     *      Codes::HTTP_SERVICE_UNAVAILABLE     = "Service unavailable / server unavailable",
     * })
     *
     * @Rest\Get(path="/places/{id}", requirements={"id" = "[A-Za-z0-9-_]{27,}",}, defaults={"_format" = "json"})
     *
     * @param string $id
     *
     * @return View
     */
    public function placesDetailsAction($id)
    {
        $requestParameters = [
            'id'       => $id,
            '_format'   => $this->getRequestFormat(),
        ];

        $this->logApiStartMethod(__METHOD__, $requestParameters);

        try {
            $result = $this->getGooglePlaces()->placeDetails($id);
        } catch (\Exception $exception) {
            return $this->handleException(__METHOD__, $exception, $requestParameters);
        }

        return $this->returnSuccess(
            $result,
            Codes::HTTP_OK,
            $requestParameters
        );
    }

}