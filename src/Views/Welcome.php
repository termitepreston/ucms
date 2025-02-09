<?php

declare(strict_types=1);

const MAX_SIZE = 5 * 1024 * 1024;
const UPLOAD_DIR = '../../uploads/';

/**
 * Validates and sanitizes form input for user profile update.
 *
 * @return array<string, array<string, string>> An associative array of validation errors. Empty array if no errors.
 */
function validateRegistrationForm(): array
{
    $errors = [];
    $sanitizedInput = [];

    // 0. Username validation and sanitization.
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize username
    if ($username === null || $username === false) {
        $errors['username'] = 'Invalid username input.'; // Filter error, should not happen in a well-formed form.
    } else {
        if (strlen($username) < 3) {
            $errors['username'] = 'Username must be at least 3 characters long.';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors['username'] = 'Username can only contain alphanumeric characters and underscores.';
        }
        // No further sanitization needed as FILTER_SANITIZE_STRING already handles basic sanitization.
        // If you need stricter sanitization, consider using more specific filters or custom logic.
        $sanitizedInput['username'] = $username;
    }


    // 1. First Name Validation and Sanitization
    $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_SPECIAL_CHARS);
    if ($firstName === null || $firstName === false) {
        $errors['firstName'] = 'Invalid input for First Name.';
    } else {
        $firstName = trim($firstName);
        if (empty($firstName)) {
            $errors['firstName'] = 'First Name is required.';
        } elseif (!preg_match('/^[a-zA-Z]+$/', $firstName)) {
            $errors['firstName'] = 'First Name should only contain letters.';
        }
        $sanitizedInput['firstName'] = $firstName; // Sanitized by FILTER_SANITIZE_STRING, trimmed
    }

    // 2. Last Name Validation and Sanitization
    $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_SPECIAL_CHARS);
    if ($lastName === null || $lastName === false) {
        $errors['lastName'] = 'Invalid input for Last Name.';
    } else {
        $lastName = trim($lastName);
        if (empty($lastName)) {
            $errors['lastName'] = 'Last Name is required.';
        } elseif (!preg_match('/^[a-zA-Z]+$/', $lastName)) {
            $errors['lastName'] = 'Last Name should only contain letters.';
        }
        $sanitizedInput['lastName'] = $lastName; // Sanitized by FILTER_SANITIZE_STRING, trimmed
    }

    // 3. Email Validation and Sanitization
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if ($email === false || $email === null) { // FILTER_VALIDATE_EMAIL returns false on invalid, null if not set (which we don't expect from form)
        $errors['email'] = 'Invalid email format.';
    } else {
        $sanitizedInput['email'] = $email; // Validated and sanitized by FILTER_VALIDATE_EMAIL
    }

    // 3.5 Blog title validation and sanitization.
    $blogTitle = filter_input(INPUT_POST, 'blogTitle', FILTER_SANITIZE_SPECIAL_CHARS);
    if ($blogTitle === null || $blogTitle === false) {
        $errors['blogTitle'] = 'Invalid input for blog title.';
    } else {
        $blogTitle = trim($blogTitle);
        if (empty($blogTitle)) {
            $errors['blogTitle'] = 'Blog title is required.';
        } elseif (!preg_match('/^[a-zA-Z_-]+$/', $blogTitle)) {
            $errors['blogTitle'] = 'Blog title should only contain letters and dashes.';
        }
        $sanitizedInput['blogTitle'] = $blogTitle; // Sanitized by FILTER_SANITIZE_STRING, trimmed
    }

    // 4. Bio Sanitization
    $bio = filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Sanitize for HTML special chars, encode < > etc.
    if ($bio === null) {
        $sanitizedInput['bio'] = ''; // Treat null as empty bio (optional field)
    } else {
        $sanitizedInput['bio'] = $bio; // Sanitized with FILTER_SANITIZE_FULL_SPECIAL_CHARS
    }

    // 5. Profile Photo Upload Validation
    $profilePhotoError = $_FILES['profilePhoto']['error'] ?? UPLOAD_ERR_NO_FILE; // Check for upload errors from $_FILES, default to NO_FILE if not present

    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp']; // Allowed image MIME types


    if ($profilePhotoError === UPLOAD_ERR_OK) {
        $profilePhotoTmpName = $_FILES['profilePhoto']['tmp_name'] ?? '';
        $profilePhotoFileName = $_FILES['profilePhoto']['name'] ?? '';
        $profilePhotoSz = $_FILES['profilePhoto']['size'] ?? 0;
        $profilePhotoMimeType = mime_content_type($profilePhotoTmpName); // Get MIME type based on file content (more reliable)

        if (!in_array($profilePhotoMimeType, $allowedMimeTypes, true)) { // Check if MIME type starts with 'image/'
            $errors['profilePhoto'] = 'Invalid file type. Only images are allowed.';
        } else if ($profilePhotoSz > MAX_SIZE) {
            $errors['profilePhoto'] = 'Above the allowed file size.';
        } else {
            // 3. Generate a Unique Filename (Security and collision prevention)
            $fileExtension = pathinfo($profilePhotoFileName, PATHINFO_EXTENSION); // Get original file extension (e.g., "jpg")
            $uniqueFilename = uniqid('profile_') . '_' . bin2hex(random_bytes(8)) . '.' . strtolower($fileExtension); // More robust unique filename
            $destinationPath = UPLOAD_DIR . $uniqueFilename;

            if (!is_dir(UPLOAD_DIR)) {
                if (!mkdir(UPLOAD_DIR, 0777, true)) { // 0777 is generally too permissive for production - adjust permissions securely
                    $errors['profilePhoto'] = "Server error: Could not create upload directory.";
                    error_log("Error creating upload directory: " . UPLOAD_DIR);
                }
            }

            // 5. Move Uploaded File to Destination (Secure move operation)
            if (is_uploaded_file($profilePhotoTmpName)) { // Double check if it's a valid upload
                if (move_uploaded_file($profilePhotoTmpName, $destinationPath)) {
                    $sanitizedInput['profilePhoto'] = $destinationPath; // Return the full path to the saved file
                } else {
                    $errors['profilePhoto'] = "Error saving uploaded file to server.";
                    error_log("Error moving uploaded file from " . $profilePhotoTmpName . " to " . $destinationPath);
                }
            } else {
                $errors['profilePhoto'] = "Possible file upload attack: Invalid upload."; // Security measure
                error_log("Possible file upload attack detected: " . $profilePhotoFileName . ", tmp_name: " . $profilePhotoTmpName);
            }

            // Perform further validation:
            // - Image dimensions
        }
    } elseif ($profilePhotoError !== UPLOAD_ERR_NO_FILE) {
        // Handle other upload errors (UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE, etc.)
        switch ($profilePhotoError) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $errors['profilePhoto'] = 'File size too large. Please upload a smaller image.';
                break;
            case UPLOAD_ERR_PARTIAL:
                $errors['profilePhoto'] = 'File upload was partial. Please try again.';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
            case UPLOAD_ERR_CANT_WRITE:
            case UPLOAD_ERR_EXTENSION:
                $errors['profilePhoto'] = 'Error uploading file. Please contact administrator.';
                error_log("Profile photo upload error (server-side): " . $profilePhotoError); // Log server errors
                break;
                // UPLOAD_ERR_NO_FILE is already handled (no error in this case)
            default:
                $errors['profilePhoto'] = 'Error uploading file.'; // Generic error for other cases
        }
    } // UPLOAD_ERR_NO_FILE means no file was uploaded, which is okay for an optional field.

    // 6. Password Validation
    $password = filter_input(INPUT_POST, 'password') ?? '';
    $passwordConfirmation = filter_input(INPUT_POST, 'passwordConfirmation') ?? '';

    if (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters long.';
    } else {
        $hasLowercase = preg_match('/[a-z]/', $password);
        $hasUppercase = preg_match('/[A-Z]/', $password);
        $hasPunctuation = preg_match('/[^a-zA-Z0-9\s]/', $password); // Matches any non-alphanumeric, non-whitespace

        if (!$hasLowercase) {
            $errors['password'][] = 'Password must contain at least one lowercase letter.'; // Append to array to allow multiple password errors
        }
        if (!$hasUppercase) {
            $errors['password'][] = 'Password must contain at least one uppercase letter.';
        }
        if (!$hasPunctuation) {
            $errors['password'][] = 'Password must contain at least one punctuation character (e.g., !@#$%^&*).';
        }

        if (isset($errors['password']) && is_array($errors['password'])) {
            if (count($errors['password']) > 0) {
                // If there are individual password errors, combine them into a single error message for 'password' key
                $errors['password'] = implode(' ', $errors['password']);
            } else {
                unset($errors['password']); // No password errors after individual checks, remove the key if it became an empty array.
                $sanitizedInput['password'] = $password;
            }
        }

        $sanitizedInput['password'] = $password;


        if ($password !== $passwordConfirmation) {
            $errors['passwordConfirmation'] = 'Passwords do not match.';
        }
    }

    return ['errors' => $errors, 'sanitizedInput' => $sanitizedInput];
}


