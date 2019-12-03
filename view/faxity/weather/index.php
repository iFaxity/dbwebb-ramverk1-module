<h1>Väderkollen</h1>
<p>
    Position kan vara en IP-address eller en geografisk position med latitud och långitud separerat med ett kommatecken (,).
    Se till så IP-addressen är giltig och har en geografisk position.
</p>

<form method="GET" action="">
    <div class="textfield">
        <input id="location" name="location" value="<?= esc($location) ?>" placeholder=" " autocomplete="off"/>
        <label for="location">Position</label>
    </div>

    <div class="checkbox">
        <input id="past-month" type="checkbox" name="past-month" value="true"/>
        <label for="past-month">Föregående 30 dagar</label>
    </div>

    <button type="submit" class="solid">Visa väderprognos</button>
</form>

<?php if (!is_null($res)) : ?>
    <h2>Koordinater: <?= implode(", ", $res->coords) ?></h2>

    <iframe
        class="weather-map" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
        src="https://www.openstreetmap.org/export/embed.html?bbox=<?= urlencode($bbox) ?>&marker=<?= urlencode($coords) ?>&layer=mapnik"
    >
    </iframe>


    <h2>Dagliga prognoser</h2>

    <div class="weather-forecast">
        <?php foreach ($res->data as $item) : ?>
            <div class="item">
                <div class="weather-icon <?= esc($item->icon) ?>"></div>
                <div class="data">
                    <div class="time"><?= esc($item->date) ?></div>
                    <div class="temp-min">Min: <?= esc($item->minTemp) ?></div>
                    <div class="temp-max">Max: <?= esc($item->maxTemp) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>
