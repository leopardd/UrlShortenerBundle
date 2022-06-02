<?php

namespace Leopardd\Bundle\UrlShortenerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Leopardd\Bundle\UrlShortenerBundle\Service\RedirectService;
use Leopardd\Bundle\UrlShortenerBundle\Exception\InvalidCodeException;

class RedirectController extends AbstractController
{
    /**
     * @param string $code
     * @throws InvalidCodeException
     * @return RedirectResponse
     */
    public function indexAction($code)
    {
        /** @var RedirectService $redirectService */
        $redirectService = $this->get('leopardd_url_shortener.service.redirect');

        $response = $redirectService->getRedirectResponse($code);
        if ($response === null) throw new InvalidCodeException();

        return $response;
    }
}