<?php


namespace Service\SocialNetwork\Contract;


interface SocialNetworkInterface
{
    public const SOCIAL_NETWORK_TWITTER = 'twitter';
    public const SOCIAL_NETWORK_FACEBOOK = 'facebook';

    public function authenticate(array $options);

    public function publishing($msg, $url);
}
