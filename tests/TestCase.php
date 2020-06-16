<?php

namespace FLAIR\GoodTillSystem\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\RequestException;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use FLAIR\GoodTillSystem\GoodTillSystemServiceProvider;

class TestCase extends OrchestraTestCase
{

    public function createMockResponse($responseData, $statusCode)
    {
        $headers = ['Content-Type' => 'application/json'];
        $body = json_encode($responseData);

        $response = new Response($statusCode, $headers, $body);

        $mock = new MockHandler([
            $response
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        //client instance is bound to the mock here.
        $this->app->instance(Client::class, $client); 

        return $response;
    }

}
