<?php declare(strict_types=1);


namespace ThreatDataScience\HttpActions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function GuzzleHttp\Psr7\stream_for;
use function Suin\Json\json_encode;

/**
 * Class AbstractAction
 * @package ThreatDataScience\HttpActions
 * @author Andrew Breksa <andrew@threatdatascience.io>
 */
abstract class AbstractAction implements ActionInterface
{

    /**
     * @param ServerRequestInterface $serverRequest
     * @param string $key
     * @param null $default
     * @return |null
     */
    public function fetchQueryParam(ServerRequestInterface $serverRequest, string $key, $default = null)
    {
        if (!array_key_exists($key, $serverRequest->getQueryParams())) {
            return $default;
        }
        return $serverRequest->getQueryParams()[$key];
    }

    /**
     * @param ResponseInterface $response
     * @param int $statusCode
     * @param array $data
     * @param array $headers
     * @return ResponseInterface
     */
    public function json(
        ResponseInterface $response,
        int $statusCode,
        array $data,
        array $headers = []
    ): ResponseInterface
    {
        return $this->text($response, $statusCode, json_encode($data), 'application/json', $headers);
    }

    /**
     * @param ResponseInterface $response
     * @param int $statusCode
     * @param string $content
     * @param string $contentType
     * @param array $headers
     * @return ResponseInterface
     */
    public function text(
        ResponseInterface $response,
        int $statusCode,
        string $content,
        string $contentType = 'text/html',
        array $headers = []
    ): ResponseInterface
    {
        $response = $response->withStatus($statusCode)
            ->withBody(stream_for($content))
            ->withHeader('Content-Type', $contentType);
        foreach ($headers as $key => $value) {
            $response = $response->withHeader($key, $value);
        }
        return $response;
    }

}