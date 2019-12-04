<?php

namespace Anax\View;

/**
 * Renders flash messages
 */
?>

<?php foreach ($messages as $message) : ?>
    <div class="message <?= e($message->type) ?>">
        <div class="content"><?= e($message->text) ?></div>
    </div>
<?php endforeach; ?>

