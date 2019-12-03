<?php

namespace Anax\View;

/**
 * A layout rendering views in defined regions.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

$lang = $lang ?? "sv";
$charset = $charset ?? "utf-8";
$title = ($title ?? "No title");

// Set active stylesheet
$request = $di->get("request");
if ($request->getGet("style")) {
    redirect("style/update/" . rawurlencode($_GET["style"]));
}


?>
<!doctype html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="<?= $charset ?>">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<!-- flash -->
<?php if (regionHasContent("flash")) : ?>
    <section class="region-flash">
        <?php renderRegion("flash") ?>
    </section>
<?php endif; ?>

<!-- main -->
<main>
    <?php if (regionHasContent("main")) : ?>
        <?php renderRegion("main") ?>
    <?php endif; ?>
</main>
</body>
</html>
