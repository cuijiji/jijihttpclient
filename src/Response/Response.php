<?php
/*
 * http response config class
 *
 * author cuijiji
 * date 2018 12 05
 */

namespace Jiji\Http\Response;

use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Psr\Http\Message\ResponseInterface;


class Response extends GuzzleResponse
{
    /**
     * @return string
     */
    public function getBodyContents(): string
    {
        $this->getBody()->rewind();
        $contents = $this->getBody()->getContents();
        $this->getBody()->rewind();
        return $contents;
    }

    /**
     * @param ResponseInterface $response
     * 
     * @return Response
     */
    public static function buildFromPsrResponse(ResponseInterface $response): \Jiji\Http\Response\Response
    {
        return new static(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );
    }
    /**
     * Build to json.
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
    
    
    /**
     * Build to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = json_decode($this->getBodyContents(), true);
        if (JSON_ERROR_NONE === json_last_error()) {
            return (array) $array;
        }
        return [];
    }


    /**
     * @return object
     */
    public function toObject(): object
    {
        return json_decode($this->getBodyContents());
    }


    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getBodyContents();
    }
}