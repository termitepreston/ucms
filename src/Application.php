<?php

namespace MicroCMS;

use MicroCMS\Controllers\BlogController;
use MicroCMS\Controllers\DefaultController;
use MicroCMS\Controllers\UserController;

class Application
{
    private \PDO $pdo;

    public function __construct()
    {
        // Configure secure sessions.
        $this->configureSessions();

        $this->pdo = Connection::getInstance()->connect();
    }

    private function configureSessions(): void
    {
        // Configure session storing cookies.
        $sessionConfig = [
            'use_strict_mode' => true,         // Prevent uninitialized session IDs
            'use_cookies' => true,             // Use cookies to store session ID
            'cookie_httponly' => true,         // Prevent JavaScript access
            'cookie_secure' => true,           // Only send over HTTPS
            'cookie_samesite' => 'Strict',     // Prevent CSRF
            'gc_maxlifetime' => 1800,          // 30 minutes expiration
            'sid_length' => 128,               // Strong session ID length
            'sid_bits_per_character' => 6      // More entropy
        ];


        // Apply configuration
        foreach ($sessionConfig as $key => $value) {
            ini_set("session.$key", $value);
        }

        // Set custom session name
        session_name('SECURE_CS322_SESSION');

        // Start session management
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function run(): void
    {
        $blogController = new BlogController($this->pdo);
        $defaultController = new DefaultController($this->pdo);
        $userController = new UserController($this->pdo);

        // controller functions are defined here.
        $action = filter_input(INPUT_GET, 'action') ?? '';


        // Routing happens here.
        switch ($action) {
            case 'addBlog':
                $blogController->addBlog();
                break;
            case 'deleteBlog':
                $blogController->deleteBlog();
                break;
            case 'editBlog':
                $blogController->editBlog();
                break;
            case 'visitBlog':
                $blogController->visitBlog();
                break;
            case 'logout':
                $userController->logout();
            case '404':
                $defaultController->pageNotFound();
                break;
            case '500':
            case '501':
            case '505':
                $defaultController->internalServerError();
            case 'setupAdmin':
                $userController->setupAdmin();
                break;
            case 'login':
                $userController->login();
                break;
            case 'register':
                $userController->register();
                break;
            case 'home':
            default:
                $defaultController->homePage();
        }
    }
}
