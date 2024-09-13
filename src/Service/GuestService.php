<?php
namespace App\Service;

use App\Model\Guest;
use App\Utils\Helpers;
use App\Interface\GuestServiceInterface;
use App\Interface\GuestRepositoryInterface;


class GuestService implements GuestServiceInterface {
    private $guestRepository;

    public function __construct(GuestRepositoryInterface $guestRepository) {
        $this->guestRepository = $guestRepository;
    }

    public function createGuest(array $data) {
        Helpers::validateGuestData($data);
        $data['country'] = $data['country'] ?? Helpers::determineCountryByPhone($data['phone']);

        $guest = new Guest(null, $data['name'], $data['surname'], $data['phone'], $data['email'] ?? null, $data['country']);
        $id = $this->guestRepository->save($guest);
        return $id;
    }

    public function updateGuest($id, array $data) {
        Helpers::validateGuestData($data, 'UPDATE');
        $currentGuestData = $this->guestRepository->findById($id);
        if (!empty($currentGuestData)) {
            $updatedGuest = new Guest(
                $id,
                $data['name'] ?? $currentGuestData['name'],
                $data['surname'] ?? $currentGuestData['surname'],
                phone: $data['phone'] ?? $currentGuestData['phone'],
                email: $data['email'] ?? $currentGuestData['email'],
                country: $data['country'] ?? $currentGuestData['country'],
            );
            return $this->guestRepository->update($updatedGuest);
        }
        return false;
    }

    public function getAllGuests() {
        return $this->guestRepository->findAll();
    }

    public function getGuest($id) {
        return $this->guestRepository->findById($id);
    }

    public function deleteGuest($id) {
        $guestId = $this->guestRepository->findById($id);
        if (!empty($guestId)) {
            return $this->guestRepository->delete($id);
        }
        return false;
    }
}
