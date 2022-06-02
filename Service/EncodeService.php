<?php

namespace Leopardd\Bundle\UrlShortenerBundle\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Hashids\Hashids;
use Leopardd\Bundle\UrlShortenerBundle\Entity\ShortUrlInterface;
use Leopardd\Bundle\UrlShortenerBundle\Repository\ShortUrlRepositoryInterface;
use Leopardd\Bundle\UrlShortenerBundle\Event\ShortUrlEvent;
use Leopardd\Bundle\UrlShortenerBundle\Event\ShortUrlCreatedEvent;

/**
 * Class EncodeService
 * @package Leopardd\Bundle\UrlShortenerBundle\Service
 */
class EncodeService
{
    /** @var Hashids */
    private $hashids;

    /** @var ShortUrlRepositoryInterface */
    private $shortUrlRepository;

    /** @var EventDispatcherInterface */
    private $dispatcher;

    /**
     * ProcessShortUrlService constructor.
     * @param Hashids $hashids
     * @param ShortUrlRepositoryInterface $shortUrlRepository
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct($hashids, $shortUrlRepository, $dispatcher)
    {
        $this->hashids = $hashids;
        $this->shortUrlRepository = $shortUrlRepository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param ShortUrlInterface $shortUrl
     * @return ShortUrlInterface
     */
    public function process($shortUrl)
    {
        $existentShortUrl = $this->shortUrlRepository->findOneByUrl($shortUrl->getUrl());
        if ($existentShortUrl) return $existentShortUrl;

        $this->shortUrlRepository->save($shortUrl);

        $shortUrlId = $shortUrl->getId();
        $code = $this->hashids->encode($shortUrlId);

        $shortUrl->setCode($code);
        $shortUrl = $this->shortUrlRepository->save($shortUrl);

        $event = new ShortUrlCreatedEvent($shortUrl);
        $this->dispatcher->dispatch($event,ShortUrlEvent::SHORT_URL_CREATED);

        return $shortUrl;
    }
}