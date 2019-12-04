<?php

namespace Anax\View;

/**
 * View for "docs" route of Weather controller
 */
?>

<h2>Väder API Documentation</h2>

<h3>Testa APIet</h3>

<form method="post" action="<?= e($apiUrl) ?>">
    <div class="textfield">
        <input id="location" name="location" placeholder=" " autocomplete="off"/>
        <label for="location">Position</label>
    </div>

    <div class="checkbox">
        <input id="past-month" type="checkbox" name="past-month" value="true"/>
        <label for="past-month">Föregående 30 dagar</label>
    </div>

    <button type="submit" class="solid">Visa väderprognos</button>
</form>


<h3>Om APIet</h3>
<p>För att använda dig av valideringsverktygets API använd URLen:</p>
<p>POST <?= e($apiUrl ) ?></p>
<p>
    I POST bodyn ska där finnas en "location" parameter som kan vara antingen en som en sträng "latitud, longitud" eller en IP-address.
    Där finns en optionell parameter "past-month" som kan användas för att visa föregånde 30 dagarnas väderprognoser.
    Denna parametern behöver inget värde då det ignoreras, bara den existerar så visas föregånde 30 dagarnas prognoser.
</p>
<p>
    Glöms "location" parametern eller är ogiltig t.ex om IP-addressen inte kan lokaliseras, så visas ett felmeddelande med statusen 400.
    Felmeddelanden skickas i formatet nedan:
    <pre><code><?= e($examples->err) ?></code></pre>
</p>
<p>
    En lyckad respons skickas med statusen 200 och ser ut såhär:
    <pre><code><?= e($examples->ok) ?></code></pre>
</p>

