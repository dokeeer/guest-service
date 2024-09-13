<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Controller\GuestController;
use App\Interface\GuestServiceInterface;

class GuestControllerTest extends TestCase
{
    private $guestController;
    private $guestServiceMock;

    protected function setUp(): void
    {
        if (!defined('IS_TEST_ENV'))
            define('IS_TEST_ENV', true);
        $this->guestServiceMock = $this->createMock(GuestServiceInterface::class);
        $this->guestController = new GuestController($this->guestServiceMock);
    }

    public function testHandleRequestServerError()
    {
        $this->guestServiceMock->method('getAllGuests')->will($this->throwException(new \Exception('Server error')));

        ob_start();
        $this->simulateRequest('GET', []);
        $response = ob_get_clean();

        $expectedResponse = ['error' => 'Server error'];
        $decodedResponse = json_decode($response, true);

        $this->assertEquals($expectedResponse, $decodedResponse);
    }

    public function testGetAllGuests()
    {
        $guests = [
            ['id' => 1, 'name' => 'John', 'surname' => 'Doe', 'phone' => '+1234567890', 'email' => 'john.doe@example.com', 'country' => 'USA']
        ];

        $this->guestServiceMock->method('getAllGuests')->willReturn($guests);

        ob_start();
        $this->simulateRequest('GET', []);
        $response = ob_get_clean();

        $decodedResponse = json_decode($response, true);
        $this->assertEquals($guests, $decodedResponse);
    }

    public function testGetGuest()
    {
        $id = 1;
        $guest = ['id' => $id, 'name' => 'John', 'surname' => 'Doe', 'phone' => '+1234567890', 'email' => 'john.doe@example.com', 'country' => 'USA'];

        $this->guestServiceMock->method('getGuest')->with($id)->willReturn($guest);

        ob_start();
        $this->simulateRequest('GET', ['id' => $id]);
        $response = ob_get_clean();

        $decodedResponse = json_decode($response, true);
        $this->assertEquals($guest, $decodedResponse);
    }

    public function testCreateGuest()
    {
        $data = [
            'name' => 'John',
            'surname' => 'Doe',
            'phone' => '+1234567890',
            'email' => 'john.doe@example.com'
        ];

        $this->guestServiceMock->method('createGuest')->with($data)->willReturn(1);

        ob_start();
        $this->simulateRequest('POST', $data);
        $response = ob_get_clean();

        $expectedResponse = ['status' => 'Guest created', 'id' => 1];
        $decodedResponse = json_decode($response, true);

        $this->assertEquals($expectedResponse, $decodedResponse);
    }

    public function testUpdateGuest()
    {
        $id = 1;
        $data = [
            'id' => $id,
            'name' => 'John',
            'email' => 'john.new@example.com'
        ];

        $this->guestServiceMock->method('updateGuest')->with($id, $data)->willReturn(true);

        ob_start();
        $this->simulateRequest('PUT', $data);
        $response = ob_get_clean();

        $expectedResponse = ['status' => 'Guest updated'];
        $decodedResponse = json_decode($response, true);

        $this->assertEquals($expectedResponse, $decodedResponse);
    }

    public function testDeleteGuest()
    {
        $id = 1;
        $this->guestServiceMock->method('deleteGuest')->with($id)->willReturn(true);

        ob_start();
        $this->simulateRequest('DELETE', ['id' => $id]);
        $response = ob_get_clean();

        $expectedResponse = ['status' => 'Guest deleted'];
        $decodedResponse = json_decode($response, true);

        $this->assertEquals($expectedResponse, $decodedResponse);
    }

    public function testHandleRequestMethodNotAllowed()
    {
        ob_start();
        $this->simulateRequest('PATCH', []);
        $response = ob_get_clean();

        $expectedResponse = ['error' => 'Method not allowed'];
        $decodedResponse = json_decode($response, true);

        $this->assertEquals($expectedResponse, $decodedResponse);
    }

    private function simulateRequest($method, $data = [])
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        $_GET = $data;
        $id = null;
        if (isset($data['id'])) $id = $data['id'];
        $this->guestController->handleRoute($id);
    }
}
