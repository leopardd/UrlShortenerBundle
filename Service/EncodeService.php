<?php

namespace Leopardd\Bundle\UrlShortenerBundle\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Hashids\Hashids;
use Leopardd\Bundle\UrlShortenerBundle\Entity\ShortUrlInterface;
use Leopardd\Bundle\UrlShortenerBundle\Repository\ShortUrlRepositoryInterface;
use Leopardd\Bundle\UrlShortenerBundle\Event\ShortUrlEvent;
use Leopardd\Bundle\UrlShortenerBundle\Event\ShortUrlCreatedEvent;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class EncodeService
 * @package Leopardd\Bundle\UrlShortenerBundle\Service
 */
class EncodeService
{

    /**
     * ProcessShortUrlService constructor.
     * @param Hashids $hashids
     * @param ShortUrlRepositoryInterface $shortUrlRepository
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(private Hashids $hashids, private ShortUrlRepositoryInterface $shortUrlRepository, private EventDispatcherInterface $dispatcher,private RouterInterface $router)
    {

    }


	public function getShortUrl($shortUrl,$referenceType = RouterInterface::ABSOLUTE_URL) // absolute url
	{

		return $this->router->generate('leopardd_url_shortener_redirect',['code' => $shortUrl->getCode() ] , $referenceType) ;

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