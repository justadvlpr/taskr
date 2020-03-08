<?php

/**
 * @var \Yiisoft\Router\UrlGeneratorInterface $urlGenerator
 * @var \Yiisoft\View\WebView $this
 * @var \App\Entity\User $user
 * @var \Yiisoft\Assets\AssetManager $assetManager
 * @var string $content
 * @var null|string $currentUrl
 */

$this->beginPage();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.8.95/css/materialdesignicons.min.css" rel="stylesheet"/>
    <link href="/assets/css/app.css" rel="stylesheet" type="text/css"/>
    <title>taskr.</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>

<noscript>
    <div style="text-align: center; font-family: arial, verdana, sans-serif; margin-top: 100px;">
        <h4>JavaScript needs to be enabled in order to see this website.</h4>
    </div>
</noscript>

<div id="app"></div>

<script src="/assets/js/app.js"></script>

<?php $this->endBody(); ?>
</body>
</html>
<?php
$this->endPage(true);
