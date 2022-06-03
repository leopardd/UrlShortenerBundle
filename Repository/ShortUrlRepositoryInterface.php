<?php

namespace Leopardd\Bundle\UrlShortenerBundle\Repository;

use Leopardd\Bundle\UrlShortenerBundle\Entity\ShortUrlInterface;

/**
 * Interface ShortUrlRepositoryInterface
 * @package Leopardd\Bundle\UrlShortenerBundle\Repository
 */
interface ShortUrlRepositoryInterface
{
    /**
     * @param int $id record id
     * @return ShortUrlInterface|null
     */
    public function findOneById($id);

    /**
     * @param string $url
     * @return ShortUrlInterface|null
     */
    public function findOneByUrl($url);

    /**
     * @param ShortUrlInterface $shortUrl
     * @return ShortUrlInterface
     */
    public function save(ShortUrlInterface $shortUrl);
}
