<?php

namespace App;

use App\Utils\Database;
use App\Controller\GuestController;
use App\Repository\GuestRepository;
use App\Service\GuestService;

class Router
{
    private $routes = [];
    private $services = [];

    public function __construct()
    {
        $this->defineRoutes();
        $this->registerServices();
    }

    private function defineRoutes()
    {
        $this->addRoute('GET', '/guests', [GuestController::class, 'handleRoute']);
        $this->addRoute('GET', '/guests/{id}', [GuestController::class, 'handleRoute']);
        $this->addRoute('POST', '/guests', [GuestController::class, 'handleRoute']);
        $this->addRoute('PUT', '/guests/{id}', [GuestController::class, 'handleRoute']);
        $this->addRoute('DELETE', '/guests/{id}', [GuestController::class, 'handleRoute']);
    }

    private function registerServices()
    {
        $this->services[GuestController::class] = function() {
            $database = new Database();
            $guestRepository = new GuestRepository(database: $database);
            $guestService = new GuestService($guestRepository);
            return new GuestController($guestService);
        };
    }

    public function handleRequest()
    {
        $requestUri = rtrim($_SERVER['REQUEST_URI'], '/');
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $route = $this->matchRoute($requestMethod, $requestUri);

        if ($route) {
            [$controllerClass, $method] = $route;
            $controller = $this->get($controllerClass);
            
            if (method_exists($controller, $method)) {
                $entityId = $this->extractId($requestUri, '/guests/{id}');
                $controller->$method($entityId);
            } else {
                $this->sendError('Method not allowed', 405);
            }
        } else {
            $this->sendError('Route not found', 404);
        }
    }

    private function extractId($uri, $pattern)
    {
        $regexPattern = str_replace('{id}', '(\d+)', $pattern);

        if (preg_match("#^$regexPattern$#", $uri, $matches)) {
            return isset($matches[1]) ? intval($matches[1]) : null;
        }
        return null;
    }


    private function matchRoute($method, $uri)
    {
        foreach ($this->routes as $routeMethod => $routes) {
            if ($method === $routeMethod) {
                foreach ($routes as $routePattern => $action) {
                    $pattern = str_replace(['{id}'], ['(\d+)'], $routePattern);
                    if (preg_match("#^$pattern$#", $uri, $matches)) {
                        return $action;
                    }
                }
            }
        }
        return null;
    }

    private function addRoute($method, $pattern, $action)
    {
        if (!isset($this->routes[$method])) {
            $this->routes[$method] = [];
        }
        $this->routes[$method][$pattern] = $action;
    }

    private function get($class)
    {
        if (isset($this->services[$class])) {
            return $this->services[$class]();
        }
        throw new \Exception("Service not found: $class");
    }

    private function sendError($message, $statusCode)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode(['error' => $message]);
        exit;
    }
}
