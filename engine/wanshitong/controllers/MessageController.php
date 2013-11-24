<?php namespace wanshitong\controllers;

use wanshitong\controllers\Controller;
use wanshitong\views\MessageView;

/**
 * A controller whose sole job is to display a static message.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class MessageController extends Controller
{
    /**
     * @var string the message title
     */
    private $message_title;
    /**
     * @var string the message body
     */
    private $message;

    /**
     * Construct the controller.
     *
     * @param string $message_title the message title
     * @param string $message the message body
     */
    public function __construct($message_title, $message)
    {
        $this->message_title = $message_title;
        $this->message = $message;
    }

    /**
     * Display the book list.
     *
     * @param array $get GET parameters
     */
    public function get($get)
    {
        $view = new MessageView($this->message_title, $this->message);
        $view->render();
    }
}