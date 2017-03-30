<?php

namespace Leopardd\Bundle\UrlShortenerBundle\Service;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Hashids\Hashids;
use Leopardd\Bundle\UrlShortenerBundle\Repository\ShortUrlRepositoryInterface;
use Leopardd\Bundle\UrlShortenerBundle\Event\ShortUrlEvent;
use Leopardd\Bundle\UrlShortenerBundle\Event\ShortUrlRedirectedEvent;
use Leopardd\Bundle\UrlShortenerBundle\Exception\InvalidCodeException;

/**
 * Class RedirectService
 * @package Leopardd\Bundle\UrlShortenerBundle\Service
 */
class RedirectService
{
    /** @var Hashids */
    private $hashids;

    /** @var ShortUrlRepositoryInterface $repository */
    private $shortUrlRepository;

    /** @var EventDispatcherInterface $dispatcher */
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
     * @param string $code
     * @throws InvalidCodeException
     * @return RedirectResponse|null
     */
    public function getRedirectResponse($code)
    {
        $ids = $this->hashids->decode($code);
        if (!isset($ids[0]) || !is_numeric($ids[0])) throw new InvalidCodeException();
        $id = $ids[0];

        $shortUrl = $this->shortUrlRepository->findOneById($id);
        if (!$shortUrl) return null;

        $event = new ShortUrlRedirectedEvent($shortUrl);
        $this->dispatcher->dispatch(ShortUrlEvent::SHORT_URL_REDIRECTED, $event);

        return new RedirectResponse($shortUrl->getUrl());
    }
}
