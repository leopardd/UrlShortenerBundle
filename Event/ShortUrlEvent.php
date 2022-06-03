<?php

namespace Leopardd\Bundle\UrlShortenerBundle\Event;

/**
 * Class ShortUrlEvents
 * @package Leopardd\Bundle\UrlShortenerBundle\Event
 */
class ShortUrlEvent
{
    const SHORT_URL_CREATED = 'leopardd_url_shortener.created';
    const SHORT_URL_REDIRECTED = 'leopardd_url_shortener.redirected';
}
