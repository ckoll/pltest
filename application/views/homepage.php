<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Perfect-Look</title>
  <meta name="description" content="">
  <link rel="stylesheet" href="<?= base_url() ?>css/normalize.css">
  <link rel="stylesheet" href="<?= base_url() ?>css/homepage.css">
  <script src="<?= base_url() ?>js/modernizr-2.5.3.min.js"></script>
</head>
<body>

  <div role="main">
      <? $this->tpl->show_block('homepage'); ?>
  </div>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="<?= base_url() ?>js/jquery-1.7.1.min.js"><\/script>')</script>
  <script src="<?= base_url() ?>js/plugins.js"></script>
  <script src="<?= base_url() ?>js/home-init.js"></script>
</body>
</html>