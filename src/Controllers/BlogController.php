<?php

namespace MicroCMS\Controllers;

ob_start();


class BlogController
{
    const VIEWS = __DIR__ . '/../Views/';

    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function addBlog(): void
    {
        $pageTitle = 'uCMS - Add a New Blog';
        require_once self::VIEWS . '__header.php';

        $isSubmitted = ($_SERVER['REQUEST_METHOD'] === 'POST');
        $isValid = true;
        $username = $_SESSION['username'] ?? '';


        if ($isSubmitted) {
            $results = $this->validateNewPageForm();

            $isValid = empty($results['errors']);

            if ($isValid) {
                $authorId = $this->getUserIdFromUsername($username);



                if ($this->insertBlog($results['sanitizedInput'], $authorId)) {


                    header('Location: index.php?action=home');
                    die();
                } else {
                    // Username already exists...
                    $results['errors']['username'] = "User {$results['sanitizedInput']['username']} already exists.";
                }
            }
        }

        require_once self::VIEWS . 'addBlog.php';

        require_once self::VIEWS . '__footer.php';
    }

    public function deleteBlog()
    {
        // Check whether if the requested id exists.
        $blogId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if ($blogId === false || $blogId === null) {
            http_response_code(400); // Set HTTP status code to 400 Bad Request
            header('Content-Type: application/json'); // Optionally specify content type (e.g., JSON for API responses)
            echo json_encode([
                'error' => 'Bad Request',
                'message' => 'Required query parameters are missing. Please provide "id".'
            ]);
            die();
        }

        try {
            $stmt = $this->pdo->prepare(
                "SELECT slug
             FROM blogs
             WHERE id = :id"
            );

            $stmt->bindValue(":id", $blogId);

            $stmt->execute();

            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$row) {
                http_response_code(404);
                header('Location: index.php?action=404');
                die();
            }

            unset($stmt);

            $stmt = $this->pdo->prepare(
                "DELETE
         FROM blogs
         WHERE id = :id"
            );


            $stmt->bindValue(":id", $blogId);

            $stmt->execute();

            header("Location: index.php?action=home");
            die();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function visitBlog(): void
    {
        $pageTitle = 'uCMS - Visiting a Blog Entry...';
        require_once self::VIEWS . '__header.php';

        // Check whether if the requested id exists.
        $blogId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if ($blogId === false || $blogId === null) {
            http_response_code(400); // Set HTTP status code to 400 Bad Request
            header('Content-Type: application/json'); // Optionally specify content type (e.g., JSON for API responses)
            echo json_encode([
                'error' => 'Bad Request',
                'message' => 'Required query parameters are missing. Please provide "id".'
            ]);
            die();
        }

        try {
            $stmt = $this->pdo->prepare(
                "SELECT content
                 FROM blogs
                 WHERE id = :id"
            );

            $stmt->bindValue(":id", $blogId);

            $stmt->execute();

            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$row) {
                http_response_code(404);
                header('Location: index.php?action=404');
                die();
            }

            $content = $row['content'];
        } catch (\PDOException $e) {
            throw $e;
        }

        require_once self::VIEWS . 'visitBlog.php';
        require_once self::VIEWS . '__footer.php';
    }

    public function editBlog()
    {
        $pageTitle = 'uCMS - Edit a Blog';
        require_once self::VIEWS . '__header.php';

        $isSubmitted = ($_SERVER['REQUEST_METHOD'] === 'POST');
        $isValid = true;
        $username = $_SESSION['username'] ?? '';

        // Check whether if the requested id exists.
        $blogId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if ($blogId === false || $blogId === null) {
            http_response_code(400); // Set HTTP status code to 400 Bad Request
            header('Content-Type: application/json'); // Optionally specify content type (e.g., JSON for API responses)
            echo json_encode([
                'error' => 'Bad Request',
                'message' => 'Required query parameters are missing. Please provide "id".'
            ]);
            die();
        }

        $blog = [];

        try {
            $stmt = $this->pdo->prepare(
                "SELECT title, slug, content
         FROM blogs
         WHERE id = :id"
            );

            $stmt->bindValue(":id", $blogId);

            $stmt->execute();

            $blog = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$blog) {
                http_response_code(404);
                header('Location: index.php?action=404');
                die();
            }
        } catch (\PDOException $e) {
            throw $e;
        }



        if ($isSubmitted) {
            $results = $this->validateEditPageForm();

            $isValid = empty($results['errors']);

            if ($isValid) {

                if ($this->updateBlog($results['sanitizedInput'], $blogId)) {


                    header("Location: index.php?action=home");
                    die();
                } else {
                    // Username already exists...
                    $results['errors']['username'] = "User {$results['sanitizedInput']['username']} already exists.";
                    $isValid = false;
                }
            }
        }

