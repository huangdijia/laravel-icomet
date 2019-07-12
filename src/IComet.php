<?php

namespace Huangdijia\IComet;

class IComet
{
    private $config = [];

    public function __construct(array $config = [])
    {
        $this->config        = $config;
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
     * @param string $cname
     * @param string|array $content
     * @return bool
     */
    public function push(string $cname = '', $content = '')
    {
        if (is_array($content)) {
            $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        }

        $response = $this->get('/push', compact('cname', 'content'));

        return ($response['type'] ?? '') == 'ok';
    }

    /**
     * Broadcast
     * @param string|array $content
     * @return bool
     */
    public function broadcast($content = '')
    {
        if (is_array($content)) {
            $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        }

        $response = $this->get('/broadcast', compact('content'), false);

        return trim($response) == 'ok';
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
     *
     * @return mixed
     */
    public function psub()
    {
        return $this->get('/psub');
    }

    /**
     * curl Get
     * @param string $path
     * @param array $queries
     * @param bool $decode
     * @return string|array
     */
    private function get(string $path = '', array $queries = [], bool $decode = true)
    {
        $url   = rtrim($this->config['api'], '/') . '/' . ltrim($path, '/');
        $query = http_build_query($queries);
        if ($query) {
            $url .= (strpos($url, '?') === false ? '?' : '&') . $query;
        }

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        curl_close($ch);

        if (!$decode) {
            return $response;
        }

        return json_decode($response, true);
    }
}