<?php namespace wanshitong\views;

use wanshitong\TemplateManager;

/**
 * A simple message.
 *
 * @author kmacphail
 * @version 1.0.0
 * @since 1.0.0
 */
class MessageView extends PageView
{
    public function __construct($message_title, $message)
    {
        parent::__construct($message_title, <<<HTML
<h2>{$message_title}</h2>
<p>{$message}</p>
HTML
        );
    }
}