        require_once self::VIEWS . 'editBlog.php';
        require_once self::VIEWS . '__footer.php';
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function validateNewPageForm(): array
    {
        $errors = [];
        $sanitizedInput = [];

        // 0. Blog title validation and sanitization.
        $blogEntryTitle = filter_input(INPUT_POST, 'blogEntryTitle', FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize username
        if ($blogEntryTitle === null || $blogEntryTitle === false) {
            $errors['blogEntryTitle'] = 'Invalid blogEntryTitle input.'; // Filter error, should not happen in a well-formed form.
        } else {
            if (strlen($blogEntryTitle) < 1) {
                $errors['blogEntryTitle'] = 'Blog title must be at least 1 characters long.';
            } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $blogEntryTitle)) {
                $errors['blogEntryTitle'] = 'blogEntryTitle can only contain alphanumeric characters and underscores.';
            }
            // No further sanitization needed as FILTER_SANITIZE_STRING already handles basic sanitization.
            // If you need stricter sanitization, consider using more specific filters or custom logic.
            $sanitizedInput['blogEntryTitle'] = $blogEntryTitle;
        }


        // 1. Blog slug Validation and Sanitization
        $blogEntrySlug = filter_input(INPUT_POST, 'blogEntrySlug', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($blogEntrySlug === null || $blogEntrySlug === false) {
            $errors['blogEntrySlug'] = 'Invalid input for Blog Slug.';
        } else {
            $blogEntrySlug = trim($blogEntrySlug);
            if (empty($blogEntrySlug)) {
                $errors['blogEntrySlug'] = 'Blog slug is required.';
            } elseif (!preg_match('/^[a-zA-Z_-]+$/', $blogEntrySlug)) {
                $errors['blogEntrySlug'] = 'Blog slug should only contain letters, digits, underscores and dashes.';
            }
            $sanitizedInput['blogEntrySlug'] = $blogEntrySlug; // Sanitized by FILTER_SANITIZE_STRING, trimmed
        }

        // 2. Last Name Validation and Sanitization
        $blogEntryMarkdown = filter_input(INPUT_POST, 'blogEntryMarkdown', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($blogEntryMarkdown === null || $blogEntryMarkdown === false) {
            $errors['blogEntryMarkdown'] = 'Blog entry cannot be empty';
        } else {
            $blogEntryMarkdown = trim($blogEntryMarkdown);
            if (empty($blogEntryMarkdown)) {
                $errors['blogEntryMarkdown'] = 'Blog entry cannot be empty.';
            }


            $sanitizedInput['blogEntryMarkdown'] = $blogEntryMarkdown; // Sanitized by FILTER_SANITIZE_STRING, trimmed
        }



        return ['errors' => $errors, 'sanitizedInput' => $sanitizedInput];
    }

    private function getUserIdFromUsername(string $username): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT id
         FROM users
         WHERE username = :username"
        );

