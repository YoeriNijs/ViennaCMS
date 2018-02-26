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
        $model = new Blogpost($this->database);
        $blogpost = $model->getByID($id)[0];
        $this->f3->set('blogpost', $blogpost);
        $this->f3->set('view', 'pages/blogpost.htm');
        $template = new Template();
        echo $template->render('layout.htm');
    }
}