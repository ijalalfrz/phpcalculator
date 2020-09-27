<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use GuzzleHttp\Client;

abstract class BaseTest extends TestCase
{

    protected static $process;

    // const ENVIRONMENT = "production";
    const HOST = "0.0.0.0";
    const PORT = 9191; // Adjust this to a port you're sure is free

    public static function setUpBeforeClass():void
    {
        // The command to spin up the server
        // $command = sprintf(
        //   'ENVIRONMENT=%s php -S %s:%d -t %s %s',
        //   self::ENVIRONMENT,
        //   self::HOST,
        //   self::PORT,
        //   realpath(__DIR__.'/../../public'),
        //   realpath(__DIR__.'/../../public/index.php')
        // );
        // Using Symfony/Process to get a handler for starting a new process
        $command = '../server';
        self::$process = new Process($command);
        // Disabling the output, otherwise the process might hang after too much output
        self::$process->disableOutput();
        // Actually execute the command and start the process
        self::$process->start();
        // Let's give the server some leeway to fully start
        usleep(150000);
    }

    public static function tearDownAfterClass():void
    {
        self::$process->stop();
    }

    protected function dispatch($data = null, $path = null, $method = 'POST'): ResponseInterface
    {
        
        $params = [];
        if ($data) {
            $params['body'] = $data;
        }

        // Creating a Guzzle Client with the base_uri, so we can use a relative
        // path for the requests.
        $client = new Client(['base_uri' => 'http://127.0.0.1:' . self::PORT]);
        return $client->request($method, $path, $params);
    }
}
