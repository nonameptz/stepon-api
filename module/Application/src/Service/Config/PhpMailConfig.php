<?php

namespace Application\Service\Config;

use Zend\Stdlib\AbstractOptions;

/**
 * Class PhpMailConfig
 *
 * @package Application\Service\Config
 */
class PhpMailConfig extends AbstractOptions
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $privateStringDKIM;

    /**
     * @var string
     */
    private $selectorDKIM;

    /**
     * @var string
     */
    private $domainDKIM;

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     *
     * @return PhpMailConfig
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return PhpMailConfig
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return PhpMailConfig
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     *
     * @return PhpMailConfig
     */
    public function setFrom(string $from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrivateStringDKIM()
    {
        return $this->privateStringDKIM;
    }

    /**
     * @param string $privateStringDKIM
     *
     * @return PhpMailConfig
     */
    public function setPrivateStringDKIM($privateStringDKIM)
    {
        $this->privateStringDKIM = $privateStringDKIM;
        return $this;
    }

    /**
     * @return string
     */
    public function getSelectorDKIM()
    {
        return $this->selectorDKIM;
    }

    /**
     * @param string $selectorDKIM
     *
     * @return PhpMailConfig
     */
    public function setSelectorDKIM($selectorDKIM)
    {
        $this->selectorDKIM = $selectorDKIM;
        return $this;
    }

    /**
     * @return string
     */
    public function getDomainDKIM()
    {
        return $this->domainDKIM;
    }

    /**
     * @param string $domainDKIM
     *
     * @return PhpMailConfig
     */
    public function setDomainDKIM($domainDKIM)
    {
        $this->domainDKIM = $domainDKIM;
        return $this;
    }
}