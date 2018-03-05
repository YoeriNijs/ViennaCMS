<?php

class MainController extends BaseController {

    /**
     * This renders *the* main Vienna page.
     */
    function render() {
        $model = new Blogpost($this->database);
        $blogposts = $model->all();
        foreach($blogposts as $blogpost) {
            $body = $blogpost->body;
            if(strlen($body) > 250)
                $blogpost->body = substr($blogpost->body,0,250) . '...';
        }
        $this->f3->set('blogposts', $blogposts);
        $this->f3->set('view', 'pages/index.htm');
        $template = new Template();
        echo $template->render('layout.htm');
    }

    function renderPost() {
        $id = $this->f3->get('PARAMS.item');
        if(is_null($id)) {
            return;
        }
        $model = new Blogpost($this->database);
        $blogpost = $model->getByID($id)[0];
        $this->f3->set('blogpost', $blogpost);
        $this->f3->set('view', 'pages/blogpost.htm');
        $template = new Template();
        echo $template->render('layout.htm');
    }

    function renderPage() {
        $id = $this->f3->get('PARAMS.item');
        if(is_null($id)) {
            return;
        }
        $model = new Page($this->database);
        $page = $model->getByID($id)[0];
        $this->f3->set('page', $page);
        $this->f3->set('view', 'pages/page.htm');
        $template = new Template();
        echo $template->render('layout.htm');
    }
}