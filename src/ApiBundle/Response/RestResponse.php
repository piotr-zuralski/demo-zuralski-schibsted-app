<?php

namespace ApiBundle\Response;

use stdClass;
use Symfony\Component\HttpFoundation\Response;

/**
 * Rest response object
 *
 * @author    Piotr Żuralski <piotr@zuralski.net>
 * @copyright 2016 Piotr Żuralski
 * @since     2016-06-24
 * @version   Release: $Id$
 */
class RestResponse
{

    const REQUEST_INFO      = 'requestInfo';
    const RESPONSE_INFO     = 'responseInfo';
    const STATUS            = 'status';

    const ERROR                     = 'error';
    const ERROR_IS_ERROR            = 'isError';
    const ERROR_CODE                = 'code';
    const ERROR_DEVELOPER_MESSAGE   = 'developerMessage';
    const ERROR_USER_MESSAGE        = 'userMessage';
    const ERROR_MORE_INFO           = 'moreInfo';

    /**
     * Metadata
     *
     * @var array
     */
    private $metadata = array();

    /**
     * Result
     *
     * @var null|array|object
     */
    private $results = null;

    /**
     * Contructor
     */
    public function __construct()
    {
        $this->results  = new stdClass();
        $this->metadata[ self::REQUEST_INFO ] = [];
        $this->metadata[ self::RESPONSE_INFO ] = [];
        $this->metadata[ self::RESPONSE_INFO ][ self::STATUS ] = Response::HTTP_OK;

        $this->metadata[ self::RESPONSE_INFO ][ self::ERROR ] = [];
        $this->metadata[ self::RESPONSE_INFO ][ self::ERROR ][ self::ERROR_IS_ERROR ]             = false;
        $this->metadata[ self::RESPONSE_INFO ][ self::ERROR ][ self::ERROR_CODE ]                 = '';
        $this->metadata[ self::RESPONSE_INFO ][ self::ERROR ][ self::ERROR_DEVELOPER_MESSAGE ]    = 'OK';
        $this->metadata[ self::RESPONSE_INFO ][ self::ERROR ][ self::ERROR_USER_MESSAGE ]         = '';
        $this->metadata[ self::RESPONSE_INFO ][ self::ERROR ][ self::ERROR_MORE_INFO ]            = '';
    }

    /**
     * Ustawia dane o żądaniu
     *
     * @param array $requestInfo
     *
     * @return $this
     */
    public function setRequestInfo(array $requestInfo)
    {
        if (empty($requestInfo)) {
            return $this;
        }

        $this->metadata[ self::REQUEST_INFO ] = $requestInfo;
        return $this;
    }

    /**
     * Dodaje metadane
     *
     * @param array $metadata
     *
     * @return $this
     */
    public function addMetadata(array $metadata)
    {
        if (empty($metadata)) {
            return $this;
        }

        $this->metadata = array_merge($this->metadata, $metadata);
        return $this;
    }

    /**
     * Ustawia status odpowiedzi
     *
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->metadata[ self::RESPONSE_INFO ][ self::STATUS ] = $status;
        return $this;
    }

    /**
     * Ustawia kod błędu
     *
     * @param string $code
     *
     * @return $this
     */
    public function setErrorCode($code)
    {
        $this->metadata[ self::RESPONSE_INFO ][ self::ERROR ][ self::ERROR_IS_ERROR ] = true;
        $this->metadata[ self::RESPONSE_INFO ][ self::ERROR ][ self::ERROR_CODE ] = $code;
        return $this;
    }

    /**
     * Ustawia wiadomość dla programisty o błędzie
     *
     * @param string $developerMessage
     *
     * @return $this
     */
    public function setErrorDeveloperMessage($developerMessage)
    {
        $this->metadata[ self::RESPONSE_INFO ][ self::ERROR ][ self::ERROR_IS_ERROR ] = true;
        $this->metadata[ self::RESPONSE_INFO ][ self::ERROR ][ self::ERROR_DEVELOPER_MESSAGE ] = $developerMessage;
        return $this;
    }

    /**
     * Ustawia komunikat o błędzie dla użytkownika
     *
     * @param string $userMessage
     *
     * @return $this
     */
    public function setErrorUserMessage($userMessage)
    {
        $this->metadata[ self::RESPONSE_INFO ][ self::ERROR ][ self::ERROR_IS_ERROR ] = true;
        $this->metadata[ self::RESPONSE_INFO ][ self::ERROR ][ self::ERROR_USER_MESSAGE ] = $userMessage;
        return $this;
    }

    /**
     * Ustawia dodatkowe informacje o błędzie
     *
     * @param string $moreInfo
     *
     * @return $this
     */
    public function setErrorMoreInfo($moreInfo)
    {
        $this->metadata[ self::RESPONSE_INFO ][ self::ERROR ][ self::ERROR_IS_ERROR ] = true;
        $this->metadata[ self::RESPONSE_INFO ][ self::ERROR ][ self::ERROR_MORE_INFO ] = $moreInfo;
        return $this;
    }

    /**
     * Ustawia treść odpowiedzi
     *
     * @param array|object $results
     *
     * @return $this
     */
    public function setResults($results)
    {
        $this->results = ((empty($results)) ? new stdClass() : $results);
        return $this;
    }

    /**
     * Zwraca metadane
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Zwraca wynik
     *
     * @return null|array|object
     */
    public function getResults()
    {
        return $this->results;
    }

}