<?php

namespace Leopardd\Bundle\UrlShortenerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Leopardd\Bundle\UrlShortenerBundle\Factory\ShortUrlFactory;
use Leopardd\Bundle\UrlShortenerBundle\Service\EncodeService;
use Leopardd\Bundle\UrlShortenerBundle\Exception\InvalidUrlException;

class EncodeController extends Controller
{
    /**
     * @param Request $request
     * @throws InvalidUrlException
     * @return JsonResponse
     */
    public function indexAction(Request $request)
    {
        /** @var ShortUrlFactory $shortUrlFactory */
        $shortUrlFactory = $this->get('leopardd_url_shortener.factory.short_url');

        /** @var EncodeService $encodeService */
        $encodeService = $this->get('leopardd_url_shortener.service.encode');

        $url = $request->request->get('url');

        // validate and sanitize
        if (filter_var($url, FILTER_VALIDATE_URL) === false) throw new InvalidUrlException();
        $url = rtrim($url, '/');

        $shortUrl = $shortUrlFactory->create($url);
        $shortUrl = $encodeService->process($shortUrl);

        return new JsonResponse([
            'url' => $shortUrl->getUrl(),
            'code' => $shortUrl->getCode()
        ]);
    }
}
