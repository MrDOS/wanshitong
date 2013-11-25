<?php namespace wanshitong\controllers;

use \wanshitong\controllers\Controller;
use \wanshitong\models\Staff;
use \wanshitong\views\MessageView;

/**
 * Handle user logins.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class LoginController extends Controller
{
    /**
     * @var \wanshitong\models\Staff
     */
    private $staff_repository;

    /**
     * Construct the controller.
     *
     * @param \wanshitong\models\Staff $staff_repository a staff repository
     */
    public function __construct($staff_repository)
    {
        $this->staff_repository = $staff_repository;
    }

    /**
     * This controller doesn't handle GET requests.
     *
     * @param array $get GET parameters
     */
    public function get($get)
    {
        $view = new MessageView('Login Failure', 'Please try to log in again.');
        $view->render();
    }

    /**
     * Process the login
     *
     * @param array $get GET parameters
     * @param array $post POSTed values
     */
    public function post($get, $post)
    {
        if (empty($post['user']))
        {
            $view = new MessageView('Login Failure', 'Username must not be blank.');
            return $view->render();
        }

        if (empty($post['pass']))
        {
            $view = new MessageView('Login Failure', 'Password must not be blank.');
            return $view->render();
        }

        $staff = $this->staff_repository->getStaffByUsername($post['user']);
        if ($staff === false || sha1($staff->password_salt . $post['pass']) !== $staff->password)
        {
            $view = new MessageView('Login Failure', 'Nonexistant user or incorrect password.');
            return $view->render();
        }

        $_SESSION['user'] = $staff;
        header('Location: ' . ROOT_URL);
    }
}