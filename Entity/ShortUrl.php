<?php

namespace Leopardd\Bundle\UrlShortenerBundle\Entity;

use \DateTime as DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Class ShortUrl
 * @package Leopardd\Bundle\UrlShortenerBundle\Entity
 * @ORM\Entity(repositoryClass="Leopardd\Bundle\UrlShortenerBundle\Repository\ShortUrlRepository")
 * @ORM\Table(name="short_url", indexes={@ORM\Index(name="idx", columns={"id", "url"})})
 */
class ShortUrl implements ShortUrlInterface
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose()
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank(message="The code should not be blank")
     * @ORM\Column(name="code", type="string", unique=true, nullable=true, options={"collation":"utf8_bin"})
     * @JMS\Expose()
     */
    protected $code;

    /**
     * @var string
     * @Assert\NotBlank(message="The url should not be blank")
     * @Assert\Length(
     *   max = 2000,
     *   maxMessage = "The url cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(name="url", type="string", length=2000)
     * @JMS\Expose()
     */
    protected $url;

    /**
     * @var DateTime
     * @ORM\Column(name="created", type="datetime")
     * @JMS\Type("DateTime<'Y-m-d H:i:s', 'UTC'>")
     * @JMS\Expose()
     */
    private $created;

    /**
     * ShortUrl constructor.
     */
    public function __construct()
    {
        $this->created = new DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * {@inheritdoc}
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param DateTime $created
     * @return ShortUrl
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }
}