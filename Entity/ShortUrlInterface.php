<?php

namespace Leopardd\Bundle\UrlShortenerBundle\Entity;

/**
 * Interface ShortUrlInterface
 * @package Leopardd\Bundle\UrlShortenerBundle\Entity
 */
interface ShortUrlInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getCode();

    /**
     * @param string $code
     * @return ShortUrlInterface
     */
    public function setCode($code);

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param string $url
     * @return ShortUrlInterface
     */
    public function setUrl($url);
}
