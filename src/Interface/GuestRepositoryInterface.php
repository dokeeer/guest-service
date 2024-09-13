<?php 
namespace App\Interface;

use App\Model\Guest;

interface GuestRepositoryInterface {
    public function findAll();
    public function findById($id);
    public function save(Guest $guest);
    public function update(Guest $guest);
    public function delete($id);
}