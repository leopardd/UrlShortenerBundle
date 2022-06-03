<?php

namespace Leopardd\Bundle\UrlShortenerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Leopardd\Bundle\UrlShortenerBundle\Service\RedirectService;
use Leopardd\Bundle\UrlShortenerBundle\Exception\InvalidCodeException;

class RedirectController extends AbstractController
{

	public function __construct(private RedirectService $redirectService)
	{

	}
    /**
     * @param string $code
     * @throws InvalidCodeException
     * @return RedirectResponse
     */
    public function indexAction($code)
    {

        $response = $this->redirectService->getRedirectResponse($code);
        if ($response === null) throw new InvalidCodeException();

        return $response;
    }
}