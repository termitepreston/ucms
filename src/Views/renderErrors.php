<?php if ($isSubmitted && !$isValid): ?>
    <div class="ErrorHeader">
        <div class="InlineNotification">
            <div class="InlineNotification__textWrapper">
                <div class="InlineNotification__title">Errors</div>
                <div class="InlineNotification__subtitle">
                    <ol>
                        <?php foreach ($results['errors'] as $field => $reason): ?>
                            <li>
                                <p><?= $field ?>: <?= $reason ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>