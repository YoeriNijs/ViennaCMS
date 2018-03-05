<?php

namespace admin;

use Blogpost;
use Logs;

class BlogpostController extends BaseAdminController {

    function create() {
        $this->render();
    }

    function all() {
        $model = new Blogpost($this->database);
        $blogposts = $model->all();
        $this->f3->set('blogposts', $blogposts);
        $this->f3->set('admview', 'admin/list_blogposts.htm');
        $this->f3->set('view', 'pages/admin.htm');
        $template = new \Template();
        echo $template->render('layout.htm');
    }

    function edit() {
        $id = $this->f3->get('PARAMS.item');
        if(is_null($id)) {
            return;
        }
        $model = new Blogpost($this->database);
        $blogpost = $model->getByID($id)[0];
        $this->f3->set('blogpost', $blogpost);
        $this->f3->set('admview', 'admin/edit_blogpost.htm');
        $this->f3->set('view', 'pages/admin.htm');
        $template = new \Template();
        echo $template->render('layout.htm');
    }

    function delete() {
        $id = $this->f3->get('PARAMS.item');
        if(is_null($id)) {
            return;
        }
        $model = new Blogpost($this->database);
        $model->delete($id);
        $this->all();
    }

    function post() {
        $title = $this->f3->get('POST.title');
        $body = $this->f3->get('POST.body');

        if(empty($title) || empty($body)) {
            \Flash::instance()->addMessage($this->f3->get('messages.admin.invalid'), 'error');
            $this->f3->reroute('/blogpost');
            return;
        }

        $post = new \Blogpost($this->database);
        $post->title = $title;
        $post->body = $body;
        $post->datetime = date('Y-m-d H:i:s');
        $post->image = $this->validateImage();
        $post->save();

        $logger = new \Log(Logs::SYSTEM);
        $logger->write("Created new blog article with title " . $title);

        \Flash::instance()->addMessage($this->f3->get('messages.admin.success.blogpost'), 'success');
        $this->f3->reroute('/blogpost/all');
    }

    private function validateImage() {
        $targetDir = "public/image/blog/";
        $targetFile = $targetDir . basename($_FILES["blogimage"]["name"]);
        $imageFileType = pathinfo($targetFile,PATHINFO_EXTENSION);
        $logger = new \Log(Logs::SYSTEM);

        if($targetFile === $targetDir) {
            return 'noimage.png';
        }

        if($_FILES["blogimage"]["size"] > 500000) {
            $logger->write("Invalid image: too big");
            \Flash::instance()->addMessage($this->f3->get('messages.admin.invalid.image.size'), 'error');
            $this->f3->reroute('/blogpost');
            return "";
        }

        if($imageFileType != "jpg") {
            $logger->write("Invalid image: invalid type");
            \Flash::instance()->addMessage($this->f3->get('messages.admin.invalid.image.ext'), 'error');
            $this->f3->reroute('/blogpost');
            return "";
        }

        $length = 20;
        $today = date("m.d.y");
        $hash = substr(hash('md5', $today), 0, $length);
        $storedImgName = $targetDir . $hash . "." . $imageFileType;
        if(move_uploaded_file($_FILES["blogimage"]["tmp_name"], $targetFile)) {
            rename($targetFile, $storedImgName);
        } else {
            $logger->write("Invalid image: cannot move image");
            \Flash::instance()->addMessage($this->f3->get('messages.admin.cannot.upload.image'), 'error');
            $this->f3->reroute('/blogpost');
            return "";
        }

        // Resize image and delete uploaded one
        $img = new \Image($storedImgName, true);
        $img->resize(24, 24, true, true );
        file_put_contents($storedImgName, $img->dump('jpg'));

        $logger->write("Image uploaded successfully");

        return $storedImgName;
    }


}