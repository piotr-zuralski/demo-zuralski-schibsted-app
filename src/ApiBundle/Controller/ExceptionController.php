<?php

namespace ApiBundle\Controller;

use RuntimeException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpKernel\Exception\FlattenException as HttpFlattenException;
use Symfony\Component\Debug\Exception\FlattenException as DebugFlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Exception Controller
 *
 * @author    Piotr Żuralski <piotr.zuralski@gmail.com>
 * @copyright 2016 Piotr Żuralski
 * @package   ApiBundle\Controller
 * @since     2016-06-24
 */
class ExceptionController extends AbstractRestController
{

    /**
     * Converts an Exception to a Response.
     *
     * @param Request                                    $request
     * @param HttpFlattenException|DebugFlattenException $exception
     * @param DebugLoggerInterface                       $logger
     *
     * @return Response
     */
    public function showAction(Request $request, $exception, DebugLoggerInterface $logger = null)
    {
        $this->logApiStartMethod(__METHOD__, []);

        $newException = new RuntimeException($exception->getMessage(), $exception->getCode());
        return $this->handleException(__METHOD__, $newException, []);
    }

}