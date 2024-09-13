<?php
namespace App\Repository;

use App\Model\Guest;
use App\Utils\Database;
use App\Interface\GuestRepositoryInterface;

class GuestRepository implements GuestRepositoryInterface {
    private $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function findAll() {
        $sql = 'SELECT * FROM guests';
        return $this->database->query($sql);
    }

    public function findById($id) {
        $sql = 'SELECT * FROM guests WHERE id = ?';
        $result = $this->database->query($sql, [$id]);
        return $result ? $result[0] : null;
    }

    public function save(Guest $guest) {
        $sql = 'INSERT INTO guests (name, surname, phone, email, country) VALUES (?, ?, ?, ?, ?)';
        return $this->database->insert($sql, [
            $guest->getName(),
            $guest->getSurname(),
            $guest->getPhone(),
            $guest->getEmail(),
            $guest->getCountry()
        ]);
    }

    public function update(Guest $guest) {
        $sql = 'UPDATE guests SET name = ?, surname = ?, phone = ?, email = ?, country = ? WHERE id = ?';
        return $this->database->update($sql, [
            $guest->getName(),
            $guest->getSurname(),
            $guest->getPhone(),
            $guest->getEmail(),
            $guest->getCountry(),
            $guest->getId()
        ]);
    }

    public function delete($id) {
        $sql = 'DELETE FROM guests WHERE id = ?';
        return $this->database->delete($sql, [$id]);
    }
}
