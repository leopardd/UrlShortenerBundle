<?php

namespace Leopardd\Bundle\UrlShortenerBundle\Factory;

use Leopardd\Bundle\UrlShortenerBundle\Entity\ShortUrlInterface;

/**
 * Class ShortUrlFactory
 * @package Leopardd\Bundle\UrlShortenerBundle\Factory
 */
class ShortUrlFactory implements ShortUrlFactoryInterface
{
    /** @var ShortUrlInterface */
    protected $shortUrl;

    /**
     * ShortUrlFactory constructor.
     * @param string $shortUrl shortUrl class
     */
    public function __construct($shortUrl)
    {
        $this->shortUrl = new $shortUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function create($url)
    {
        $this->shortUrl->setUrl($url);

        return $this->shortUrl;
    }
}
