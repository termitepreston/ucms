<section>
    <h2>Manage Blog Entries</h2>
    <a class="button" href="index.php?action=newPage">Add a new entry</a>
    <table>
        <thead>
            <tr>
                <td>Title</td>
                <td>Slug</td>
                <td>Author</td>
                <td>Actions</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($blogs as $blog): ?>
                <tr>
                    <td><?= $blog["title"] ?></td>
                    <td><?= $blog["slug"] ?></td>
                    <td><?= $this->fetchUserFromId($blog["authorId"])["username"] ?></td>
                    <td>
                        <a id="removePageLink" class="button" href="index.php?action=removePage&id=<?= $blog["id"] ?>" data-title="<?= $blog['title'] ?>">Remove</a>
                        <a class="button" href="index.php?action=editPage&id=<?= $blog["id"] ?>">Edit</a>
                        <a class="button" href="index.php?action=viewPage&id=<?= $blog["id"] ?>">Visit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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

</body>

</html>