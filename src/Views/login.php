<div class="form-container">
    <div class="stack-vertical stack-scale-7">
        <h2>Login or <a href="index.php?action=register">Register</a></h2>
        <form id="loginForm" action="#" method="post" onsubmit="return validateForm()">
            <?php require_once __DIR__ . '/renderErrors.php' ?>
            <div class="form-item">
                <div class="text-input__label-wrapper"><label class="label" for="userName">Username:</label></div>
                <div class="text-input__field-outer-wrapper">
                    <div class="text-input__field-wrapper"><input type="text" id="userName" class="text-input" name="username" onblur="validateUsername()" onfocus="clearError('userName')" class="" aria-describedby="userNameError"></div>
                </div>
                <p id="userNameError" class="error-message invalid" aria-live="assertive">Username is required.</p>
            </div>
            <div class="form-item">
                <div class="text-input__label-wrapper"><label class="label" for="password">Password:</label></div>
                <div class="text-input__field-outer-wrapper">
                    <div class="text-input__field-wrapper"><input type="password" class="text-input" id="password" name="password" onblur="validatePassword()" onfocus="clearError('password')" class="" aria-describedby="passwordError"></div>
                </div>
                <p id="passwordError" class="error-message invalid" aria-live="assertive">Password is required.</p>
            </div>
            <div class="form-item">
                <button class="button button--primary" type="submit" id="loginButton" disabled>Login</button>
            </div>
        </form>
    </div>
</div>

<script>
    const loginForm = document.getElementById('loginForm');
    const userNameInput = document.getElementById('userName');
    const passwordInput = document.getElementById('password');
    const submitButton = document.getElementById('loginButton');

    let isUserNameValid = false;
    let isPasswordValid = false;

    function validateUsername() {
        if (userNameInput.value.trim() === '') {
            showError('userName', 'Username is required.');
            isUserNameValid = false;
        } else {
            clearError('userName');
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