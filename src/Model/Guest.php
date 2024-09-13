<?php
namespace App\Model;

class Guest {
    private $id;
    private $name;
    private $surname;
    private $phone;
    private $email;
    private $country;

    public function __construct($id, $name, $surname, $phone, $email, $country) {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->phone = $phone;
        $this->email = $email;
        $this->country = $country;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getCountry() {
        return $this->country;
    }
}
