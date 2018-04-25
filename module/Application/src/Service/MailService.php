<?php

namespace Application\Service;

use Application\Service\Config\PhpMailConfig;
use PHPMailer\PHPMailer\PHPMailer;
use Zend\View\Renderer\RendererInterface;

/**
 * Class MailService
 *
 * @package Application\Service
 */
class MailService extends PHPMailer
{
    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * @var PhpMailConfig
     */
    protected $config;

    /**
     * MailService constructor.
     *
     * @param RendererInterface $renderer
     * @param PhpMailConfig     $config
     */
    public function __construct(RendererInterface $renderer, PhpMailConfig $config)
    {
        parent::__construct(true);
        $this->renderer = $renderer;
        $this->config   = $config;
        $this->init();
    }

    /**
     * @param string $address
     * @param string $name
     *
     * @return $this
     */
    public function addTo($address, $name = '')
    {
        $this->addAddress($address, $name);

        return $this;
    }

    /**
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->Subject = $subject;

        return $this;
    }

    /**
     * @param string $template
     * @param array  $vars
     *
     * @return $this
     */
    public function assembleBody($template, array $vars = [])
    {
        $body = $this->renderer->render($template, $vars);
        $this->setBody($body);

        return $this;
    }

    /**
     * @param $body
     *
     * @return $this
     */
    public function setBody($body)
    {
        $this->Body = $body;

        return $this;
    }

    private function init()
    {
        $this->SMTPDebug = 0;
        $this->isSMTP();
        $this->Host       = $this->config->getHost();
        $this->SMTPAuth   = true;
        $this->Username   = $this->config->getUsername();
        $this->Password   = $this->config->getPassword();
        $this->SMTPSecure = 'ssl';
        $this->Port       = 465;
        $this->CharSet    = 'UTF-8';
        $this->isHTML(true);
        $this->setFrom($this->config->getFrom(), 'Кинотеатр Калевала');
        $this->DKIM_domain         = $this->config->getDomainDKIM();
        $this->DKIM_selector       = $this->config->getSelectorDKIM();
        $this->DKIM_private_string = $this->config->getPrivateStringDKIM();
        $this->DKIM_passphrase     = '';
        $this->DKIM_identity       = $this->From;
    }
}
