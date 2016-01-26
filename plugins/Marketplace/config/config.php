<?php

use Interop\Container\ContainerInterface;
use Piwik\Option;
use Piwik\Plugins\Marketplace\Api\Service;

return array(
    'MarketplaceEndpoint' => 'http://plugins.piwik.org',
    'Piwik\Plugins\Marketplace\Api\Service' => function (ContainerInterface $c) {
        /** @var \Piwik\Plugins\Marketplace\Api\Service $previous */

        $domain = $c->get('MarketplaceEndpoint');
        $updater = $c->get('Piwik\Plugins\CoreUpdater\Updater');

        if (0 && $updater->isUpdatingOverHttps()) {
            $domain = str_replace('http://', 'https://', $this->domain);
        }

        $service = new Service($domain);

        $accessToken = Option::get('marketplace_license_key');
        $previous->authenticate($accessToken);

        return $service;
    }
);