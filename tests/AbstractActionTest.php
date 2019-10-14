<?php declare(strict_types=1);


use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use ThreatDataScience\HttpActions\AbstractAction;

/**
 * Class AbstractActionTest
 * @copyright 2019 Threat Data Science
 * @author Andrew Breksa <andrew@threatdatascience.io>
 */
class AbstractActionTest extends TestCase
{

    public function testText()
    {
        $action = new Action();

        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('withStatus')
            ->with(200)
            ->andReturnSelf()
            ->once();
        $response->shouldReceive('withBody')
            ->withArgs(function (StreamInterface $stream) {
                $stream->rewind();
                $this->assertEquals('Hello World!', $stream->getContents());
                return true;
            })
            ->andReturnSelf()
            ->once();
        $response->shouldReceive('withHeader')
            ->with('Content-Type', 'application/x-test')
            ->andReturnSelf()
            ->once();
        $response->shouldReceive('withHeader')
            ->with('Powered-By', 'Caffeine')
            ->andReturnSelf()
            ->once();

        $actionResponse = $action->text($response, 200, 'Hello World!', 'application/x-test', [
            'Powered-By' => 'Caffeine'
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $actionResponse);

        Mockery::close();
    }

    public function testJson()
    {
        $action = new Action();

        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('withStatus')
            ->with(200)
            ->andReturnSelf()
            ->once();
        $response->shouldReceive('withBody')
            ->withArgs(function (StreamInterface $stream) {
                $stream->rewind();
                $this->assertEquals(json_encode([
                    'this' => [
                        'is some',
                        'data',
                        true => 'dat'
                    ]
                ]), $stream->getContents());
                return true;
            })
            ->andReturnSelf()
            ->once();
        $response->shouldReceive('withHeader')
            ->with('Content-Type', 'application/json')
            ->andReturnSelf()
            ->once();
        $response->shouldReceive('withHeader')
            ->with('Powered-By', 'Caffeine')
            ->andReturnSelf()
            ->once();

        $actionResponse = $action->json($response, 200, [
            'this' => [
                'is some',
                'data',
                true => 'dat'
            ]
        ], [
            'Powered-By' => 'Caffeine'
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $actionResponse);

        Mockery::close();
    }

    public function testFetchQueryParamDefault()
    {
        $action = new Action();
        $request = Mockery::mock(ServerRequestInterface::class);

        $request->shouldReceive('getQueryParams')
            ->andReturn([
                'not_test_key' => [
                    1, 2, 3
                ]
            ])
            ->once();

        $out = $action->fetchQueryParam($request, 'test_key', 'defaulty-value');

        $this->assertEquals('defaulty-value', $out);

        Mockery::close();
    }

    public function testFetchQueryParamNoDefault()
    {
        $action = new Action();
        $request = Mockery::mock(ServerRequestInterface::class);

        $request->shouldReceive('getQueryParams')
            ->andReturn([
                'not_test_key' => [
                    1, 2, 3
                ]
            ])
            ->twice();

        $out = $action->fetchQueryParam($request, 'not_test_key', 'defaulty-value');

        $this->assertEquals([1, 2, 3], $out);

        Mockery::close();
    }

}

class Action extends AbstractAction
{

    /**
     * @param ServerRequestInterface $serverRequest
     * @param ResponseInterface $response
     * @param array $parameters
     * @return ResponseInterface
     */
    public function execute(ServerRequestInterface $serverRequest, ResponseInterface $response, array $parameters = []): ResponseInterface
    {
        // TODO: Implement execute() method.
    }
}