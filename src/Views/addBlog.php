<h1>Create A New Blog Entry for <?= $username ?>.</h1>

<form id="blogEntryForm" action="" method="post">

    <?php require_once __DIR__ . '/renderErrors.php' ?>
    <div class="form-group">
        <label for="blogEntryTitle">Blog Entry Title:</label>
        <input type="text" id="blogEntryTitle" name="blogEntryTitle" onblur="validateBlogEntryTitle()" onfocus="clearError('blogEntryTitleError')" required>
        <div id="blogEntryTitleError" class="error" style="display: none;"></div>
    </div>

    <div class="form-group">
        <label for="blogEntrySlug">Blog Entry Slug:</label>
        <input type="text" id="blogEntrySlug" name="blogEntrySlug" onblur="validateBlogEntrySlug()" onfocus="clearError('blogEntrySlugError')" required>
        <div id="blogEntrySlugError" class="error" style="display: none;"></div>
    </div>

    <div class="form-group">
        <label for="blogEntryMarkdown">Markdown Text:</label>
        <textarea id="blogEntryMarkdown" name="blogEntryMarkdown" rows="10" required></textarea>
    </div>

    <div class="form-group">
        <button type="submit">Publish</button>
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