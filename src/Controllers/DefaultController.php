<?php

declare(strict_types=1);

namespace MicroCMS\Controllers;

ob_start();

class DefaultController
{
    const VIEWS = __DIR__ . '/../Views/';


    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function homePage()
    {
        $stmt = $this->pdo->prepare(
            'SELECT user_id 
             FROM user_roles
             WHERE role_id = (SELECT id FROM roles WHERE name = :role_name);'
        );

        $stmt->bindValue(':role_name', 'admin');

        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            header("Location: index.php?action=setupAdmin");
            die();
        }

        // First check if there is an active session.
        // It's now array. Get on with it.
        if (isset($_SESSION['user'])) {
            $roles = $_SESSION['user']['roles'];

            $isAdmin = in_array("admin", $roles);

            if ($isAdmin) {
                $this->dashboard();
            }
        } else {
            header('Location: index.php?action=login');
            die();
        }
    }

    public function dashboard(): void
    {
        $pageTitle = 'uCMS - Dashboard';
        $blogs = $this->fetchAllBlogs();

        require_once self::VIEWS . '__header.php';

        require_once self::VIEWS . 'dashboard.php';

        require_once self::VIEWS . '__footer.php';
    }

    public function contactUs() {}

    public function pageNotFound()
    {
        $pageTitle = 'Oops! Page Not Found';
        require_once self::VIEWS . '__header.php';

        require_once self::VIEWS . '404.php';

        require_once self::VIEWS . '__footer.php';
    }

    public function internalServerError()
    {
        $pageTitle = 'Internal Server Error.';
        require_once self::VIEWS . '__header.php';

        require_once self::VIEWS . '50x.php';

        require_once self::VIEWS . '__footer.php';
    }







    private function fetchAllBlogs(): array
    {

        $stmt = $this->pdo->prepare(
            "SELECT id, title, slug, content, author_id
         FROM blogs;"
        );

        $stmt->execute();

        $blogs = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $blogs[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'slug' => $row['slug'],
                'content' => $row['content'],
                'authorId' => $row['author_id'],
            ];
        }

        return $blogs;
    }
}
