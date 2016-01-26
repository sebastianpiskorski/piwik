<?php

use Interop\Container\ContainerInterface;
use Piwik\Option;
use Piwik\Plugins\Marketplace\Api\Client;

return array(
    'MarketplaceEndpoint' => 'http://plugins.piwik.org',
    'Piwik\Plugins\Marketplace\Api\Client' => function (ContainerInterface $c) {
        /** @var \Piwik\Plugins\Marketplace\Api\Client $previous */

        $domain = $c->get('MarketplaceEndpoint');
        $updater = $c->get('Piwik\Plugins\CoreUpdater\Updater');

        if (0 && $updater->isUpdatingOverHttps()) {
            $domain = str_replace('http://', 'https://', $this->domain);
        }

        $client = new Client($domain);

        $accessToken = Option::get('marketplace_license_key');
        $previous->authenticate($accessToken);

        return $client;
    }
);