<?php


namespace Service\SocialNetwork\Contract;


use Service\SocialNetwork\Adapters\FacebookAdapter;
use Service\SocialNetwork\Adapters\TwitterAdapter;

class SocialNetworkService
{
    public function create(string $socialNetwork, string $params): void
    {
        switch ($socialNetwork) {
            case SocialNetworkInterface::SOCIAL_NETWORK_TWITTER:
                $socialNetworkAdapter = new TwitterAdapter();
                break;
            case SocialNetworkInterface::SOCIAL_NETWORK_FACEBOOK:
                $socialNetworkAdapter = new FacebookAdapter();
                break;
            default;
        }

        $this->sendMessage($socialNetworkAdapter, $params);
    }

    /**
     * Отправка сообщения в соц.сеть
     * @param SocialNetworkInterface $socialNetwork
     * @param string $params
     * @return void
     */
    protected function sendMessage(SocialNetworkInterface $socialNetwork, string $params): void
    {
        $socialNetwork->publishing($params, 'pageURL');
    }
}