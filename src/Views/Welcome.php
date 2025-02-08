<?php
function validateRegistrationForm(): array
{
    $results = [
        'errors' => []
    ];

    // 1. Username Validation and Sanitization
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize username
    if ($username === null || $username === false) {
        $results['errors']['username'][] = 'Invalid username input.'; // Filter error, should not happen in a well-formed form.
    } else {
        if (strlen($username) < 3) {
            $results['errors']['username'] = 'Username must be at least 3 characters long.';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $results['errors']['username'] = 'Username can only contain alphanumeric characters and underscores.';
        }
        // No further sanitization needed as FILTER_SANITIZE_STRING already handles basic sanitization.
        // If you need stricter sanitization, consider using more specific filters or custom logic.
    }


    // 2. Password Validation
    $password = filter_input(INPUT_POST, 'password') ?? ''; // Retrieve password directly from postData array - NOT sanitized yet.
    $passwordConfirmation = filter_input(INPUT_POST, 'passwordConfirmation') ?? ''; // Retrieve password confirmation

    if (strlen($password) < 8) {
        $results['errors']['password'] = 'Password must be at least 8 characters long.';
    } else {
        $hasLowercase = preg_match('/[a-z]/', $password);
        $hasUppercase = preg_match('/[A-Z]/', $password);
        $hasPunctuation = preg_match('/[^a-zA-Z0-9\s]/', $password); // Matches any non-alphanumeric, non-whitespace

        if (!$hasLowercase) {
            $results['errors']['password'][] = 'Password must contain at least one lowercase letter.'; // Append to array to allow multiple password errors
        }
        if (!$hasUppercase) {
            $results['errors']['password'][] = 'Password must contain at least one uppercase letter.';
        }
        if (!$hasPunctuation) {
            $results['errors']['password'][] = 'Password must contain at least one punctuation character (e.g., !@#$%^&*).';
        }

        if (isset($results['errors']['password']) && is_array($results['errors']['password'])) {
            if (count($results['errors']['password']) > 0) {
                // If there are individual password errors, combine them into a single error message for 'password' key
                $results['errors']['password'] = implode(' ', $results['errors']['password']);
            } else {
                unset($results['errors']['password']); // No password errors after individual checks, remove the key if it became an empty array.
            }
        }

        if ($password !== $passwordConfirmation) {
            $results['errors']['password_confirmation'] = 'Passwords do not match.';
        }
    }

    $results['data'] = [
        'username' => $username,
        'password' => $password,
        'passwordConfirmation' => $passwordConfirmation,
    ];


    return $results;
}


$isSubmitted = ($_SERVER['REQUEST_METHOD'] === 'POST');
$isValid = true;



if ($isSubmitted) {
    $results = validateRegistrationForm($_POST);

    $isValid = empty($results['errors']);

    if ($isValid) {
        $username = $results['data']['username'];
        $password = $results['data']['password'];
        if (registerUser($pdo, $username, $password)) {
            header('Location: index.php?action=login');
        } else {
            print 'Internal error.';
        }
        die();
    }
}

function registerUser(PDO $pdo, string $username, string $password): bool
{
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)");
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':password_hash', $hashedPassword, PDO::PARAM_STR);
        $stmt->execute();
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
            const isPasswordValid = validatePassword();
            const isPasswordConfirmationValid = validatePasswordConfirmation();

            if (isUsernameValid && isFirstNameValid && isLastNameValid && isEmailValid && isPasswordValid && isPasswordConfirmationValid) {
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