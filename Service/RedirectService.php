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

    /**
     * ProcessShortUrlService constructor.
     * @param Hashids $hashids
     * @param ShortUrlRepositoryInterface $shortUrlRepository
     * @param EventDispatcherInterface $dispatcher
     */
	public function __construct(private Hashids $hashids, private ShortUrlRepositoryInterface $shortUrlRepository, private EventDispatcherInterface $dispatcher)
    {

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
        $this->dispatcher->dispatch($event,ShortUrlEvent::SHORT_URL_REDIRECTED);

        return new RedirectResponse($shortUrl->getUrl());
    }
}