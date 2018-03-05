<?php

namespace admin;

use Logs;
use Page;

class PageController extends BaseAdminController {

    function create() {
        $this->f3->set('admview', 'admin/create_page.htm');
        $this->f3->set('view', 'pages/admin.htm');
        $template = new \Template();
        echo $template->render('layout.htm');
    }

    function all() {
        $model = new Page($this->database);
        $pages = $model->all();
        $this->f3->set('pages', $pages);
        $this->f3->set('admview', 'admin/list_pages.htm');
        $this->f3->set('view', 'pages/admin.htm');
        $template = new \Template();
        echo $template->render('layout.htm');
    }

    function edit() {
        $id = $this->f3->get('PARAMS.item');
        if(is_null($id)) {
            return;
        }
        $model = new Page($this->database);
        $page = $model->getByID($id)[0];
        $this->f3->set('page', $page);
        $this->f3->set('admview', 'admin/edit_page.htm');
        $this->f3->set('view', 'pages/admin.htm');
        $template = new \Template();
        echo $template->render('layout.htm');
    }

    function delete() {
        $id = $this->f3->get('PARAMS.item');
        if(is_null($id)) {
            return;
        }
        $model = new Page($this->database);
        $model->delete($id);
        $this->all();
    }

    function post() {
        $title = $this->f3->get('POST.title');
        $body = $this->f3->get('POST.body');

        if(empty($title) || empty($body)) {
            \Flash::instance()->addMessage($this->f3->get('messages.admin.invalid.page'), 'error');
            $this->f3->reroute('/page/all');
            return;
        }

        $page = new Page($this->database);
        $page->title = $title;
        $page->body = $body;
        $page->datetime = date('Y-m-d H:i:s');
        $page->save();

        $logger = new \Log(Logs::SYSTEM);
        $logger->write("Created new page with title " . $title);

        \Flash::instance()->addMessage($this->f3->get('messages.admin.success.page'), 'success');
        $this->f3->reroute('/page/all');
    }
}