<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\Marketplace\Api;

use Piwik\Cache;
use Piwik\Http as PiwikHttp;

/**
 *
 */
class Client
{
    const CACHE_TIMEOUT_IN_SECONDS = 1200;
    const HTTP_REQUEST_TIMEOUT = 60;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var null|string
     */
    private $accessToken;

    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    public function authenticate($accessToken)
    {
        if (empty($accessToken)) {
            $this->accessToken = null;
        } elseif (ctype_xdigit($accessToken)) {
            $this->accessToken = $accessToken;
        }
    }

    public function fetch($action, $params)
    {
        $query = http_build_query($params);

        $endpoint = $this->domain . '/api/2.0/';

        $url = sprintf('%s%s?%s', $endpoint, $action, $query);

        if ($this->accessToken) {
            $url .= '&access_token=' . $this->accessToken;
        }

        $response = PiwikHttp::sendHttpRequest($url, static::HTTP_REQUEST_TIMEOUT, $userAgent = null,
                                               $destinationPath = null,
                                               $followDepth = 0,
                                               $acceptLanguage = false,
                                               $byteRange = false,
                                               $getExtendedInfo = false,
                                               $httpMethod = 'POST');
        $result = json_decode($response, true);

        if (is_null($result)) {
            $message = sprintf('There was an error reading the response from the Marketplace: %s. Please try again later.',
                substr($response, 0, 50));
            throw new Client\Exception($message);
        }

        if (!empty($result['error'])) {
            throw new Client\Exception($result['error']);
        }

        return $result;
    }

    public function getDomain()
    {
        return $this->domain;
    }


}
