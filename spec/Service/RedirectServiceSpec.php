<?php

namespace spec\Leopardd\Bundle\UrlShortenerBundle\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Hashids\Hashids;
use Leopardd\Bundle\UrlShortenerBundle\Entity\ShortUrlInterface;
use Leopardd\Bundle\UrlShortenerBundle\Event\ShortUrlEvent;
use Leopardd\Bundle\UrlShortenerBundle\Event\ShortUrlRedirectedEvent;
use Leopardd\Bundle\UrlShortenerBundle\Exception\InvalidCodeException;
use Leopardd\Bundle\UrlShortenerBundle\Repository\ShortUrlRepositoryInterface;
use Leopardd\Bundle\UrlShortenerBundle\Service\RedirectService;

/**
 * Class RedirectServiceSpec
 * @package spec\Leopardd\Bundle\UrlShortenerBundle\Service
 */
class RedirectServiceSpec extends ObjectBehavior
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
        $this->shouldHaveType(RedirectService::class);
    }

    function it_get_redirect_response_by_invalid_code(
        Hashids $hashids
    ) {
        $id = 'not numeric';
        $code = 'abc';

        $hashids->decode($code)
            ->shouldBeCalled()
            ->willReturn($id);

        $this->shouldThrow(InvalidCodeException::class)->duringGetRedirectResponse($code);
    }

    function it_get_redirect_response_by_nonexistent_code(
        Hashids $hashids,
        ShortUrlRepositoryInterface $shortUrlRepository
    ) {
        $ids = [8];
        $id = $ids[0];
        $code = 'abc';

        $hashids->decode($code)
            ->shouldBeCalled()
            ->willReturn($ids);
        $shortUrlRepository->findOneById($id)
            ->shouldBeCalled()
            ->willReturn(null);

        $this->getRedirectResponse($code)->shouldReturn(null);
    }

    function it_get_redirect_response(
        EventDispatcherInterface $dispatcher,
        Hashids $hashids,
        ShortUrlInterface $shortUrl,
        ShortUrlRepositoryInterface $shortUrlRepository
    ) {
        $ids = [8];
        $id = $ids[0];
        $url = 'https://www.google.com';
        $code = 'abc';
        $redirectResponse = new RedirectResponse($url);

        $hashids->decode($code)
            ->shouldBeCalled()
            ->willReturn($ids);
        $shortUrlRepository->findOneById($id)
            ->shouldBeCalled()
            ->willReturn($shortUrl);
        $dispatcher->dispatch(ShortUrlEvent::SHORT_URL_REDIRECTED, Argument::type(ShortUrlRedirectedEvent::class))
            ->shouldBeCalled();
        $shortUrl->getUrl()
            ->shouldBeCalled()
            ->willReturn($url);

        $this->getRedirectResponse($code)->shouldBeLike($redirectResponse);
    }
}
