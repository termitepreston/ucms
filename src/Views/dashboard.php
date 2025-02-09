<section>
    <h3><a href="index.php?action=logout">Logout</a></h3>
    <div class="table-container">
        <div class="table-header">
            <h4 class="table-header__title">Blog posts</h4>
            <p class="table-header__description">Manage Blog Entries (<?= $isAdmin ? 'admin' : 'author' ?> version)</p>

        </div>
        <div class="table-toolbar">
            <div class="toolbar-content">
                <a class="button button--primary" href="index.php?action=addBlog">Add a new entry</a>

            </div>
        </div>
        <div class="table-content">
            <table class="table table--lg table--visible-overflow-menu">
                <thead>
                    <tr>
                        <th>
                            <div class="table-header-label">Title</div>
                        </th>
                        <th>
                            <div class="table-header-label">Slug</div>
                        </th>
                        <th>
                            <div class="table-header-label">Author</div>
                        </th>
                        <th>
                            <div class="table-header-label">Actions</div>
                        </th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($blogs as $blog): ?>
                        <tr>
                            <td><?= $blog["title"] ?></td>
                            <td><?= $blog["slug"] ?></td>
                            <td><?= $this->fetchUserFromId($blog["authorId"])["username"] ?></td>
                            <td>
                                <a id="removePageLink" class="button button--primary" href="index.php?action=deleteBlog&id=<?= $blog["id"] ?>" data-title="<?= $blog['title'] ?>">Remove</a>
                                <a class="button button--primary" href="index.php?action=editBlog&id=<?= $blog["id"] ?>">Edit</a>
                                <a class="button button--primary" href="index.php?action=visitBlog&id=<?= $blog["id"] ?>">Visit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    const removePageLink = document.getElementById("removePageLink");


    removePageLink.addEventListener("click", event => {
        const blogTitle = removePageLink.getAttribute('data-title');
        console.log(blogTitle);

        const confirmation = window.confirm(`Are you sure you want to delete ${blogTitle}`)

        if (!confirmation) {
            event.preventDefault();
        } else {
            console.log(`Deleting ${blogTitle}`);
        }
    });
</script>