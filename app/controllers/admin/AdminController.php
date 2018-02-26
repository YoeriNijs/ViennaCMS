<?php

namespace admin;

use BaseController;
use Logs;

class AdminController extends BaseController {

    function beforeroute() {
        $this->checkForCSRFAttack();
        $this->checkLogin();
    }

    function render() {
        $this->f3->set('view', 'pages/admin.htm');
        $template = new \Template();
        echo $template->render('layout.htm');
    }

    function blogpost() {
        $this->f3->set('view', '/admin/blogpost.htm');
        $template = new \Template();
        echo $template->render('layout.htm');
    }

    function postBlog() {
        $title = $this->f3->get('POST.title');
        $body = $this->f3->get('POST.body');

        if(empty($title) || empty($body)) {
            \Flash::instance()->addMessage($this->f3->get('messages.admin.invalid') . '.', 'toast-error');
            $this->f3->reroute('/blogpost');
        }

        $post = new \Blogpost($this->database);
        $post->title = $title;
        $post->body = $body;
        $post->datetime = date('Y-m-d H:i:s');
        $post->image = $this->validateImage();
        $post->save();

        $logger = new \Log(Logs::SYSTEM);
        $logger->write("Created new blog article with title " . $title);

        \Flash::instance()->addMessage($this->f3->get('messages.admin.success') . '.', 'toast-success');
        $this->f3->reroute('/blogpost');
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
            \Flash::instance()->addMessage($this->f3->get('messages.admin.invalid.image.size') . '.', 'toast-error');
            $this->f3->reroute('/blogpost');
        }

        if($imageFileType != "jpg") {
            $logger->write("Invalid image: invalid type");
            \Flash::instance()->addMessage($this->f3->get('messages.admin.invalid.image.ext'), 'toast-error');
            $this->f3->reroute('/blogpost');
        }

        $length = 20;
        $today = date("m.d.y");
        $hash = substr(hash('md5', $today), 0, $length);
        $storedImgName = $targetDir . $hash . "." . $imageFileType;
        if(move_uploaded_file($_FILES["blogimage"]["tmp_name"], $targetFile)) {
            rename($targetFile, $storedImgName);
        } else {
            $logger->write("Invalid image: cannot move image");
            \Flash::instance()->addMessage($this->f3->get('messages.admin.cannot.upload.image'), 'toast-error');
            $this->f3->reroute('/blogpost');
        }

        // Resize image and delete uploaded one
        $img = new \Image($storedImgName, true);
        $img->resize(24, 24, true, true );
        file_put_contents($storedImgName, $img->dump('jpg'));

        $logger->write("Image uploaded successfully");

        return $storedImgName;
    }


}