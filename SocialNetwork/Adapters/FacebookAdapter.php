<?php


namespace Service\SocialNetwork\Adapters;


use Service\SocialNetwork\Contract\SocialNetworkInterface;
use Service\SocialNetwork\Facebook;

class FacebookAdapter implements SocialNetworkInterface
{
    protected $service;

    const SOCIAL_NETWORK_FACEBOOK = 'facebook';

    public function __construct()
    {
        $this->service = new Facebook();
    }

    public function authenticate(array $options)
    {
         $this->service->authorizeWithFacebook();
    }

    public function publishing($msg, $url)
    {
        $this->service->postToFacebook($msg . ' ' . $url);
    }
}