<?php
namespace App\Interface;
interface GuestServiceInterface {
    public function createGuest(array $data);
    public function updateGuest($id, array $data);
    public function getAllGuests();
    public function getGuest($id);
    public function deleteGuest($id);
}