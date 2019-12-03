<?php

namespace Faxity\DI;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * DI module for creating and rendering flash messages
 */
class Flash implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * @var array $messages Messages buffer
     * @var string $template Anax view template
     * @var string $region Anax page region
     */
    private $messages = [];
    private $template;
    private $region;


    /**
     * Adds message to buffer
     * @param string $type Message type (ok, error or warning)
     * @param string $text Message text, not required
     */
    private function message(string $type, string $text)
    {
        $this->messages[] = (object) [
            "type" => $type,
            "text" => $text,
        ];
    }


    /**
     * @param string $template Template to render messages
     * @param string|null $region The region to render to, defaults to "flash"
     */
    public function __construct(string $template, ?string $region = null)
    {
        $this->template = $template;
        $this->region = $region ?? "flash";
    }


    /**
     * Gets all messages within the buffer
     *
     * @return array
     */
    public function getMessages() : array
    {
        return $this->messages;
    }


    /**
     * Renders flash messages into an Anax view
     */
    public function render()
    {
        // Pass messages by reference, as all messages are created after this function call
        $data = [ "messages" => &$this->messages ];
        $this->di->view->add($this->template, $data, $this->region);
    }


    /**
     * Adds an ok message to render
     * @param string $text Message text, not required
     */
    public function ok(string $text)
    {
        $this->message("ok", $text);
    }


    /**
     * Adds a warning message to render
     * @param string $text Message text, not required
     */
    public function warn(string $text)
    {
        $this->message("warn", $text);
    }


    /**
     * Adds an error message to render
     * @param string $text Message text, not required
     */
    public function err(string $text) : void
    {
        $this->message("err", $text);
    }
}
