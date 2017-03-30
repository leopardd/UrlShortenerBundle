<?php

namespace spec\Leopardd\Bundle\UrlShortenerBundle\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Hashids\Hashids;
use Leopardd\Bundle\UrlShortenerBundle\Entity\ShortUrlInterface;
use Leopardd\Bundle\UrlShortenerBundle\Event\ShortUrlEvent;
use Leopardd\Bundle\UrlShortenerBundle\Event\ShortUrlCreatedEvent;
use Leopardd\Bundle\UrlShortenerBundle\Repository\ShortUrlRepositoryInterface;
use Leopardd\Bundle\UrlShortenerBundle\Service\EncodeService;

/**
 * Class EncodeServiceSpec
 * @package spec\Leopardd\Bundle\UrlShortenerBundle\Service
 */
class EncodeServiceSpec extends ObjectBehavior
{
    function let(
        Hashids $hashids,
        ShortUrlRepositoryInterface $shortUrlRepository,
        EventDispatcherInterface $dispatcher
    ) {
        $this->beConstructedWith($hashids, $shortUrlRepository, $dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EncodeService::class);
    }

    function it_process_existent_url(
        ShortUrlInterface $shortUrl,
        ShortUrlInterface $existentShortUrl,
        ShortUrlRepositoryInterface $shortUrlRepository
    ) {
        $url = 'https://www.google.com';

        $shortUrl->getUrl()
            ->shouldBeCalled()
            ->willReturn($url);
        $shortUrlRepository->findOneByUrl($url)
            ->shouldBeCalled()
            ->willReturn($existentShortUrl);

        $this->process($shortUrl)->shouldReturn($existentShortUrl);
    }

    function it_process(
        EventDispatcherInterface $dispatcher,
        Hashids $hashids,
        ShortUrlInterface $shortUrl,
        ShortUrlRepositoryInterface $shortUrlRepository
    ) {
        $id = 8;
        $url = 'https://www.google.com';
        $code = 'abc';

        $shortUrl->getUrl()
            ->shouldBeCalled()
            ->willReturn($url);
        $shortUrlRepository->findOneByUrl($url)
            ->shouldBeCalled()
            ->willReturn(null);
        $shortUrlRepository->save($shortUrl)
            ->shouldBeCalledTimes(2)
            ->willReturn($shortUrl);
        $shortUrl->getId()
            ->shouldBeCalled()
            ->willReturn($id);
        $hashids->encode($id)
            ->willReturn($code);
        $shortUrl->setCode($code)
            ->shouldBeCalled();

        $event = new ShortUrlCreatedEvent($shortUrl->getWrappedObject());
        $dispatcher->dispatch(ShortUrlEvent::SHORT_URL_CREATED, $event)
            ->shouldBeCalled();

        $this->process($shortUrl)->shouldReturn($shortUrl);
    }
}
