<?php

namespace Anax\View;

/**
 * View for the API of the IP controller
 */
?>

<h2>JSON API</h2>
<p>
    För att få ut samma information som formuläret ovan så finns ett API att använda på sidan.
    Testa IP APIet direkt via formuläret nedan.
</p>
<form method="post" action="<?= e($apiUrl) ?>">
    <div class="textfield">
        <input id="ip2" name="ip" value="<?= $ip ?>" placeholder=" " autocomplete="off"/>
        <label for="ip2">IP-address</label>
    </div>

    <button type="submit" class="solid">Validera</button>
</form>


<h3>Test addresser</h3>
<p>Testa APIet direkt via testen nedan, klicka på en knapp för att testa med förbestämda IP addresser.</p>
<form method="post" action="<?= e($apiUrl) ?>">
    <?php foreach ($addrs as $idx => $addr) : ?>
        <button type="submit" name="ip" value="<?= e($addr) ?>">Test <?= $idx + 1 ?></button>
    <?php endforeach; ?>
</form>


<h3>Om APIet</h3>
<p>För att använda dig av valideringsverktygets API använd:</p>
<p>POST <?= e($apiUrl) ?></p>
<p>
    I POST bodyn så ska där finnas med en "ip" parameter med IP-addressen som skall verifieras.
    Om parametern glöms eller är tom så skickas en respons med statusen 400 och kan se ut såhär:
    <pre><code><?= e($examples->err) ?></code></pre>
</p>
<p>
    Annars skickas en respons med statusen 200 och kan se ut såhär:
    <pre><code><?= e($examples->ok) ?></code></pre>
</p>
<ul>
    <li>
        Attributen "ip" är alltid den skickade ip addressen, även om addressen inte är giltig.
    </li>
    <li>
        Attributen "valid" kan vara true eller false beroende på om IP addressen var giltig eller ej.
    </li>
    <li>
        Attributen "type" är antingen "ipv4" eller "ipv6", om inte adressen är giltig är attributens värde null.
    </li>
    <li>
        Alla andra attributer är en sträng eller null beroende om ip addressen är giltig, och om data hittades för attributen.
        T.ex har addressen inget domännamn kopplat till sig är attributen "domain" null.
    </li>
</ul>
