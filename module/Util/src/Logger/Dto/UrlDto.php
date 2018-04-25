<?php

namespace Util\Logger\Dto;

use Psr\Http\Message\UriInterface;

/**
 * Class UrlDto
 *
 * @package Application\Log\Dto
 */
class UrlDto implements DtoInterface
{
    const SECTION_NAME = 'url';

    /**
     * @var string
     */
    private $scheme;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $query;

    /**
     * @var string
     */
    private $path;

    /**
     * UrlDto constructor.
     *
     * @param $scheme
     * @param $host
     * @param $path
     * @param $query
     */
    public function __construct($scheme, $host, $path, $query)
    {
        $this->scheme = (string)$scheme;
        $this->host   = (string)$host;
        $this->path   = (string)$path;
        $this->query  = (string)$query;
    }

    /**
     * @param bool $wrapIntoSection
     *
     * @return array
     */
    public function toArray($wrapIntoSection = true)
    {
        $result = [];

        foreach (get_object_vars($this) as $name => $value) {
            if (!empty($value)) {
                $result[$name] = $value;
            }
        }

        $result['generalPath'] = sprintf(
            '%s://%s/%s',
            $this->scheme,
            trim($this->host, '/'),
            ltrim($this->path, '/')
        );

        $result['url'] = !empty($this->query)
            ? sprintf('%s?%s', $result['generalPath'], $this->query)
            : $result['generalPath'];

        if (empty($result)) {
            return $result;
        }

        return $wrapIntoSection === true ? [self::SECTION_NAME => $result] : $result;
    }

    /**
     * @param UriInterface $uri
     *
     * @return static
     */
    public static function fromUri(UriInterface $uri)
    {
        $scheme = $uri->getScheme();
        $host   = $uri->getHost();
        $path   = $uri->getPath();
        $query  = $uri->getQuery();

        return new static($scheme, $host, $path, $query);
    }
}
