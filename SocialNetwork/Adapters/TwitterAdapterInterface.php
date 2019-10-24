<?php


namespace Service\SocialNetwork\Adapters;


use Service\SocialNetwork\Contract\SocialNetworkInterface;
use Service\SocialNetwork\Twitter;

class TwitterAdapter implements SocialNetworkInterface
{
    protected $service;

    const SOCIAL_NETWORK_TWITTER = 'twitter';

    public function __construct()
    {
        $this->service = new Twitter();
    }

    public function authenticate(array $options)
    {
        $apiKey = $options['api_key'];
        $this->service->authorize($apiKey);
    }

    public function publishing($msg, $url)
    {
        $this->service->tweet($msg . ' ' . $url);
    }
}