<?php

namespace FrontendBundle\Controller;

use GuzzleHttp\ClientInterface;
use FrontendBundle\Form\SearchForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Frontend
 *
 * @author    Piotr Żuralski <piotr@zuralski.net>
 * @copyright 2016 Piotr Żuralski
 * @since     2016-06-26
 */
class IndexController extends Controller
{
    const FLASHBAG_SUCCESS = 'success';
    const FLASHBAG_INFO = 'info';
    const FLASHBAG_NOTICE = 'warning';
    const FLASHBAG_ERROR = 'danger';

    /**
     * Returns HTTP client
     *
     * @return ClientInterface
     */
    private function getHttpClient()
    {
        return $this->container->get('csa_guzzle.client.default');
    }

    /**
     * Places list
     *
     * @Configuration\Cache(public=true, maxage=3600, smaxage=3600)
     * @Configuration\Method(methods={"GET", "POST"})
     * @Configuration\Route(path="/")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $result = [];
        $searchForm = $this->createForm(SearchForm::class);
        if ($request->getMethod() == 'POST') {
            $searchForm->handleRequest($request);

            if (!$searchForm->isValid()) {
                $request->getSession()->getFlashBag()->add(
                    self::FLASHBAG_NOTICE,
                    'Invalid data in form'
                );
            } else {
                $searchData = $searchForm->getData();
                $url = $this->generateUrl('api_place_placeslist', $searchData, UrlGeneratorInterface::ABSOLUTE_URL);
                $response = $this->getHttpClient()->request('GET', $url);
                $responseBodyJson = json_decode($response->getBody()->getContents());
                if ($responseBodyJson->metadata->responseInfo->error->isError) {
                    $request->getSession()->getFlashBag()->add(
                        self::FLASHBAG_ERROR,
                        $responseBodyJson->metadata->responseInfo->error->userMessage
                    );
                } else {
                    $result = ((is_object($responseBodyJson->results))
                        ? [$responseBodyJson->results] : $responseBodyJson->results
                    );
                }
            }
        }

        return $this->render('FrontendBundle:Index:list.html.twig', [
            'searchForm'=> $searchForm->createView(),
            'result' => $result,
        ]);
    }

}

