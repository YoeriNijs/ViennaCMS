<?php

namespace security;

use BaseController;
use Logs;
use Template;
use User;

class AuthController extends BaseController {

    function beforeroute() {}

    function render() {
        $this->f3->set('view', 'pages/login.htm');
        $template = new Template();
        echo $template->render('layout.htm');
    }
        
    function authenticate() {
        $this->checkForCSRFAttack();

        $username = $this->f3->get('POST.username');
        $password = $this->f3->get('POST.password');

        $user = new User($this->database);
        $user->getByUsername($username);

        $logger = new \Log(Logs::AUTH);
        $logger->write("User " . $username . " is trying to login.");

        if(!$user->dry() && password_verify($password, $user->password)) {
            $this->f3->set('SESSION.username', $user->username);
            $this->f3->set('SESSION.id', $user->id);
            $this->f3->set('SESSION.display_name', $user->display_name);
            $this->f3->copy('CSRF','SESSION.csrf'); // Save actual token against CSRF in session
            $logger->write($username . " logged in successfully."); // Log login result
            $this->f3->reroute('/admin');
        }

        $logger->write("Login failed for " . $username . ": invalid password.");
        \Flash::instance()->addMessage($this->f3->get('messages.login.invalid') . '.', 'toast-warning');
        $this->f3->reroute('/login');
    }

    /**
     * Handles everything related to logging off the user from the
     * application.
     */
    function logOff() {
        $username = $this->f3->get('SESSION.username');

        if($username !== null) {
            // Clear the session
            $this->f3->clear('SESSION');

            // Store new csrf token in session
            $this->f3->copy('CSRF','SESSION.csrf');

            // Log action
            $logger = new \Log(Logs::AUTH);
            $logger->write("User " . $username . " logged off successfully.");
        }

        // Reroute to login page
        $this->f3->reroute('/login');
    }
}