        $stmt->bindValue(":username", $username);

        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            print "Could not find user by the name of {$username}";
            die();
        }

        return $row['id'];
    }

    function insertBlog(array $inputs, int $authorId): bool
    {
        $title = $inputs['blogEntryTitle'];
        $slug = $inputs['blogEntrySlug'];
        $content = $inputs['blogEntryMarkdown'];

        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO
             blogs (title, slug, content, author_id)
             VALUES (:title, :slug, :content, :authorId)"
            );

            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':slug', $slug);
            $stmt->bindValue(':content', $content);
            $stmt->bindValue(':authorId', $authorId);

            $stmt->execute();
        } catch (\PDOException $e) {
            if ($e->getCode() === '23505') {
                return false;
            } else {
                throw $e;
            }
        }

        return true;
    }

    function updateBlog(array $inputs, int $blogId): bool
    {
        $title = $inputs['blogEntryTitle'] ?? '';
        $slug = $inputs['blogEntrySlug'] ?? '';
        $content = $inputs['blogEntryMarkdown'] ?? '';

        try {
            $stmt = $this->pdo->prepare(
                "UPDATE blogs
             SET
                title = :title,
                slug = :slug,
                content = :content
             WHERE
                id = :blogId;"
            );

            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':slug', $slug);
            $stmt->bindValue(':content', $content);
            $stmt->bindValue(':blogId', $blogId);

            $stmt->execute();
        } catch (\PDOException $e) {
            if ($e->getCode() === '23505') {
                return false;
            } else {
                throw $e;
            }
        }

        return true;
    }

    /**
     * @return array<string, array<string, string>>
     */

    private function validateEditPageForm(): array
    {
        $errors = [];
        $sanitizedInput = [];

        // 0. Blog title validation and sanitization.
        $blogEntryTitle = filter_input(INPUT_POST, 'blogEntryTitle', FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize username
        if ($blogEntryTitle === null || $blogEntryTitle === false) {
            $errors['blogEntryTitle'] = 'Invalid blogEntryTitle input.'; // Filter error, should not happen in a well-formed form.
        } else {
            if (strlen($blogEntryTitle) < 1) {
                $errors['blogEntryTitle'] = 'Blog title must be at least 1 characters long.';
            } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $blogEntryTitle)) {
                $errors['blogEntryTitle'] = 'blogEntryTitle can only contain alphanumeric characters and underscores.';
            }
            // No further sanitization needed as FILTER_SANITIZE_STRING already handles basic sanitization.
            // If you need stricter sanitization, consider using more specific filters or custom logic.
            $sanitizedInput['blogEntryTitle'] = $blogEntryTitle;
        }


        // 1. Blog slug Validation and Sanitization
        $blogEntrySlug = filter_input(INPUT_POST, 'blogEntrySlug', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($blogEntrySlug === null || $blogEntrySlug === false) {
            $errors['blogEntrySlug'] = 'Invalid input for Blog Slug.';
        } else {
            $blogEntrySlug = trim($blogEntrySlug);
            if (empty($blogEntrySlug)) {
                $errors['blogEntrySlug'] = 'Blog slug is required.';
            } elseif (!preg_match('/^[a-zA-Z_-]+$/', $blogEntrySlug)) {
                $errors['blogEntrySlug'] = 'Blog slug should only contain letters, digits, underscores and dashes.';
            }
            $sanitizedInput['blogEntrySlug'] = $blogEntrySlug; // Sanitized by FILTER_SANITIZE_STRING, trimmed
        }

        // 2. Last Name Validation and Sanitization
        $blogEntryMarkdown = filter_input(INPUT_POST, 'blogEntryMarkdown');
        if ($blogEntryMarkdown === null || $blogEntryMarkdown === false) {
            $errors['blogEntryMarkdown'] = 'Blog entry cannot be empty';
        } else {
            $blogEntryMarkdown = trim($blogEntryMarkdown);
            if (empty($blogEntryMarkdown)) {
                $errors['blogEntryMarkdown'] = 'Blog entry cannot be empty.';
            }


            $sanitizedInput['blogEntryMarkdown'] = $blogEntryMarkdown; // Sanitized by FILTER_SANITIZE_STRING, trimmed
        }



        return ['errors' => $errors, 'sanitizedInput' => $sanitizedInput];
    }

    private function markdownToHtml(string $markdown): string
    {
        // Temporary storage for code blocks
        $codeBlocks = [];

        // Step 1: Process code blocks (```...```)
        $processed = preg_replace_callback('/```([\s\S]*?)```/', function ($matches) use (&$codeBlocks) {
            $codeBlocks[] = htmlspecialchars(trim($matches[1]), ENT_QUOTES, 'UTF-8');
            return '<!--codeblock' . (count($codeBlocks) - 1) . '-->';
        }, $markdown);

        // Step 2: Escape HTML characters
        // $html = htmlspecialchars($processed, ENT_QUOTES, 'UTF-8');
        $html = $processed;

        // Step 3: Process block elements
        // Headers (#)
        $html = preg_replace_callback('/^(#{1,6})\s+(.+)$/m', function ($matches) {
            $level = strlen($matches[1]);
            return "<h$level>{$matches[2]}</h$level>";
        }, $html);

        // Horizontal rules (---, ***)
        $html = preg_replace('/^[-*_]{3,}$/m', '<hr>', $html);

        // Step 4: Process lists
        // Unordered lists (*, -, +)
        $html = preg_replace('/^(\*|\-|\+)\s+(.+)$/m', '<li>$2</li>', $html);
        // Ordered lists (1., 2., etc)
        $html = preg_replace('/^(\d+)\.\s+(.+)$/m', '<li>$2</li>', $html);

        // Step 5: Process inline elements
        // Inline code (`code`)
        $html = preg_replace('/`([^`]+)`/', '<code>$1</code>', $html);
        // Links [text](url)
        $html = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2">$1</a>', $html);
        // Bold (**bold** or __bold__)
        $html = preg_replace('/(\*\*|__)(.*?)\1/', '<strong>$2</strong>', $html);
        // Italic (*italic* or _italic_)
        $html = preg_replace('/(\*|_)(.*?)\1/', '<em>$2</em>', $html);

        // Step 6: Process line breaks
        $html = preg_replace('/(?: {2,}|\\\\)\r?\n/', "<br>", $html);

        // Step 7: Process paragraphs
        $lines = explode("\n", $html);
        $html = '';
        $paragraph = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (empty($trimmed)) {
                if (!empty($paragraph)) {
                    $html .= '<p>' . implode(' ', $paragraph) . '</p>';
                    $paragraph = [];
                }
                continue;
            }

            if (preg_match('/^<(\/?(h[1-6]|ul|ol|li|hr|pre|code|br))/', $trimmed)) {
                if (!empty($paragraph)) {
                    $html .= '<p>' . implode(' ', $paragraph) . '</p>';
                    $paragraph = [];
                }
                $html .= $trimmed . "\n";
            } else {
                $paragraph[] = $trimmed;
            }
        }

        if (!empty($paragraph)) {
            $html .= '<p>' . implode(' ', $paragraph) . '</p>';
        }

        // Step 8: Restore code blocks
        $html = preg_replace_callback('/<!--codeblock(\d+)-->/', function ($matches) use ($codeBlocks) {
            $index = $matches[1];
            return '<pre><code>' . $codeBlocks[$index] . '</code></pre>';
        }, $html);

        return $html;
    }

    private function testMdToHtml()
    {
        $md1 = <<<EOD
# The Wonders of Fictional Flora

Paragraph 1:  Imagine a world painted not just with familiar greens and browns, but vibrant hues of cerulean foliage and crimson bark.  In this fantastical ecosystem, plants possess abilities beyond mere sustenance.  Consider the Whisperwind Willow, its leaves rustling not with the breeze, but with secrets whispered on the wind, or the Sunstone Bloom, petals radiating warmth like a miniature sun.  This exploration into fictional flora, as detailed further in this [comprehensive guide to fantastical botany](https://www.google.com/url?sa=E&source=gmail&q=https://www.example-fictional-botany.com), reveals the boundless creativity of imagined nature.

## Exploring Unique Adaptations

Paragraph 2: Fictional plants thrive on extraordinary adaptations.  Think about the Cloudvine, clinging to sky islands with roots that absorb atmospheric moisture, or the Crystal Tree, its branches composed of shimmering quartz, channeling subterranean energies.  Unordered lists can help categorize these wonders:

  - Luminescent Flora: Plants that emit their own light, often found in subterranean grottos or twilight forests.
  - Carnivorous Vines: Mobile plants that actively hunt and consume insects or even small creatures.
  - Symbiotic Fungi:  Complex networks of fungi that integrate with tree roots, sharing nutrients and information.

![A Fictional Plant](about:sanitized)

### The Cycle of the Lumiflora

Paragraph 3: The life cycle of these fictional plants can be just as captivating. The Lumiflora, for instance, follows a rhythmic illumination cycle.

1.  **Seed Dormancy:** Seeds remain inert until triggered by specific lunar phases.
2.  **Sprout Illumination:**  Seedlings emerge, emitting a faint glow that intensifies with growth.
3.  **Full Bloom Luminescence:**  The plant reaches maturity, radiating a vibrant light that attracts nocturnal pollinators.
4.  **Seed Dispersal (Phototropic):**  Seeds are released, often drifting on light currents created by their own luminescence.

> "Nature, even when imagined, holds a mirror to our own realities, reflecting the intricate dance of life and adaptation." -  Professor Eldrune, Fictional Botanist.

Paragraph 4:  Sometimes, the most intriguing aspects are hidden beneath the surface.  Consider this snippet of pseudo-code outlining the growth algorithm for the Everbranch Tree:

```pseudocode
function growEverbranch(treeNode):
  if treeNode.age < maxAge:
    if randomChance(branchingProbability):
      createBranch(treeNode)
    else:
      treeNode.increaseHeight()
    waitForTime(growthInterval)
    growEverbranch(treeNode) // Recursive growth
  else:
    treeNode.stopGrowing()
```

## Conclusion: The Allure of the Unseen

Paragraph 5:  The allure of fictional flora lies not just in their fantastical properties, but in the way they expand our understanding and appreciation for the natural world, both real and imagined.  These botanical fantasies remind us that the universe is brimming with possibilities, waiting to be discovered, or perhaps, to be invented.  Let us continue to explore these imagined green realms, for they enrich our perception of life itself.
EOD;
    }
}
