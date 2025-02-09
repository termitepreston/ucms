<section>
    <h3>Setup your account</h3>
    <form id="registrationForm" action="" method="post" enctype="multipart/form-data">

        <div class="stack-vertical stack-scale-7">
            <?php require_once __DIR__ . '/renderErrors.php'; ?>
            <div class="form-item">
                <div class="text-input__label-wrapper"><label class="label" for="username">Username:</label></div>
                <div class="text-input__field-outer-wrapper">
                    <div class="text-input__field-wrapper"><input type="text" class="text-input" id="username" name="username" onblur="validateUsername()" onfocus="clearError('usernameError')" required></div>
                </div>
                <div id="usernameError" class="error" style="display: none;"></div>
            </div>
            <div class="form-item">
                <div class="text-input__label-wrapper"><label class="label" for="firstName">First Name:</label></div>
                <div class="text-input__field-outer-wrapper">
                    <div class="text-input__field-wrapper"><input type="text" class="text-input" id="firstName" name="firstName" onblur="validateFirstName()" onfocus="clearError('firstNameError')" required></div>
                </div>
                <div id="firstNameError" class="error" style="display: none;"></div>
            </div>
            <div class="form-item">
                <div class="text-input__label-wrapper"><label class="label" for="lastName">Last Name:</label></div>
                <div class="text-input__field-outer-wrapper">
                    <div class="text-input__field-wrapper"><input type="text" class="text-input" id="lastName" name="lastName" onblur="validateLastName()" onfocus="clearError('lastNameError')" required></div>
                </div>
                <div id="lastNameError" class="error" style="display: none;"></div>
            </div>
            <div class="form-item">
                <div class="text-input__label-wrapper"><label class="label" for="email">Email:</label></div>
                <div class="text-input__field-outer-wrapper">
                    <div class="text-input__field-wrapper"><input type="email" class="text-input" id="email" name="email" onblur="validateEmail()" onfocus="clearError('emailError')" required></div>
                </div>
                <div id="emailError" class="error" style="display: none;"></div>
            </div>

            <div class="form-item">
                <div class="text-input__label-wrapper"><label class="label" for="profilePhoto">Profile Photo:</label></div>
                <input type="file" id="profilePhoto" name="profilePhoto" accept="image/*">
                <small class="form-text text-muted">Optional: Upload a profile picture (images only).</small>
            </div>
            <div class="form-item">
                <div class="text-input__label-wrapper"><label class="label" for="bio">Bio:</label></div>
                <div class="text-area__wrapper"><textarea id="bio" name="bio" rows="3" class="text-area"></textarea></div>
                <small class="form-text text-muted">Tell us a bit about yourself (optional).</small>
            </div>
            <div class="form-item">
                <div class="text-input__label-wrapper"><label class="label" for="password">Password:</label></div>
                <div class="text-input__field-outer-wrapper">
                    <div class="text-input__field-wrapper"><input type="password" class="text-input" id="password" name="password" onblur="validatePassword()" onfocus="clearError('passwordError')" required></div>
                </div>
                <div id="passwordError" class="error" style="display: none;"></div>
            </div>
            <div class="form-item">
                <div class="text-input__label-wrapper"><label class="label" for="passwordConfirmation">Confirm Password:</label></div>
                <div class="text-input__field-outer-wrapper">
                    <div class="text-input__field-wrapper"><input type="password" class="text-input" id="passwordConfirmation" name="passwordConfirmation" onblur="validatePasswordConfirmation()" onfocus="clearError('passwordConfirmationError')" required></div>
                </div>
                <div id="passwordConfirmationError" class="error" style="display: none;"></div>
            </div>
            <div class="form-item">
                <button class="button button--primary" type="submit" id="registerButton" disabled>Register</button>
            </div>
        </div>
    </form>
</section>

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