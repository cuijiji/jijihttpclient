<?php
/*
 * http client
 *
 * author cuijiji
 * date 2018 12 05
 */
namespace Jiji\Http;

use GuzzleHttp\Client as GuzzleClient;
use Jiji\Http\Response\Response;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\ResponseInterface;

/**
 * Class BaseClient.
 *
 * @author Jiji <i@Jiji.me>
 */
class Client
{
    /**
     * @var \Jiji\Http\Config
     */
    protected $config;

    /**
     * @var
     */
    protected $httpClient;

    /**
     * @var
     */
    protected $baseUri;

    /**
     * @var array
     */
    protected static $defaults = [
        'curl' => [
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ],
    ];

    /**
     * @var \GuzzleHttp\HandlerStack
     */
    protected $handlerStack;

    /**
     * @return static
     */
    public static function create(): self
    {
        return new static(...func_get_args());
    }
    
    /**
     * Client constructor.
     *
     * @param \Jiji\Http\Config|array $config
     */
    public function __construct($config = [])
    {
        $this->config = $this->normalizeConfig($config);
    }
    /**
     * GET request.
     *
     * @param string $url
     * @param array  $query
     *
     * @return array|object|string
     */
    public function get(string $url, array $query = [])
    {
        return $this->request($url, 'GET', ['query' => $query]);
    }
    /**
     * POST request.
     *
     * @param string $url
     * @param array  $data
     *
     * @return \Psr\Http\Message\ResponseInterface|array|object|string
     */
    public function post(string $url, array $data = [])
    {
        return $this->request($url, 'POST', ['form_params' => $data]);
    }
    /**
     * JSON request.
     *
     * @param string       $url
     * @param string|array $data
     * @param array        $query
     *
     * @return \Psr\Http\Message\ResponseInterface|array|object|string
     */
    public function postJson(string $url, array $data = [], array $query = [])
    {
        return $this->request($url, 'POST', ['query' => $query, 'json' => $data]);
    }
    /**
     * Upload file.
     *
     * @param string $url
     * @param array  $files
     * @param array  $form
     * @param array  $query
     *
     * @return \Psr\Http\Message\ResponseInterface|array|object|string
     */
    public function upload(string $url, array $files = [], array $form = [], array $query = [])
    {
        $multipart = [];
        foreach ($files as $name => $path) {
            $multipart[] = [
                'name'     => $name,
                'contents' => fopen($path, 'r'),
            ];
        }
        foreach ($form as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }
        return $this->request($url, 'POST', ['query' => $query, 'multipart' => $multipart]);
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array  $options
     * @param bool   $returnRaw
     *
     * @return \Psr\Http\Message\ResponseInterface|array|object|string
     */
    public function request(string $uri, string $method = 'GET', array $options = [], $returnRaw = false)
    {
        if (property_exists($this, 'baseUri') && !is_null($this->baseUri)) {
            $options['base_uri'] = $this->baseUri;
        }
        if ((!empty($options['base_uri']) || $this->config->getBaseUri()) && $this->config->needAutoTrimEndpointSlash()) {
            $uri = ltrim($uri, '/');
        }
        $response = $this->performRequest($uri, $method, $options);
        return $returnRaw ? $response : $this->castResponseToType($response, $this->config->getOption('response_type'));
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string|null                         $type
     *
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     */
    protected function castResponseToType(ResponseInterface $response, $type = null)
    {
        $response = Response::buildFromPsrResponse($response);
        $response->getBody()->rewind();
        switch ($type ?? 'array') {
            case 'collection':
                return $response->toCollection();
            case 'array':
                return $response->toArray();
            case 'object':
                return $response->toObject();
            case 'raw':
                return $response;
        }
    }

    /**
     * Make a request.
     *
     * @param string $uri
     * @param string $method
     * @param array  $options
     *
     * @return \Psr\Http\Message\ResponseInterface|array|object|string
     */
    public function performRequest($uri, $method = 'GET', $options = []): ResponseInterface
    {
        $method = strtoupper($method);
        $options = array_merge(self::$defaults, $options, ['handler' => $this->getHandlerStack()]);
        $response = $this->getHttpClient()->request($method, $uri, $options);
        $response->getBody()->rewind();
        return $response;
    }

    /**
     * Build a handler stack.
     *
     * @return \GuzzleHttp\HandlerStack
     */
    public function getHandlerStack(): HandlerStack
    {
        if ($this->handlerStack) {
            return $this->handlerStack;
        }
        $this->handlerStack = HandlerStack::create();

        return $this->handlerStack;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array  $options
     *
     * @return \Jiji\Http\Response\Response
     */
    public function requestRaw(string $url, string $method = 'GET', array $options = [])
    {
        return Response::buildFromPsrResponse($this->request($url, $method, $options, true));
    }
    /**
     * @param \GuzzleHttp\Client $client
     *
     * @return \Jiji\Http\Client
     */
    public function setHttpClient(GuzzleClient $client): \Jiji\Http\Client
    {
        $this->httpClient = $client;
        return $this;
    }
    /**
     * Return GuzzleHttp\Client instance.
     *
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient(): \GuzzleHttp\Client
    {
        if (!($this->httpClient instanceof GuzzleClient)) {
            $this->httpClient = new GuzzleClient($this->config->toArray());
        }
        return $this->httpClient;
    }
    /**
     * @return \Jiji\Http\Config
     */
    public function getConfig(): \Jiji\Http\Config
    {
        return $this->config;
    }
    /**
     * @param \Jiji\Http\Config $config
     *
     * @return \Jiji\Http\Client
     */
    public function setConfig(\Jiji\Http\Config $config): \Jiji\Http\Client
    {
        $this->config = $config;
        return $this;
    }
    /**
     * @param mixed $config
     *
     * @return \Jiji\Http\Config
     */
    protected function normalizeConfig($config): \Jiji\Http\Config
    {
        if (\is_array($config)) {
            $config = new Config($config);
        }
        if (!($config instanceof Config)) {
            throw new \InvalidArgumentException('config must be array or instance of Jiji\Http\Config.');
        }
        return $config;
    }
}