<?php declare(strict_types=1);


namespace ThreatDataScience\HttpActions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface ActionInterface
 * @package ThreatDataScience\HttpActions
 * @copyright 2019 Threat Data Science
 * @author Andrew Breksa <andrew@threatdatascience.io>
 */
interface ActionInterface
{

    /**
     * @param ServerRequestInterface $serverRequest
     * @param ResponseInterface $response
     * @param array $parameters
     * @return ResponseInterface
     */
    public function execute(
        ServerRequestInterface $serverRequest,
        ResponseInterface $response,
        array $parameters = []
    ): ResponseInterface;

}