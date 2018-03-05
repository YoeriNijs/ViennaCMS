<?php

namespace admin;

use BaseController;

class BaseAdminController extends BaseController {

    function beforeroute() {
        $this->checkForCSRFAttack();
        $this->checkMenuItems();
        $this->checkLogin();
    }

    function render() {
        $this->f3->set('admview', 'admin/create_blogpost.htm');
        $this->f3->set('view', 'pages/admin.htm');
        $template = new \Template();
        echo $template->render('layout.htm');
    }
}