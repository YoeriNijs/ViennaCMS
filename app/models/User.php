<?php

class User extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'users');
    }

    public function all() {
        $this->load();
        return $this->query;
    }

    public function getByID($id) {
        $this->load(array('id=?', $id));
        return $this->query;
    }

    public function getByUsername($username) {
        $this->load(array('username=?', $username));
        return $this->query;
    }

    public function add() {
        $this->copyFrom('POST');
        $this->save();
    }

    public function edit($id) {
        $this->load(array('id=?', $id));
        $this->copyFrom('POST');
        $this->update();
    }

    public function delete($id) {
        $this->load(array('id=?', $id));
        $this->erase();
    }
}