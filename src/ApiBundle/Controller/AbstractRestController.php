<?php

namespace ApiBundle\Controller;

use ApiBundle\Response\RestResponse;
use stdClass;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

/**
 * (description)
 *
 * @author    Piotr Żuralski <piotr.zuralski@gmail.com>
 * @copyright 2016 Piotr Żuralski
 * @package   ApiBundle\Controller
 * @since     2016-06-24
 * @version   Release: $Id$
 */
abstract class AbstractRestController  extends FOSRestController
{
    /**
     * Zwraca logger-a
     *
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        return $this->container->get('logger');
    }

    /**
     * Zwraca komunikat o błędzie
     *
     * @param string    $errorDeveloperMessage  Komunikat o błędzie dla programisty
     * @param string    $errorUserMessage       Komunikat o błędzie dla użytkownika
     * @param string    $errorCode              [OPTIONAL] Kod błędu
     * @param string    $errorInfo              [OPTIONAL] Dodatkowe informacje o błędzie (z adresem URL)
     * @param int       $httpCode               [OPTIONAL] Kod HTTP odpowiedzi
     * @param array     $requestInfo            [OPTIONAL] Dane o wysłanym żądaniu do serwera
     * @param array     $metadata               [OPTIONAL] Dodatkowe metadane
     *
     * @return View
     */
    protected function returnError(
        $errorDeveloperMessage,
        $errorUserMessage,
        $errorCode = '',
        $errorInfo = '',
        $httpCode = Codes::HTTP_BAD_REQUEST,
        array $requestInfo = array(),
        array $metadata = array()
    ) {


        $response = (new RestResponse())
            ->setStatus($httpCode)
            ->setRequestInfo($requestInfo)
            ->addMetadata($metadata)
            ->setErrorDeveloperMessage($errorDeveloperMessage)
            ->setErrorUserMessage($errorUserMessage)
            ->setErrorCode($errorCode)
            ->setErrorMoreInfo($errorInfo)
            ->setResults(null)
        ;

        return new View($response, $httpCode);
    }

    /**
     * Zwraca komunikat o sukcesie
     *
     * @param array|mixed   $resultsData            Dane
     * @param int           $httpCode               [OPTIONAL] Kod HTTP odpowiedzi
     * @param array         $requestInfo            [OPTIONAL] Dane o wysłanym żądaniu do serwera
     * @param array         $metadata               [OPTIONAL] Dodatkowe metadane
     *
     * @return View
     */
    protected function returnSuccess(
        $resultsData,
        $httpCode = Codes::HTTP_OK,
        array $requestInfo = array(),
        array $metadata = array()
    ) {

        $response = (new RestResponse())
            ->setStatus($httpCode)
            ->setRequestInfo($requestInfo)
            ->addMetadata($metadata)
            ->setResults($resultsData)
        ;

        return new View($response, $httpCode);
    }

    /**
     * Zwraca aktualny request
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->container->get('request_stack')->getCurrentRequest();
    }

    /**
     * Zwraca aktualny format żądania
     *
     * @return string
     */
    public function getRequestFormat()
    {
        return $this->getRequest()->getRequestFormat();
    }

    /**
     * Loguje start metody API z parametrami wejściowymi do API
     *
     * @param string    $method         Metoda wejściowa klasy (__METHOD__)
     * @param array     $requestInfo    Parametry żądania wysłane przez klienta API
     *
     * @return void
     */
    protected function logApiStartMethod($method, array $requestInfo)
    {
        $this->getLogger()->info(sprintf('API called: "%s"', $method), $requestInfo);
    }

    /**
     * Obsługa wyjątków API
     *
     * @param string    $method         Metoda wejściowa klasy (__METHOD__)
     * @param Exception $exception      Obiekt wyjątku
     * @param array     $requestInfo    Parametry żądania wysłane przez klienta API
     * @param array     $metadata       [OPTIONAL] Dodatkowe metadane
     *
     * @return View
     */
    protected function handleException($method, Exception $exception, array $requestInfo, array $metadata = array())
    {
        switch (get_class($exception)) {
            case 'InvalidArgumentException':
                $this->getLogger()->warning(
                    sprintf('API ERROR %s: %s', $method, $exception->getMessage()),
                    [$requestInfo, $exception]
                );

                return $this->returnError(
                    $exception->getMessage(),
                    'Wystąpił błąd podczas przetwarzania żądania',
                    $exception->getCode(),
                    '',
                    Codes::HTTP_BAD_REQUEST,
                    $requestInfo,
                    $metadata
                );
                break;

            case 'DomainException':
                $this->getLogger()->warning(
                    sprintf('API ERROR %s: %s', $method, $exception->getMessage()),
                    [$requestInfo, $exception]
                );

                return $this->returnError(
                    $exception->getMessage(),
                    'Żądany obiekt nie został odnaleziony',
                    $exception->getCode(),
                    '',
                    Codes::HTTP_NOT_FOUND,
                    $requestInfo,
                    $metadata
                );
                break;

            case 'RuntimeException':
            default:
                $this->getLogger()->critical(
                    sprintf('API ERROR %s: %s', $method, $exception->getMessage()),
                    [$requestInfo, $exception]
                );

                return $this->returnError(
                    $exception->getMessage(),
                    'Żądanie nie może być zrealizowane',
                    $exception->getCode(),
                    '',
                    Codes::HTTP_INTERNAL_SERVER_ERROR,
                    $requestInfo,
                    $metadata
                );
                break;



        }
    }

}