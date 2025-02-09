<h1>Edit the Blog Entry of <?= $username ?>.</h1>

<form id="blogEntryForm" action="" method="post">

    <div class="stack-vertical stack-scale-7">
        <?php require_once __DIR__ . '/renderErrors.php' ?>
        <div class="form-item">
            <div class="text-input__label-wrapper"><label for="blogEntryTitle" class="label">Blog Entry Title</label></div>
            <div class="text-input__field-outer-wrapper">
                <div class="text-input__field-wrapper" data-invalid="true">
                    <svg focusable="false" preserveAspectRatio="xMidYMid meet" fill="currentColor" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" class="text-input__invalid-icon" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8,1C4.2,1,1,4.2,1,8s3.2,7,7,7s7-3.1,7-7S11.9,1,8,1z M7.5,4h1v5h-1C7.5,9,7.5,4,7.5,4z M8,12.2 c-0.4,0-0.8-0.4-0.8-0.8s0.3-0.8,0.8-0.8c0.4,0,0.8,0.4,0.8,0.8S8.4,12.2,8,12.2z"></path>
                        <path d="M7.5,4h1v5h-1C7.5,9,7.5,4,7.5,4z M8,12.2c-0.4,0-0.8-0.4-0.8-0.8s0.3-0.8,0.8-0.8 c0.4,0,0.8,0.4,0.8,0.8S8.4,12.2,8,12.2z" data-icon-path="inner-path" opacity="0"></path>
                    </svg>
                    <input type="text" id="blogEntryTitle" class="text-input text-input__invalid" name="blogEntryTitle" onblur="validateBlogEntryTitle()" onfocus="clearError('blogEntryTitleError')" value="<?= $blog['title'] ?? '' ?>" required>
                    <span class="text-input__counter-alert" role="alert" aria-live="assertive" aria-atomic="true"></span>
                </div>
                <div class="form-requirement" id="test4-error-msg" dir="ltr">
                    Your password must be at least 6 characters as well as contain at least one uppercase one lowercase, and one number.
                </div>
            </div>
            <div id="blogEntryTitleError" class="error" style="display: none;"></div>
        </div>
        <div class="form-item">
            <div class="text-input__label-wrapper"><label for="blogEntrySlug" class="label">Blog Entry Slug</label></div>
            <div class="text-input__field-outer-wrapper">
                <div class="text-input__field-wrapper"><input type="text" id="blogEntrySlug" class="text-input text-input__invalid" name="blogEntrySlug" onblur="validateBlogEntrySlug()" onfocus="clearError('blogEntrySlugError')" value="<?= $blog['title'] ?? '' ?>" required></div>
            </div>
            <div id="blogEntrySlugError" class="error" style="display: none;"></div>
        </div>
        <div class="form-item">
            <div class="text-input__label-wrapper"><label for="blogEntryMarkdown" class="label">Markdown Text:</label></div>
            <div class="text-area__wrapper">
                <textarea class="text-area" id="blogEntryMarkdown" name="blogEntryMarkdown" rows="10" style="width: 100%" required>
                    <?= $blog['content'] ?? '' ?>
                </textarea>
            </div>
        </div>
        <div class="form-item">
            <button class="button button--primary" type="submit">Update</button>
        </div>
    </div>
</form>

<script>
    const form = document.getElementById('blogEntryForm');

    function clearError(errorElementId) {
        document.getElementById(errorElementId).style.display = 'none';
    }

    function displayError(errorElementId, message) {
        const errorElement = document.getElementById(errorElementId);
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }

    function validateBlogEntryTitle() {
        const titleInput = document.getElementById('blogEntryTitle');
        const title = titleInput.value.trim();
        if (!title) {
            displayError('blogEntryTitleError', 'Blog title cannot be empty.');
            return false;
        } else if (/[^a-zA-Z0-9\s\p{P}]/.test(title)) { // Allows alphanumerics, spaces and punctuation from any language (unicode property)
            displayError('blogEntryTitleError', 'Title should not contain special characters beyond basic punctuation.');
            return false;
        } else {
            clearError('blogEntryTitleError');
            return true;
        }
    }

    function validateBlogEntrySlug() {
        const slugInput = document.getElementById('blogEntrySlug');
        const slug = slugInput.value.trim();
        if (!/^[a-zA-Z0-9_-]+$/.test(slug)) {
            displayError('blogEntrySlugError', 'Slug should only contain alphanumeric characters, underscores, and dashes.');
            return false;
        } else {
            clearError('blogEntrySlugError');
            return true;
        }
    }

    form.addEventListener('submit', function(event) {
        let isTitleValid = validateBlogEntryTitle();
        let isSlugValid = validateBlogEntrySlug();

        if (!isTitleValid || !isSlugValid) {
            event.preventDefault(); // Prevent form submission if validation fails
            alert('Please correct the errors in the form.');
        }
    });
</script>