<?php
namespace App\Utils;

use InvalidArgumentException;

class Helpers {
    public static function validateGuestData(array $data, $option = 'CREATE')
    {
        if ($option == 'CREATE') {
            // Проверка на наличие обязательных полей при создании
            if (empty($data['name']) || empty($data['surname']) || empty($data['phone'])) {
                throw new InvalidArgumentException('Missing required fields');
            }
        }

        // Проверка корректности имени и фамилии
        if (isset($data['name']) && !self::isAlpha($data['name']) 
        ||  isset($data['surname']) && !self::isAlpha($data['surname'])) {
            throw new InvalidArgumentException('Name and surname must only contain alphabetic characters');
        }

        // Проверка корректности email (если указан)
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }

        // Проверка корректности телефона
        if (isset($data['phone']) && !self::isValidPhoneNumber($data['phone'])) {
            throw new InvalidArgumentException('Invalid phone number format');
        }

        // Проверка корректности страны (если указана)
        if (isset($data['country']) && !self::isAlpha($data['country'])) {
            throw new InvalidArgumentException('Country name must only contain alphabetic characters');
        }

        return true;
    }

    private static function isAlpha($string)
    {
        return preg_match('/^[a-zA-Z]+$/', $string);
    }

    private static function isValidPhoneNumber($phone)
    {
        return preg_match('/^\+?[0-9]{10,15}$/', $phone);
    }

    public static function determineCountryByPhone($phone) {
        if (strpos($phone, '+7') === 0) {
            return 'Russia';
        }
        return 'Unknown';
    }

    public static function getRequestData() {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                return $_GET;
            case 'POST':
            case 'PUT':
            case 'DELETE':
                if (defined('IS_TEST_ENV') && IS_TEST_ENV) {
                    return $_GET;
                }
                $input = file_get_contents('php://input');
                $data = json_decode($input, true);
                if ($data === null) {
                    $data = [];
                }
                return $data;

            default:
                return [];
        }

    }
}