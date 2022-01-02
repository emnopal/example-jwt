<?php

<<<<<<< HEAD:src/Auth/Router.php
namespace Badhabit\SimpleJWT\Auth;
=======
namespace Badhabit\JwtLoginManagement\App;
>>>>>>> jwt-auth-db:app/App/Route.php

class Route
{

    private static array $routes = [];

    public static function add(string $method,
                               string $path,
                               string $controller,
                               string $function): void
    {
        self::$routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'function' => $function
        ];
    }

    public static function run(): void
    {
        header('Content-Type: application/json');
        $path = '/';
        if (isset($_SERVER['PATH_INFO'])) {
            $path = $_SERVER['PATH_INFO'];
        }

        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $route) {
            $pattern = "#^" . $route['path'] . "$#";
            if (preg_match($pattern, $path, $variables) && $method == $route['method']) {

                $function = $route['function'];
                $controller = new $route['controller'];

                array_shift($variables);
                call_user_func_array([$controller, $function], $variables);

                return;
            }
        }
        $token_json = [
            'status' => [
                'code' => "404",
                'message' => 'Link not found'
            ]
        ];
        echo json_encode($token_json);
    }
}