$isSubmitted = ($_SERVER['REQUEST_METHOD'] === 'POST');
$isValid = true;



if ($isSubmitted) {
    $results = validateRegistrationForm();

    $isValid = empty($results['errors']);

    if ($isValid) {

        if (registerUser($pdo, $results['sanitizedInput'])) {
            $_SESSION['username'] = $results['sanitizedInput']['username'];
            $_SESSION['role'] = 'admin';

            // Set session data
            $_SESSION['user'] = [
                'username' => $results['sanitizedInput']['username'],
                'role' => 'admin',
                'last_login' => time(),
                'ip' => $_SERVER['REMOTE_ADDR']
            ];

            // Close session after writing
            session_write_close();

            header('Location: index.php');
        } else {
            // Username already exists...
            $results['errors']['username'] = "User {$results['sanitizedInput']['username']} already exists.";
        }
        die();
    }
}

function registerUser(PDO $pdo, array $input): bool
{
    $username = $input['username'];
    $firstName = $input['firstName'];
    $lastName = $input['lastName'];
    $email = $input['email'];
    $blogTitle = $input['blogTitle'];
    $bio = $input['bio'];
    $profilePhoto = $input['profilePhoto'];
    $password = $input['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare(
            "INSERT INTO users 
                (username, first_name, last_name, email, profile_picture_path, bio, password_hash, blog_title)
            VALUES
                (:username, :firstName, :lastName, :email, :profilePhoto, :bio, :passwordHash, :blogTitle)"
        );

        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':firstName', $firstName, PDO::PARAM_STR);
        $stmt->bindValue(':lastName', $lastName, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':bio', $bio, PDO::PARAM_STR);
        $stmt->bindValue(':profilePhoto', $profilePhoto, PDO::PARAM_STR);
        $stmt->bindValue(':passwordHash', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindValue(':blogTitle', $blogTitle, PDO::PARAM_STMT);

        $stmt->execute();

        $adminId = $pdo->lastInsertId();

        $stmtRBAC = $pdo->prepare(
            "INSERT INTO user_roles
                (user_id, role_id)
            VALUES
                (:adminId, (SELECT id FROM roles WHERE name = :adminName))"
        );

        $stmtRBAC->bindValue(':adminId', $adminId);
        $stmtRBAC->bindValue(':adminName', 'admin');

        $stmtRBAC->execute();

        return true; // Registration successful
    } catch (PDOException $e) {
        // Handle unique constraint violation (username already exists) specifically
        if ($e->getCode() === '23505') { // PostgreSQL unique_violation SQLSTATE - Adapt for other DBs if needed
            return false; // Indicate registration failure due to existing username
        } else {
            // Re-throw other PDOExceptions for the calling code to handle generic database errors
            throw $e;
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome To Your Initial Setup</title>
    <style>
        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 0.2em;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-bottom: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button[type="submit"] {
            padding: 10px 15px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }

        button[type="submit"]:disabled {
            background-color: #cccccc;
            color: #666666;
            cursor: not-allowed;
        }

        body {
            display: grid;
            place-items: center;
            min-height: 100vh;
        }

        form {
            max-width: 33%;
        }
    </style>
</head>

<body>
    <form id="registrationForm" action="" method="post" enctype="multipart/form-data">
        <?php if ($isSubmitted && !$isValid): ?>
            <div class="error">
                <pre>
                <?php print_r($results['errors']) ?>
            </pre>
            </div>
        <?php endif; ?>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" onblur="validateUsername()" onfocus="clearError('usernameError')" required>
            <div id="usernameError" class="error" style="display: none;"></div>
        </div>

        <div class="form-group">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" onblur="validateFirstName()" onfocus="clearError('firstNameError')" required>
            <div id="firstNameError" class="error" style="display: none;"></div>
        </div>

        <div class="form-group">
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" onblur="validateLastName()" onfocus="clearError('lastNameError')" required>
            <div id="lastNameError" class="error" style="display: none;"></div>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" onblur="validateEmail()" onfocus="clearError('emailError')" required>
            <div id="emailError" class="error" style="display: none;"></div>
        </div>

        <div class="form-group">
            <label for="blogTitle">Blog Title:</label>
            <input type="text" id="blogTitle" name="blogTitle" onblur="validateBlogTitle()" onfocus="clearError('blogTitleError')" required>
            <div id="blogTitleError" class="error" style="display: none;"></div>
        </div>

        <div class="form-group">
            <label for="profilePhoto">Profile Photo:</label>
            <input type="file" id="profilePhoto" name="profilePhoto" accept="image/*">
            <small class="form-text text-muted">Optional: Upload a profile picture (images only).</small>
        </div>

        <div class="form-group">
            <label for="bio">Bio:</label>
            <textarea id="bio" name="bio" rows="3"></textarea>
            <small class="form-text text-muted">Tell us a bit about yourself (optional).</small>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" onblur="validatePassword()" onfocus="clearError('passwordError')" required>
            <div id="passwordError" class="error" style="display: none;"></div>
        </div>

        <div class="form-group">
            <label for="passwordConfirmation">Confirm Password:</label>
            <input type="password" id="passwordConfirmation" name="passwordConfirmation" onblur="validatePasswordConfirmation()" onfocus="clearError('passwordConfirmationError')" required>
            <div id="passwordConfirmationError" class="error" style="display: none;"></div>
        </div>

        <div class="form-group">
            <button type="submit" id="registerButton" disabled>Register</button>
        </div>
    </form>

    <script>
        const form = document.getElementById('registrationForm');
        const registerButton = document.getElementById('registerButton');
        let formIsValid = false; // Track overall form validity

        document.addEventListener('DOMContentLoaded', function() {
            disableSubmitButton(); // Initially disable the button
        });

        function enableSubmitButton() {
            registerButton.disabled = false;
            formIsValid = true;
        }

        function disableSubmitButton() {
            registerButton.disabled = true;
            formIsValid = false;
        }

        function clearError(errorElementId) {
            document.getElementById(errorElementId).style.display = 'none';
            document.getElementById(getErrorInputId(errorElementId)).classList.remove('is-invalid'); // Remove visual feedback if used
        }

        function displayError(errorElementId, message) {
            const errorElement = document.getElementById(errorElementId);
            errorElement.textContent = message;
            errorElement.style.display = 'block';
            document.getElementById(getErrorInputId(errorElementId)).classList.add('is-invalid'); // Optionally add visual feedback (e.g., red border)
        }

        function getErrorInputId(errorElementId) {
            return errorElementId.replace('Error', ''); // Assumes error id is fieldId + 'Error'
        }


        function validateUsername() {
            const usernameInput = document.getElementById('username');
            const username = usernameInput.value.trim();
            if (username.length < 3 || !/^[a-zA-Z0-9_]+$/.test(username)) {
                displayError('usernameError', 'Username must be at least 3 characters and alphanumeric with underscores only.');
                return false;
            } else {
                clearError('usernameError');
                return true;
            }
        }

        function validateFirstName() {
            const firstNameInput = document.getElementById('firstName');
            const firstName = firstNameInput.value.trim();
            if (!firstName) {
                displayError('firstNameError', 'First name is required.');
                return false;
            } else if (/[^a-zA-Z]/.test(firstName)) {
                displayError('firstNameError', 'First name should only contain letters.');
                return false;
            } else {
                clearError('firstNameError');
                return true;
            }
        }

        function validateLastName() {
            const lastNameInput = document.getElementById('lastName');
            const lastName = lastNameInput.value.trim();
            if (!lastName) {
                displayError('lastNameError', 'Last name is required.');
                return false;
            } else if (/[^a-zA-Z]/.test(lastName)) {
                displayError('lastNameError', 'Last name should only contain letters.');
                return false;
            } else {
                clearError('lastNameError');
                return true;
            }
        }

        function validateEmail() {
            const emailInput = document.getElementById('email');
            const email = emailInput.value.trim();
            if (!email) {
                displayError('emailError', 'Email is required.');
                return false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { // Basic email validation regex
                displayError('emailError', 'Invalid email format.');
                return false;
            } else {
                clearError('emailError');
                return true;
            }
        }

        function validateBlogTitle() {
            const blogTitleInput = document.getElementById('blogTitle');
            const blogTitle = blogTitleInput.value.trim();

            if (!blogTitle) {
                displayError('blogTitleError', 'Blog title is required');
                return false;
            } else if (/[^a-zA-Z0-9_-]+/.test(blogTitle)) {
                displayError('blogTitleError', 'Blog title (domain) should only contain alpha-numerics or dashes and underscores.')
                return false;
            } else {
                clearError('blogTitleError');
                return true;
            }
        }

        function validatePassword() {
            const passwordInput = document.getElementById('password');
            const password = passwordInput.value;
            if (password.length < 8 || !/(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9\s])/.test(password)) {
                displayError('passwordError', 'Password must be at least 8 characters and include lowercase, uppercase, and punctuation.');
                return false;
            } else {
                clearError('passwordError');
                return true;
            }
        }

        function validatePasswordConfirmation() {
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('passwordConfirmation');
            if (passwordInput.value !== passwordConfirmationInput.value) {
                displayError('passwordConfirmationError', 'Passwords do not match.');
                return false;
            } else {
                clearError('passwordConfirmationError');
                return true;
            }
        }

        function validateForm() {
            const isUsernameValid = validateUsername();
            const isFirstNameValid = validateFirstName();
            const isLastNameValid = validateLastName();
            const isEmailValid = validateEmail();
            const isBlogTitleValid = validateBlogTitle();
            const isPasswordValid = validatePassword();
            const isPasswordConfirmationValid = validatePasswordConfirmation();

            if (isUsernameValid && isFirstNameValid && isLastNameValid && isBlogTitleValid && isEmailValid && isPasswordValid && isPasswordConfirmationValid) {
                enableSubmitButton();
                return true; // Form is valid
            } else {
                disableSubmitButton();
                return false; // Form is invalid
            }
        }


        form.addEventListener('submit', function(event) {
            if (!validateForm()) {
                event.preventDefault(); // Prevent submission if form is not valid after final check on submit
                alert('Please correct the errors in the form.'); // Optional: Alert user of errors on submit as well
            }
            // Form will submit if validateForm() returns true (button is enabled)
        });

        // Event listeners for input fields to perform validation on blur and focus
        document.querySelectorAll('input, textarea').forEach(input => {
            input.addEventListener('blur', validateForm); // Re-validate entire form on blur of any field
        });
    </script>
</body>

</html>