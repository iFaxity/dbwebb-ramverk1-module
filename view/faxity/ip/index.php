<?php

namespace Anax\View;

/**
 * View for "index" route of IP controller
 */
?>

<h1>IP validerare</h1>

<?php if (!is_null($valid)) : ?>
    <h3>IP addressen är
        <span style="color: <?= $valid ? '#0f0' : '#f00' ?>">
            <?= $valid ? "giltig" : "ogiltig" ?>
        </span>
    </h3>

    <?php if (!is_null($type)) : ?>
        <p>Protokolltypen är <?= $type ?>.</p>
    <?php endif; ?>

    <?php if (!is_null($domain)) : ?>
        <p>Domännamnet är <?= $domain ?>.</p>
    <?php endif; ?>

    <?php if (!is_null($country) && !is_null($region)) : ?>
        <p>Land och region: <?= $country ?>, <?= $region ?>.</p>
    <?php endif; ?>

    <?php if (!is_null($location)) : ?>
        <p>Latitud <?= $location->latitude ?>, longitud <?= $location->longitude ?>.</p>
    <?php endif; ?>
<?php endif; ?>

<form method="GET" action="">
    <div class="textfield">
        <input id="ip1" name="ip" value="<?= e($ip) ?>" placeholder=" " autocomplete="off"/>
        <label for="ip1">IP-address</label>
    </div>

    <button type="submit" class="solid">Validera</button>
</form>
