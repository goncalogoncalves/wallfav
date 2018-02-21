<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!--[if ie]><meta content='IE=8' http-equiv='X-UA-Compatible'/><![endif]-->
    <?php global $array_global_configs;?>
    <title><?php echo $array_global_configs['website_titulo']; ?></title>
    <meta name="description" content="<?php echo $array_global_configs['website_descricao']; ?>">
    <meta name="keywords" content="<?php echo $array_global_configs['website_keywords']; ?>">
    <meta name="summary" content="<?php echo $array_global_configs['website_descricao']; ?>">
    <meta name="viewport" content="width=device-width , user-scalable=no">
    <meta name="copyright" content="<?php echo $array_global_configs['website_nome']; ?>">
    <meta name="author" content="Gonçalo Gonçalves, goncalo@wallfav.com">
    <meta name="reply-to" content="goncalo@wallfav.com">
    <meta name="language" content="EN">
    <meta name="url" content="http://www.wallfav.com">
    <meta name="coverage" content="Worldwide">
    <meta name="distribution" content="Global">
    <meta name="rating" content="General">
    <meta name="revisit-after" content="4 days">
    <meta name="apple-mobile-web-app-capable" content="yes"> <!-- para fullscreen -->
    <meta name="apple-mobile-web-app-title" content="<?php echo $array_global_configs['website_nome']; ?>">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="robots" content="index, follow">
    <meta name="og:title" content="<?php echo $array_global_configs['website_titulo']; ?>">
    <meta name="og:type" content="bookmarks">
    <meta name="og:url" content="http://www.wallfav.com">
    <meta name="og:image" content="http://www.wallfav.dev/img/logo_wallfav.png">
    <meta name="og:site_name" content="<?php echo $array_global_configs['website_nome']; ?>">
    <meta name="og:description" content="<?php echo $array_global_configs['website_descricao']; ?>">
    <meta name="fb:page_id" content="693187967412453">
    <meta name="application-name" content="<?php echo $array_global_configs['website_nome']; ?>">
    <meta name="og:email" content="wallfav@wallfav.com">
    <meta http-equiv="cache-control" content="public">
    <meta http-equiv="expires" content="Sun, 19 Jan 2014 11:12:13 GMT">
    <meta http-equiv="cleartype" content="on">

    <link rel="shortcut icon" type="image/ico" href="<?php echo BASE_URL; ?>img/favicon.ico">
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>img/apple-touch-icon.png">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/todos.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/homepage.css">

    <script src="<?php echo BASE_URL; ?>js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    <script src="<?php echo BASE_URL; ?>js/vendor/jquery-1.11.0.min.js"></script>

    <script>
        var BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
</head>
<body>
