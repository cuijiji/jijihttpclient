<?php
/*
 * http client config class
 *
 * author cuijiji
 * date 2018 12 05
 */

namespace Jiji\Http;

class Config
{
    /**
     * @var array
     */
    protected $options = [
        'base_uri'        => null,
        'timeout'         => 3000,
        'connect_timeout' => 3000,
        'proxy'           => [],
    ];
    /**
     * @var bool
     */
    protected $autoTrimEndpointSlash = true;
    /**
     * Config constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }
    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->options['base_uri'] ?? '';
    }
    /**
     * @param string $baseUri
     *
     * @return \Jiji\Http\Config
     */
    public function setBaseUri($baseUri): \Jiji\Http\Config
    {
        $this->options['base_uri'] = $baseUri;
        return $this;
    }
    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->options['timeout'] ?? 3000;
    }
    /**
     * @param int $timeout
     *
     * @return \Jiji\Http\Config
     */
    public function setTimeout($timeout): \Jiji\Http\Config
    {
        $this->options['timeout'] = $timeout;
        return $this;
    }
    /**
     * @return int
     */
    public function getConnectTimeout(): int
    {
        return $this->options['connect_timeout'] ?? 3000;
    }
    /**
     * @param int $connectTimeout
     *
     * @return \Jiji\Http\Config
     */
    public function setConnectTimeout($connectTimeout): \Jiji\Http\Config
    {
        $this->options['connect_timeout'] = $connectTimeout;
        return $this;
    }
    /**
     * @return array
     */
    public function getProxy(): array
    {
        return $this->options['proxy'] ?? [];
    }
    /**
     * @param array $proxy
     *
     * @return \Jiji\Http\Config
     */
    public function setProxy(array $proxy): \Jiji\Http\Config
    {
        $this->options['proxy'] = $proxy;
        return $this;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->options;
    }
    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setOption($key, $value): \Jiji\Http\Config
    {
        $this->options[$key] = $value;
        return $this;
    }
    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOption($key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }
    /**
     * @param array $options
     *
     * @return $this
     */
    public function mergeOptions(array $options): \Jiji\Http\Config
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }
    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options): \Jiji\Http\Config
    {
        $this->options = $options;
        return $this;
    }
    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
    /**
     * @return bool
     */
    public function needAutoTrimEndpointSlash(): bool
    {
        return $this->autoTrimEndpointSlash;
    }
    /**
     * @return $this
     */
    public function disableAutoTrimEndpointSlash(): \Jiji\Http\Config
    {
        $this->autoTrimEndpointSlash = false;
        return $this;
    }
}