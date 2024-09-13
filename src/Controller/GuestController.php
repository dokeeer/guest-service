<?php
namespace App\Controller;

use Exception;
use App\Utils\Helpers;
use App\Interface\GuestServiceInterface;

class GuestController {
    private $guestService;

    public function __construct(GuestServiceInterface $guestService) {
        $this->guestService = $guestService;
    }

    public function handleRoute($id = null) {
        $method = $_SERVER['REQUEST_METHOD'];
        $data = Helpers::getRequestData();

        try {
            switch ($method) {
                case 'GET':
                    if ($id) {
                        $result = $this->guestService->getGuest($id);
                    } else {
                        $result = $this->guestService->getAllGuests();
                    }
                    $response = $result;
                    if (empty($result))
                        $response = ['status'=>'Guest not found'];
                    break;
                case 'POST':
                    $id = $this->guestService->createGuest($data);
                    $response = ['status' => 'Guest created', 'id' => $id];
                    break;
                case 'PUT':
                    if ($id) {
                        $result = $this->guestService->updateGuest($id, $data);
                        $response = ['status' => 'Guest updated'];
                        if (empty($result))
                            $response = ['status'=>'Guest not found'];
                } else {
                        $this->sendError('ID is required for update', 400);
                    }
                    break;
                case 'DELETE':
                    if ($id) {
                        $result = $this->guestService->deleteGuest($id);
                        $response = (['status' => 'Guest deleted']);
                        if (empty($result))
                            $response = ['status'=>'Guest not found'];
                    } else {
                        $this->sendError('ID is required for deletion', 400);
                    }
                    break;
                default:
                    $this->sendError('Method not allowed', 405);
                    break;
            }
            if (!empty($response))
                $this->sendResponse($response, 200);

        } catch (Exception $e) {
            $this->sendError($e->getMessage(), 500);
        }
    }

    private function sendResponse($data, $statusCode = 200) {
        if (!headers_sent()) {
            header('Content-Type: application/json');
            header('X-Debug-Time: ' . (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']));
            header('X-Debug-Memory: ' . (memory_get_usage() / 1024) . ' KB');
            http_response_code($statusCode);
        }
        echo json_encode($data);
        if (!defined('IS_TEST_ENV') || !IS_TEST_ENV) {
            exit;
        }
    }

    private function sendError($message, $statusCode) {
        $this->sendResponse(['error' => $message], $statusCode);
    }
}

