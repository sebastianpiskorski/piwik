<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\Marketplace;

use Exception;
use Piwik\Container\StaticContainer;
use Piwik\Option;
use Piwik\Piwik;
use Piwik\Plugins\Marketplace\Api\Client;

/**
 * API for plugin Marketplace
 *
 * @method static \Piwik\Plugins\Marketplace\API getInstance()
 */
class API extends \Piwik\Plugin\API
{
    /**
     * @var Client
     */
    private $marketplaceClient;

    public function __construct(Client $client)
    {
        $this->marketplaceClient = $client;
    }

    public function saveLicenseKey($licenseKey)
    {
        Piwik::checkUserHasSuperUserAccess();

        $this->marketplaceClient->authenticate($licenseKey);

        try {
            $consumer = $this->marketplaceClient->fetch('consumer', array());
        } catch (Api\Client\Exception $e) {
            $consumer = false;
        }

        if (empty($consumer['name'])) {
            throw new Exception('Entered license key is not valid');
        }

        Option::set('marketplace_license_key', $licenseKey);

        return true;
    }

}
