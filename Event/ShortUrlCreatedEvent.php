<?php

namespace Leopardd\Bundle\UrlShortenerBundle\Event;

use Leopardd\Bundle\UrlShortenerBundle\Entity\ShortUrlInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class ShortUrlCreatedEvent
 * @package Leopardd\Bundle\UrlShortenerBundle\Event
 */
class ShortUrlCreatedEvent extends Event
{
    /** @var ShortUrlInterface */
    private $shortUrl;

    /**
     * ShortUrlCreatedEvent constructor.
     * @param ShortUrlInterface $shortUrl
     */
    public function __construct(ShortUrlInterface $shortUrl)
    {
        $this->shortUrl = $shortUrl;
    }

    /**
     * @return ShortUrlInterface
     */
    public function getShortUrl()
    {
        return $this->shortUrl;
    }
}