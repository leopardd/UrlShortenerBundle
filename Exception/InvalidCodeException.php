<?php

namespace Leopardd\Bundle\UrlShortenerBundle\Exception;

use \Exception as Exception;

class InvalidCodeException extends Exception
{
    /**
     * InvalidCodeException constructor.
     * @param string $message
     */
    public function __construct($message = 'Code is not valid')
    {
        parent::__construct($message);
    }
}
