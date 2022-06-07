<?php

namespace Leopardd\Bundle\UrlShortenerBundle\Factory;

use Leopardd\Bundle\UrlShortenerBundle\Entity\ShortUrlInterface;

// ok

/**
 * Interface ShortUrlFactoryInterface
 * @package Leopardd\Bundle\UrlShortenerBundle\Factory
 */
interface ShortUrlFactoryInterface
{
    /**
     * @see http://stackoverflow.com/questions/7003416/validating-a-url-in-php
     * @param string $url
     * @return ShortUrlInterface
     */
    public function create($url);
}