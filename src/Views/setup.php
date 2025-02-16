<main class="login-wrapper">
    <div class="App">
        <div class="AppContainer">
            <div class="AnimatedBackground__container">
                <div class="AnimatedBackground SetCyanMagentaPurple">
                    <svg filter="url(#filter)" class="AnimatedBackground__gradient" height="100%" width="100%" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <g>
                            <ellipse id="blob1" cx="5%" cy="100%" rx="10%" ry="35%"></ellipse>
                            <ellipse id="blob2" cx="15%" cy="100%" rx="25%" ry="15%"></ellipse>
                            <ellipse id="blob3" cx="40%" cy="105%" rx="10%" ry="20%"></ellipse>
                            <ellipse id="blob4" cx="80%" cy="100%" rx="30%" ry="15%"></ellipse>
                            <ellipse id="blob5" cx="100%" cy="100%" rx="20%" ry="50%"></ellipse>
                        </g>
                        <defs>
                            <filter id="filter" x="0" y="0" color-interpolation-filters="sRGB">
                                <feGaussianBlur stdDeviation="70"></feGaussianBlur>
                            </filter>
                        </defs>
                    </svg>
                </div>
            </div>

            <div class="login-container">
                <div class="login-wrapper">
                    <div class="login-sub-wrapper">
                        <form id="loginForm" action method="post"">

                            <svg xmlns=" http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                            </svg>

                            <div class="LoginForm__title">
                                Setup your account
                                <div class="LoginForm__createAccount">
                                    Already have a account? <a href="index.php?action=login">Login.</a>
                                </div>
                            </div>

                            <?php require_once __DIR__ . '/renderErrors.php' ?>


                            <div class="LoginForm__inputRows">

                                <div class="LoginForm__usernamePasswordRow">
                                    <div class="LoginForm__labelWrapper">
                                        <div class="LoginForm__label">
                                            <span>Account information</span>
                                        </div>
                                    </div>

                                    <!-- Username -->
                                    <div class="LoginForm__usernameWrapper">
                                        <div class="LayerTwo">
                                            <div class="FormItem TextInputWrapper">
                                                <div class="InputLabel__wrapper">
                                                    <div class="Label">Username</div>
                                                </div>
                                                <div class="TextInput__fieldOuterWrapper">
                                                    <div class="TextInput__fieldWrapper">
                                                        <svg focusable="false" preserveAspectRatio="xMidYMid meet" fill="currentColor" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" class="TextInput__invalidIcon" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M8,1C4.2,1,1,4.2,1,8s3.2,7,7,7s7-3.1,7-7S11.9,1,8,1z M7.5,4h1v5h-1C7.5,9,7.5,4,7.5,4z M8,12.2 c-0.4,0-0.8-0.4-0.8-0.8s0.3-0.8,0.8-0.8c0.4,0,0.8,0.4,0.8,0.8S8.4,12.2,8,12.2z"></path>
                                                            <path d="M7.5,4h1v5h-1C7.5,9,7.5,4,7.5,4z M8,12.2c-0.4,0-0.8-0.4-0.8-0.8s0.3-0.8,0.8-0.8 c0.4,0,0.8,0.4,0.8,0.8S8.4,12.2,8,12.2z" data-icon-path="inner-path" opacity="0"></path>
                                                        </svg>
                                                        <input type="text" class="TextInput TextInput--large Layout--sizeLarge" placeholder="username" name="username" id="username" autocapitalize="none" autocomplete="username" value onblur="validateUsername();" onfocus="clearError('username');" />
                                                    </div>
                                                    <div class="FormRequirement"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- First name -->
                                    <div class="LoginForm__usernameWrapper">
                                        <div class="LayerTwo">
                                            <div class="FormItem TextInputWrapper">
                                                <div class="InputLabel__wrapper">
                                                    <div class="Label">First name</div>
                                                </div>
                                                <div class="TextInput__fieldOuterWrapper">
                                                    <div class="TextInput__fieldWrapper">
                                                        <svg focusable="false" preserveAspectRatio="xMidYMid meet" fill="currentColor" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" class="TextInput__invalidIcon" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M8,1C4.2,1,1,4.2,1,8s3.2,7,7,7s7-3.1,7-7S11.9,1,8,1z M7.5,4h1v5h-1C7.5,9,7.5,4,7.5,4z M8,12.2 c-0.4,0-0.8-0.4-0.8-0.8s0.3-0.8,0.8-0.8c0.4,0,0.8,0.4,0.8,0.8S8.4,12.2,8,12.2z"></path>
                                                            <path d="M7.5,4h1v5h-1C7.5,9,7.5,4,7.5,4z M8,12.2c-0.4,0-0.8-0.4-0.8-0.8s0.3-0.8,0.8-0.8 c0.4,0,0.8,0.4,0.8,0.8S8.4,12.2,8,12.2z" data-icon-path="inner-path" opacity="0"></path>
                                                        </svg>
                                                        <input type="text" class="TextInput TextInput--large Layout--sizeLarge" placeholder="firstName" name="firstName" id="firstName" autocapitalize="none" value onblur="validateFirstName();" onfocus="clearError('firstName');" />
                                                    </div>
                                                    <div class="FormRequirement"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Last name -->
                                    <div class="LoginForm__usernameWrapper">
                                        <div class="LayerTwo">
                                            <div class="FormItem TextInputWrapper">
                                                <div class="InputLabel__wrapper">
                                                    <div class="Label">Last name</div>
                                                </div>
                                                <div class="TextInput__fieldOuterWrapper">
                                                    <div class="TextInput__fieldWrapper">
                                                        <svg focusable="false" preserveAspectRatio="xMidYMid meet" fill="currentColor" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" class="TextInput__invalidIcon" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M8,1C4.2,1,1,4.2,1,8s3.2,7,7,7s7-3.1,7-7S11.9,1,8,1z M7.5,4h1v5h-1C7.5,9,7.5,4,7.5,4z M8,12.2 c-0.4,0-0.8-0.4-0.8-0.8s0.3-0.8,0.8-0.8c0.4,0,0.8,0.4,0.8,0.8S8.4,12.2,8,12.2z"></path>
                                                            <path d="M7.5,4h1v5h-1C7.5,9,7.5,4,7.5,4z M8,12.2c-0.4,0-0.8-0.4-0.8-0.8s0.3-0.8,0.8-0.8 c0.4,0,0.8,0.4,0.8,0.8S8.4,12.2,8,12.2z" data-icon-path="inner-path" opacity="0"></path>
                                                        </svg>
                                                        <input type="text" class="TextInput TextInput--large Layout--sizeLarge" placeholder="Last name" name="lastName" id="lastName" autocapitalize="none" value onblur="validateLastName();" onfocus="clearError('lastName');" />
                                                    </div>
                                                    <div class="FormRequirement"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="LoginForm__usernameWrapper">
                                        <div class="LayerTwo">
                                            <div class="FormItem TextInputWrapper">
                                                <div class="InputLabel__wrapper">
                                                    <div class="Label">Email</div>
                                                </div>
                                                <div class="TextInput__fieldOuterWrapper">
                                                    <div class="TextInput__fieldWrapper">
                                                        <svg focusable="false" preserveAspectRatio="xMidYMid meet" fill="currentColor" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" class="TextInput__invalidIcon" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M8,1C4.2,1,1,4.2,1,8s3.2,7,7,7s7-3.1,7-7S11.9,1,8,1z M7.5,4h1v5h-1C7.5,9,7.5,4,7.5,4z M8,12.2 c-0.4,0-0.8-0.4-0.8-0.8s0.3-0.8,0.8-0.8c0.4,0,0.8,0.4,0.8,0.8S8.4,12.2,8,12.2z"></path>
                                                            <path d="M7.5,4h1v5h-1C7.5,9,7.5,4,7.5,4z M8,12.2c-0.4,0-0.8-0.4-0.8-0.8s0.3-0.8,0.8-0.8 c0.4,0,0.8,0.4,0.8,0.8S8.4,12.2,8,12.2z" data-icon-path="inner-path" opacity="0"></path>
                                                        </svg>
                                                        <input type="email" class="TextInput TextInput--large Layout--sizeLarge" placeholder="user@example.com" name="email" id="email" autocapitalize="none" autocomplete="email" value onblur="validateEmail();" onfocus="clearError('email');" />
                                                    </div>
                                                    <div class="FormRequirement"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Blog title -->
                                    <div class="LoginForm__usernameWrapper">
                                        <div class="LayerTwo">
                                            <div class="FormItem TextInputWrapper">
                                                <div class="InputLabel__wrapper">
                                                    <div class="Label">Blog title</div>
                                                </div>
                                                <div class="TextInput__fieldOuterWrapper">
                                                    <div class="TextInput__fieldWrapper">
                                                        <svg focusable="false" preserveAspectRatio="xMidYMid meet" fill="currentColor" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" class="TextInput__invalidIcon" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M8,1C4.2,1,1,4.2,1,8s3.2,7,7,7s7-3.1,7-7S11.9,1,8,1z M7.5,4h1v5h-1C7.5,9,7.5,4,7.5,4z M8,12.2 c-0.4,0-0.8-0.4-0.8-0.8s0.3-0.8,0.8-0.8c0.4,0,0.8,0.4,0.8,0.8S8.4,12.2,8,12.2z"></path>
                                                            <path d="M7.5,4h1v5h-1C7.5,9,7.5,4,7.5,4z M8,12.2c-0.4,0-0.8-0.4-0.8-0.8s0.3-0.8,0.8-0.8 c0.4,0,0.8,0.4,0.8,0.8S8.4,12.2,8,12.2z" data-icon-path="inner-path" opacity="0"></path>
                                                        </svg>
                                                        <input type="text" class="TextInput TextInput--large Layout--sizeLarge" name="blogTitle" id="blogTitle" autocapitalize="none" value onblur="validateBlogTitle();" onfocus="clearError('blogTitle');" />
                                                    </div>
                                                    <div class="FormRequirement"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="LoginForm__usernameWrapper">
                                        <div class="LayerTwo">
                                            <div class="FormItem TextInputWrapper PasswordInputWrapper">
                                                <div class="InputLabel__wrapper">
                                                    <div class="Label">Password</div>
                                                </div>
                                                <div class="TextInput__fieldOuterWrapper">
                                                    <div class="TextInput__fieldWrapper">
                                                        <svg focusable="false" preserveAspectRatio="xMidYMid meet" fill="currentColor" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" class="TextInput__invalidIcon" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M8,1C4.2,1,1,4.2,1,8s3.2,7,7,7s7-3.1,7-7S11.9,1,8,1z M7.5,4h1v5h-1C7.5,9,7.5,4,7.5,4z M8,12.2 c-0.4,0-0.8-0.4-0.8-0.8s0.3-0.8,0.8-0.8c0.4,0,0.8,0.4,0.8,0.8S8.4,12.2,8,12.2z"></path>
                                                            <path d="M7.5,4h1v5h-1C7.5,9,7.5,4,7.5,4z M8,12.2c-0.4,0-0.8-0.4-0.8-0.8s0.3-0.8,0.8-0.8 c0.4,0,0.8,0.4,0.8,0.8S8.4,12.2,8,12.2z" data-icon-path="inner-path" opacity="0"></path>
                                                        </svg>
                                                        <input type="password" class="TextInput PasswordInput TextInput--large LoginForm__password Layout--sizeLarge" name="password" id="password" autocomplete="current-password" required value onblur="validatePassword();" onfocus="clearError('password');" data-toggle-password-visibility="true" />
                                                        <span class="PopoverContainer PopoverCaret PopoverHightContrast PopoverBottomEnd ToolTip TogglePasswordTooltip">
                                                            <div class="TooltipTrigger__wrapper">
                                                                <button type="button" class="Button TextInput--passwordVisibilityToggle Tooltip__trigger" onclick="togglePasswordVisibility();">
                                                                    <svg focusable="false" preserveAspectRatio="xMidYMid meet" fill="currentColor" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" class="cds--icon-visibility-on" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M15.5,7.8C14.3,4.7,11.3,2.6,8,2.5C4.7,2.6,1.7,4.7,0.5,7.8c0,0.1,0,0.2,0,0.3c1.2,3.1,4.1,5.2,7.5,5.3 c3.3-0.1,6.3-2.2,7.5-5.3C15.5,8.1,15.5,7.9,15.5,7.8z M8,12.5c-2.7,0-5.4-2-6.5-4.5c1-2.5,3.8-4.5,6.5-4.5s5.4,2,6.5,4.5 C13.4,10.5,10.6,12.5,8,12.5z"></path>
                                                                        <path d="M8,5C6.3,5,5,6.3,5,8s1.3,3,3,3s3-1.3,3-3S9.7,5,8,5z M8,10c-1.1,0-2-0.9-2-2s0.9-2,2-2s2,0.9,2,2S9.1,10,8,10z"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </span>
                                                    </div>
                                                    <div class="FormRequirement"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Confirm password -->
                                    <div class="LoginForm__usernameWrapper">
                                        <div class="LayerTwo">
                                            <div class="FormItem TextInputWrapper PasswordInputWrapper">
                                                <div class="InputLabel__wrapper">
                                                    <div class="Label">Confirm password</div>
                                                </div>
                                                <div class="TextInput__fieldOuterWrapper">
                                                    <div class="TextInput__fieldWrapper">
                                                        <svg focusable="false" preserveAspectRatio="xMidYMid meet" fill="currentColor" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" class="TextInput__invalidIcon" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M8,1C4.2,1,1,4.2,1,8s3.2,7,7,7s7-3.1,7-7S11.9,1,8,1z M7.5,4h1v5h-1C7.5,9,7.5,4,7.5,4z M8,12.2 c-0.4,0-0.8-0.4-0.8-0.8s0.3-0.8,0.8-0.8c0.4,0,0.8,0.4,0.8,0.8S8.4,12.2,8,12.2z"></path>
                                                            <path d="M7.5,4h1v5h-1C7.5,9,7.5,4,7.5,4z M8,12.2c-0.4,0-0.8-0.4-0.8-0.8s0.3-0.8,0.8-0.8 c0.4,0,0.8,0.4,0.8,0.8S8.4,12.2,8,12.2z" data-icon-path="inner-path" opacity="0"></path>
                                                        </svg>
                                                        <input type="password" class="TextInput PasswordInput TextInput--large LoginForm__password Layout--sizeLarge" name="passwordConfirmation" id="passwordConfirmation" autocomplete="current-password" required value onblur="validatePasswordConfirmation();" onfocus="clearError('passwordConfirmation');" data-toggle-password-visibility="true" />
                                                        <span class="PopoverContainer PopoverCaret PopoverHightContrast PopoverBottomEnd ToolTip TogglePasswordTooltip">
                                                            <div class="TooltipTrigger__wrapper">
                                                                <button type="button" class="Button TextInput--passwordVisibilityToggle Tooltip__trigger" onclick="togglePasswordConfirmationVisibility();">
                                                                    <svg focusable="false" preserveAspectRatio="xMidYMid meet" fill="currentColor" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" class="cds--icon-visibility-on" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M15.5,7.8C14.3,4.7,11.3,2.6,8,2.5C4.7,2.6,1.7,4.7,0.5,7.8c0,0.1,0,0.2,0,0.3c1.2,3.1,4.1,5.2,7.5,5.3 c3.3-0.1,6.3-2.2,7.5-5.3C15.5,8.1,15.5,7.9,15.5,7.8z M8,12.5c-2.7,0-5.4-2-6.5-4.5c1-2.5,3.8-4.5,6.5-4.5s5.4,2,6.5,4.5 C13.4,10.5,10.6,12.5,8,12.5z"></path>
                                                                        <path d="M8,5C6.3,5,5,6.3,5,8s1.3,3,3,3s3-1.3,3-3S9.7,5,8,5z M8,10c-1.1,0-2-0.9-2-2s0.9-2,2-2s2,0.9,2,2S9.1,10,8,10z"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </span>
                                                    </div>
                                                    <div class="FormRequirement"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="LoginForm__loginButtonWrapper">
                                        <button name="login" type="submit" id="loginButton" class="LoginForm__button Button Button--primary">
                                            <span>Setup account</span>
                                        </button>
                                    </div>

                                </div>



                            </div>


                        </form>
                    </div>
                    <div class="FooterClause">
                        μCMS - CS322 Final Project.
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<script>
    const usernameInput = document.getElementById("username");
    const passwordInput = document.getElementById("password");
    const loginButton = document.getElementById("loginButton");

    let isUsernameValid = false;
    let isPasswordValid = false;
    let isEmailValid = false;
    let isPasswordConfirmationValid = false;
    let isFirstNameValid = false;
    let isLastNameValid = false;
    let isBlogTitleValid = false;


    function validateUsername() {
        if (usernameInput.value.trim() === '') {
            showError('username', 'Username is required.');
            isUsernameValid = false;
        } else {
            clearError('username');
            isUsernameValid = true;
        }

        updateLoginButtonState();
    }

    function validateFirstName() {
        const firstNameInput = document.getElementById('firstName');
        const firstName = firstNameInput.value.trim();
        if (!firstName) {
            showError('firstName', 'First name is required.');
            isFirstNameValid = false;
        } else if (/[^a-zA-Z]/.test(firstName)) {
            showError('firstName', 'First name should only contain letters.');
            isFirstNameValid = false;
        } else {
            clearError('firstName');
            isFirstNameValid = true;
        }

        updateLoginButtonState();

    }

    function validateLastName() {
        const lastNameInput = document.getElementById('lastName');
        const lastName = lastNameInput.value.trim();
        if (!lastName) {
            showError('lastName', 'Last name is required.');
            isLastNameValid = false;
        } else if (/[^a-zA-Z]/.test(lastName)) {
            showError('lastName', 'Last name should only contain letters.');
            isLastNameValid = false;
        } else {
            clearError('lastName');
            isLastNameValid = true;
        }

        updateLoginButtonState();

    }

    function validatePassword() {
        if (passwordInput.value.trim() === '') {
            showError('password', 'Password is required.');
            isPasswordValid = false;
        } else {
            clearError('username');
            isPasswordValid = true;
        }

        updateLoginButtonState();
    }

    function validatePasswordConfirmation() {
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('passwordConfirmation');
        if (passwordInput.value !== passwordConfirmation.value) {
            showError('passwordConfirmation', 'Passwords do not match.');
            isPasswordConfirmationValid = false;
        } else {
            clearError('passwordConfirmation');
            isPasswordConfirmationValid = true;
        }

        updateLoginButtonState();

    }

    function validateEmail() {
        const emailInput = document.getElementById('email');
        const email = emailInput.value.trim();
        if (!email) {
            showError('email', 'Email is required.');
            isEmailValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { // Basic email validation regex
            showError('email', 'Invalid email format.');
            isEmailValid = false;
        } else {
            clearError('email');
            isEmailValid = true;
        }
    }

    function validateBlogTitle() {
        const blogTitleInput = document.getElementById('blogTitle');
        const blogTitle = blogTitleInput.value.trim();

        if (!blogTitle) {
            showError('blogTitle', 'Blog title is required');
            isBlogTitleValid = false;
        } else if (/[^a-zA-Z0-9_-]+/.test(blogTitle)) {
            showError('blogTitle', 'Blog title (domain) should only contain alpha-numerics or dashes and underscores.')
            isBlogTitleValid = false;
        } else {
            clearError('blogTitle');
            isBlogTitleValid = true;
        }

        updateLoginButtonState();
    }



    function showError(inputId, message) {
        const input = document.getElementById(inputId);
        const inputWrapper = document.querySelector(`div:has(> #${inputId})`);
        const errorIcon = document.querySelector(`div:has(> #${inputId}) svg`);
        const messageContainer = document.querySelector(`div:has(> #${inputId}) ~ .FormRequirement`);

        input.toggleAttribute("data-invalid");
        inputWrapper.toggleAttribute("data-invalid");
        errorIcon.toggleAttribute("data-visible");
        messageContainer.innerHTML = message;
    }

    function clearError(inputId) {
        const input = document.getElementById(inputId);
        const inputWrapper = document.querySelector(`div:has(> #${inputId})`);
        const errorIcon = document.querySelector(`div:has(> #${inputId}) svg`);
        const messageContainer = document.querySelector(`div:has(> #${inputId}) ~ .FormRequirement`);


        input.removeAttribute("data-invalid");
        inputWrapper.removeAttribute("data-invalid");
        errorIcon.removeAttribute("data-visible");
        messageContainer.innerHTML = '';
    }

    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const currentType = passwordInput.getAttribute("type");

        const toggleButton = document.querySelector('#password ~ span button');


        toggleButton.innerHTML =
            currentType === 'password' ?
            '<svg focusable="false" preserveAspectRatio="xMidYMid meet" fill="currentColor" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" class="cds--icon-visibility-off" xmlns="http://www.w3.org/2000/svg"><path d="M2.6,11.3l0.7-0.7C2.6,9.8,1.9,9,1.5,8c1-2.5,3.8-4.5,6.5-4.5c0.7,0,1.4,0.1,2,0.4l0.8-0.8C9.9,2.7,9,2.5,8,2.5 C4.7,2.6,1.7,4.7,0.5,7.8c0,0.1,0,0.2,0,0.3C1,9.3,1.7,10.4,2.6,11.3z"></path><path d="M6 7.9c.1-1 .9-1.8 1.8-1.8l.9-.9C7.2 4.7 5.5 5.6 5.1 7.2 5 7.7 5 8.3 5.1 8.8L6 7.9zM15.5 7.8c-.6-1.5-1.6-2.8-2.9-3.7L15 1.7 14.3 1 1 14.3 1.7 15l2.6-2.6c1.1.7 2.4 1 3.7 1.1 3.3-.1 6.3-2.2 7.5-5.3C15.5 8.1 15.5 7.9 15.5 7.8zM10 8c0 1.1-.9 2-2 2-.3 0-.7-.1-1-.3L9.7 7C9.9 7.3 10 7.6 10 8zM8 12.5c-1 0-2.1-.3-3-.8l1.3-1.3c1.4.9 3.2.6 4.2-.8.7-1 .7-2.4 0-3.4l1.4-1.4c1.1.8 2 1.9 2.6 3.2C13.4 10.5 10.6 12.5 8 12.5z"></path></svg>' :
            '<svg focusable="false" preserveAspectRatio="xMidYMid meet" fill="currentColor" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" class="cds--icon-visibility-on" xmlns="http://www.w3.org/2000/svg"><path d="M15.5,7.8C14.3,4.7,11.3,2.6,8,2.5C4.7,2.6,1.7,4.7,0.5,7.8c0,0.1,0,0.2,0,0.3c1.2,3.1,4.1,5.2,7.5,5.3 c3.3-0.1,6.3-2.2,7.5-5.3C15.5,8.1,15.5,7.9,15.5,7.8z M8,12.5c-2.7,0-5.4-2-6.5-4.5c1-2.5,3.8-4.5,6.5-4.5s5.4,2,6.5,4.5 C13.4,10.5,10.6,12.5,8,12.5z"></path><path d="M8,5C6.3,5,5,6.3,5,8s1.3,3,3,3s3-1.3,3-3S9.7,5,8,5z M8,10c-1.1,0-2-0.9-2-2s0.9-2,2-2s2,0.9,2,2S9.1,10,8,10z"></path></svg>';

        passwordInput.setAttribute("type", currentType === "password" ? "text" : "password");

    }

    function togglePasswordConfirmationVisibility() {
        const passwordInput = document.getElementById('passwordConfirmation');
        const currentType = passwordInput.getAttribute("type");

        const toggleButton = document.querySelector('#password ~ span button');


        toggleButton.innerHTML =
            currentType === 'password' ?
            '<svg focusable="false" preserveAspectRatio="xMidYMid meet" fill="currentColor" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" class="cds--icon-visibility-off" xmlns="http://www.w3.org/2000/svg"><path d="M2.6,11.3l0.7-0.7C2.6,9.8,1.9,9,1.5,8c1-2.5,3.8-4.5,6.5-4.5c0.7,0,1.4,0.1,2,0.4l0.8-0.8C9.9,2.7,9,2.5,8,2.5 C4.7,2.6,1.7,4.7,0.5,7.8c0,0.1,0,0.2,0,0.3C1,9.3,1.7,10.4,2.6,11.3z"></path><path d="M6 7.9c.1-1 .9-1.8 1.8-1.8l.9-.9C7.2 4.7 5.5 5.6 5.1 7.2 5 7.7 5 8.3 5.1 8.8L6 7.9zM15.5 7.8c-.6-1.5-1.6-2.8-2.9-3.7L15 1.7 14.3 1 1 14.3 1.7 15l2.6-2.6c1.1.7 2.4 1 3.7 1.1 3.3-.1 6.3-2.2 7.5-5.3C15.5 8.1 15.5 7.9 15.5 7.8zM10 8c0 1.1-.9 2-2 2-.3 0-.7-.1-1-.3L9.7 7C9.9 7.3 10 7.6 10 8zM8 12.5c-1 0-2.1-.3-3-.8l1.3-1.3c1.4.9 3.2.6 4.2-.8.7-1 .7-2.4 0-3.4l1.4-1.4c1.1.8 2 1.9 2.6 3.2C13.4 10.5 10.6 12.5 8 12.5z"></path></svg>' :
            '<svg focusable="false" preserveAspectRatio="xMidYMid meet" fill="currentColor" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" class="cds--icon-visibility-on" xmlns="http://www.w3.org/2000/svg"><path d="M15.5,7.8C14.3,4.7,11.3,2.6,8,2.5C4.7,2.6,1.7,4.7,0.5,7.8c0,0.1,0,0.2,0,0.3c1.2,3.1,4.1,5.2,7.5,5.3 c3.3-0.1,6.3-2.2,7.5-5.3C15.5,8.1,15.5,7.9,15.5,7.8z M8,12.5c-2.7,0-5.4-2-6.5-4.5c1-2.5,3.8-4.5,6.5-4.5s5.4,2,6.5,4.5 C13.4,10.5,10.6,12.5,8,12.5z"></path><path d="M8,5C6.3,5,5,6.3,5,8s1.3,3,3,3s3-1.3,3-3S9.7,5,8,5z M8,10c-1.1,0-2-0.9-2-2s0.9-2,2-2s2,0.9,2,2S9.1,10,8,10z"></path></svg>';

        passwordInput.setAttribute("type", currentType === "password" ? "text" : "password");

    }

    function updateLoginButtonState() {
        loginButton.disabled = !(isUsernameValid && isPasswordValid && isPasswordConfirmationValid && isEmailValid && isFirstNameValid && isLastNameValid && isBlogTitleValid);
    }

    updateLoginButtonState();
</script>