<?php


namespace Service\SocialNetwork;


class Twitter
{
    protected $authorizeUrl = 'http://twitter.com/oauth/authorize';
    protected $apiUrl = 'http://twitter.com';

    public function authorize($apiKey)
    {
        //какие-то дейтсвия
    }

    public function tweet($params)
    {
        //какие-то действия
    }
}