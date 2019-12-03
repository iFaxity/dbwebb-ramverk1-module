<?php

namespace Anax\View;

/**
 * Renders flash messages
 */
?>

<?php foreach ($messages as $message) : ?>
    <div class="message <?= esc($message->type) ?>">
        <div class="content"><?= esc($message->text) ?></div>
    </div>
<?php endforeach; ?>

