<?php

namespace Huangdijia\IComet;

use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;

class IComet
{
    private $config = [];
    private $client;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => rtrim($this->config['api'], '/'),
            'timeout'  => $this->config['timeout'] ?? 2,
        ]);
    }

    /**
     * Sign
     * @param string $cname
     * @param int $expires
     * @return array
     */
    public function sign(string $cname = '', int $expires = 60)
    {
        return $this->get('/sign', compact('cname', 'expires'));
    }

    /**
     * Push
     * @param string|array $cname
     * @param string|array $content
     * @return bool
     */
    public function push($cname = '', $content = '')
    {
        // Batch
        if (is_array($cname)) {
            return $this->batchPush($cname);
        }

        // Single
        if (!is_scalar($cname)) {
            throw new \InvalidArgumentException("\$cname must be string, " . gettype($cname) . " given", 1);
        }

        // transform content
        if (is_array($content)) {
            $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        }

        $response = $this->get('/push', compact('cname', 'content'));

        return ($response['type'] ?? '') == 'ok';
    }

    /**
     * Batch push
     *
     * @param array $context ['cname' => 'content']
     * @return bool
     */
    public function batchPush(array $context = [])
    {
        $uri      = '/push';
        $requests = function ($uri, $context) {
            foreach ($context as $cname => $content) {
                if (is_array($content)) {
                    $content = json_encode($content, JSON_UNESCAPED_UNICODE);
                }

                yield new Request('GET', $uri . '?' . http_build_query(['cname' => $cname, 'content' => $content]));
            }
        };

        $pool = new Pool($this->client, $requests($uri, $context), [
            'concurrency' => 30,
            'fulfilled'   => function ($response, $index) {
                // this is delivered each successful response
            },
            'rejected'    => function ($reason, $index) {
                // this is delivered each failed request
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();

        return true;
    }

    /**
     * Broadcast
     * @param string|array $content
     * @param array|null $cnames
     * @return bool
     */
    public function broadcast($content = '', array $cnames = null)
    {
        if (is_array($content)) {
            $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        }

        // broadcast to all channels
        if (is_null($cnames)) {
            $response = $this->get('/broadcast', compact('content'), false);

            return trim($response) == 'ok';
        }

        // push to assign channels
        $uri      = '/push';
        $requests = function ($uri, $cnames, $content) {
            foreach ($cnames as $cname) {
                yield new Request('GET', $uri . '?' . http_build_query(['cname' => $cname, 'content' => $content]));
            }
        };

        $pool = new Pool($this->client, $requests($uri, $cnames, $content), [
            'concurrency' => 30,
            'fulfilled'   => function ($response, $index) {
                // this is delivered each successful response
            },
            'rejected'    => function ($reason, $index) {
                // this is delivered each failed request
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();

        return true;
    }

    /**
     * Check
     *
     * @param string $cname
     * @return bool
     */
    public function check(string $cname = '')
    {
        $result = $this->get('/check', compact('cname'));

        return !empty($result[$cname]);
    }

    /**
     * Close
     *
     * @param string $cname
     * @return bool
     */
    public function close(string $cname = '')
    {
        $response = $this->get('/close', compact('cname'), false);

        return substr($response, 0, 2) == 'ok';
    }

    /**
     * Clear
     *
     * @param string $cname
     * @return bool
     */
    public function clear(string $cname = '')
    {
        $response = $this->get('/clear', compact('cname'), false);

        return substr($response, 0, 2) == 'ok';
    }

    /**
     * Info
     *
     * @param string $cname
     * @return array
     */
    public function info(string $cname = '')
    {
        return $this->get('/info', $cname ? compact('cname') : []);
    }

    /**
     * Psub
     * @param \Closure $callback
     *
     * @return void
     */

    public function psub(Closure $callback)
    {
        $url    = rtrim($this->config['api'], '/') . '/psub';
        $handle = fopen($url, 'rb');

        if (false === $handle) {
            throw new \RuntimeException('cannot open ' . $url);
        }

        while (!feof($handle)) {
            $line = fread($handle, 8192);
            $line = trim($line);

            if (empty($line)) {
                continue;
            }

            $data    = explode(' ', $line, 2);
            $status  = (int) ($data[0] ?? 0);
            $channel = (int) ($data[1] ?? 0);

            $callback($channel, $status);
        }

        fclose($handle);
    }

    /**
     * curl Get
     * @param string $path
     * @param array $query
     * @param bool $decode
     * @return string|array
     */
    private function get(string $path = '', array $query = [], bool $decode = true)
    {
        $path     = '/' . ltrim($path, '/');
        $response = $this->client->get($path, ['query' => $query]);
        $body     = $response->getBody()->getContents();

        if (!$decode) {
            return $body;
        }

        return json_decode($body, true);
    }
}
