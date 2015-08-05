<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gateways;

use Psr\Http\Message\ResponseInterface;
use WNowicki\Generic\ApiClient\AbstractApiClient;
use WNowicki\Generic\ApiClient\ErrorResponseException;
use WNowicki\Generic\ApiClient\WrongResponseException;

/**
 * Class ProviderApiClient
 *
 * @author WN
 * @package App\Gateways
 */
class ProviderApiClient extends AbstractApiClient
{
    /**
     * @author WN
     * @param $baseUrl
     * @param $token
     * @return ProviderApiClient
     */
    public static function make($baseUrl, $token = '')
    {
        $ar = [];
        $ar['base_uri'] = $baseUrl;

        if ($token != '') {

            $ar['headers'] = ['Authorization' => 'ApiToken token="' . $token . '"'];
        }

        return new self($ar, \Log::getMonolog());
    }

    /**
     * @author WN
     * @param array $body
     * @return array
     */
    protected function processRequestBody(array $body)
    {
        return ['json' => $body];
    }

    /**
     * @author WN
     * @param ResponseInterface $response
     * @return array
     * @throws WrongResponseException
     */
    protected function processResponse(ResponseInterface $response)
    {
        $responseBody = json_decode($response->getBody()->getContents(), true);

        if ($responseBody !== false) {
            return $responseBody;
        }
        throw new WrongResponseException('Response body was malformed JSON', $response->getStatusCode());
    }

    /**
     * @author WN
     * @param ResponseInterface $response
     * @throws ErrorResponseException
     */
    protected function processErrorResponse(ResponseInterface $response)
    {
        if (($responseBody = json_decode($response->getBody()->getContents(), true)) &&
            array_key_exists('message', $responseBody)
        ) {
            throw new ErrorResponseException($responseBody['message'], $response->getStatusCode());
        }
    }
}
