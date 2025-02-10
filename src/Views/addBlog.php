<h1>Create A New Blog Entry for <?= $username ?>.</h1>

<form id="blogEntryForm" method="post" action="<?= $_SERVER['REQUEST_URI'];?>">

    <div class="stack-vertical stack-scale-7">
        <?php require_once __DIR__ . '/renderErrors.php' ?>
        <div class="form-item">
            <div class="text-input__label-wrapper"><label for="blogEntryTitle" class="label">Blog Entry Title</label></div>
            <div class="text-input__field-outer-wrapper">
                <div class="text-input__field-wrapper" data-invalid="true">
                    <input type="text" id="blogEntryTitle" class="text-input text-input__invalid" name="blogEntryTitle" onblur="validateBlogEntryTitle()" onfocus="clearError('blogEntryTitleError')" required>
                    <span class="text-input__counter-alert" role="alert" aria-live="assertive" aria-atomic="true"></span>
                </div>
                <div class="form-requirement" id="blogEntryTitleError" dir="ltr" style="display: none;">
                </div>
            </div>
        </div>
        <div class="form-item">
            <div class="text-input__label-wrapper">
                <label for="blogEntrySlug" class="label">Blog Entry Slug</label>
            </div>
            <div class="text-input__field-outer-wrapper">
                <div class="text-input__field-wrapper">
                    <input type="text" id="blogEntrySlug" class="text-input text-input__invalid" name="blogEntrySlug" onblur="validateBlogEntrySlug()" onfocus="clearError('blogEntrySlugError')" required>
                </div>
                <div class="form-requirement" id="blogEntrySlugError" dir="ltr" style="display: none;">
                </div>
            </div>
        </div>
        <div class="form-item">
            <div class="text-input__label-wrapper"><label for="blogEntryMarkdown" class="label">Markdown Text:</label></div>
            <div class="text-area__wrapper">
                <textarea placeholder="Write your content here..." class="text-area" id="blogEntryMarkdown" name="blogEntryMarkdown" rows="10" style="width: 100%" required></textarea>
            </div>
        </div>
        <div class="form-item">
            <button class="button button--primary" type="submit">Publish</button>
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