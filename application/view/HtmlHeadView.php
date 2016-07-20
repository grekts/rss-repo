<?php

echo '<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <title>'.$pageTitle->titleTextData.'</title>
    <meta name="description" content="'.$pageMetaDescription->metaDescriptionTextData.'" />
    <link rel="shortcut icon" href="public/image/for-site/favicon3.ico" type="image/x-icon" />
    <link rel="stylesheet" href="public/css/'.$cssFile->nameCssFileTextData.'" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />'.$robotsAccess->codeBanAccessRobotsOnPageCode.'		
</head>';
