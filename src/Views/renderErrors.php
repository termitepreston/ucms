<?php if ($isSubmitted && !$isValid): ?>
    <div class="error">
        <p>Found the following errors:</p>
        <ol>
            <?php foreach ($results['errors'] as $field => $reason): ?>
                <li>
                    <p><?= $field ?>: <?= $reason ?></p>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
<?php endif; ?>