<div class="form-container">
    <h2>Login or <a href="index.php?action=register">Register</a></h2>
    <form id="loginForm" action="#" method="post" onsubmit="return validateForm()">

        <?php require_once __DIR__ . '/renderErrors.php' ?>
        <div class="form-group">
            <label for="userName">Username:</label>
            <input type="text" id="userName" name="username" onblur="validateUsername()" onfocus="clearError('userName')" class="" aria-describedby="userNameError">
            <p id="userNameError" class="error-message invalid" aria-live="assertive">Username is required.</p>
            <p id="userNameValid" class="error-message valid" aria-live="assertive">Looks good!</p>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <div class="password-input-group">
                <input type="password" id="password" name="password" onblur="validatePassword()" onfocus="clearError('password')" class="" aria-describedby="passwordError">
                <button type="button" id="togglePassword" aria-label="Show password" onclick="togglePasswordVisibility()">Show</button>
            </div>
            <p id="passwordError" class="error-message invalid" aria-live="assertive">Password is required.</p>
            <p id="passwordValid" class="error-message valid" aria-live="assertive">Password entered.</p>
        </div>

        <div class="form-actions">
            <button type="submit" id="submitButton" disabled>Login</button>
        </div>
    </form>
</div>

<script>
    const loginForm = document.getElementById('loginForm');
    const userNameInput = document.getElementById('userName');
    const passwordInput = document.getElementById('password');
    const submitButton = document.getElementById('submitButton');

    let isUserNameValid = false;
    let isPasswordValid = false;

    function validateUsername() {
        if (userNameInput.value.trim() === '') {
            showError('userName', 'Username is required.');
            isUserNameValid = false;
        } else {
            clearError('userName');
            showValid('userName'); // Example for valid feedback, can be removed
            userNameInput.classList.remove('invalid-input');
            userNameInput.classList.add('valid-input');
            isUserNameValid = true;
        }
        updateSubmitButtonState();
    }

    function validatePassword() {
        if (passwordInput.value.trim() === '') {
            showError('password', 'Password is required.');
            isPasswordValid = false;
        } else {
            clearError('password');
            showValid('password'); // Example for valid feedback, can be removed
            passwordInput.classList.remove('invalid-input');
            passwordInput.classList.add('valid-input');
            isPasswordValid = true;
        }
        updateSubmitButtonState();
    }

    function clearError(fieldName) {
        const errorElement = document.getElementById(fieldName + 'Error');
        const validElement = document.getElementById(fieldName + 'Valid'); // Example for valid feedback, can be removed
        const inputElement = document.getElementById(fieldName);
        errorElement.style.display = 'none';
        if (validElement) {
            validElement.style.display = 'none'; // Example for valid feedback, can be removed
        }
        inputElement.classList.remove('invalid-input');
        inputElement.classList.remove('valid-input');
    }

    function showError(fieldName, message) {
        const errorElement = document.getElementById(fieldName + 'Error');
        const validElement = document.getElementById(fieldName + 'Valid'); // Example for valid feedback, can be removed
        const inputElement = document.getElementById(fieldName);
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        if (validElement) {
            validElement.style.display = 'none'; // Example for valid feedback, can be removed
        }
        inputElement.classList.remove('valid-input');
        inputElement.classList.add('invalid-input');
    }

    function showValid(fieldName) { // Example for valid feedback, can be removed
        const validElement = document.getElementById(fieldName + 'Valid');
        validElement.style.display = 'block';
    }


    function updateSubmitButtonState() {
        submitButton.disabled = !(isUserNameValid && isPasswordValid);
    }

    function togglePasswordVisibility() {
        const passwordFieldType = passwordInput.getAttribute('type');
        const toggleButton = document.getElementById('togglePassword');
        if (passwordFieldType === 'password') {
            passwordInput.setAttribute('type', 'text');
            toggleButton.textContent = 'Hide';
            toggleButton.setAttribute('aria-label', 'Hide password');
        } else {
            passwordInput.setAttribute('type', 'password');
            toggleButton.textContent = 'Show';
            toggleButton.setAttribute('aria-label', 'Show password');
        }
    }

    // Initial state: disable submit button
    updateSubmitButtonState();
</script>