<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Perfect-look.org</title>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/startpage.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="<?= base_url() ?>js/plugins.js"></script>
    <script src="<?= base_url() ?>js/jquery-ui-1.8.20.custom.min.js"></script>
    <script src="<?= base_url() ?>js/user-init.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.20.custom.css">

</head>

<body>

<div id="apDiv1"><img src="images/doll-1.png"/></div>


<div id='main-cont'>

    <div id='top-cont'>
        <div id='top-buttons'>
            <a class="top-button" href="/register">sign up</a>
            <a class="top-button" href="/signin">log in</a>
        </div>

        <div id='soc-buttons'>
            <a id='fb-button' class="socbutton" href="http://facebook.com/perfectlookorg">&nbsp;</a>
            <a id='tw-button' class="socbutton" href="http://twitter.com/perfectlookorg">&nbsp;</a>
            <a id='email-button' class="socbutton" href="mailto:perfectlookorg[at]gmail.com">&nbsp;</a>
        </div>
        <div id='logo'></div>
    </div>


    <div id='bottom-cont'>
        <div id='center-cont'>
            <? $this->tpl->show_block('startpage'); ?>
        </div>
        <div id='left-cont'>
            <div class="sidetitle">
                <h1>Search</h1>
            </div>
            <div class="sidetitle">
                <h1>Top Tags</h1>
            </div>

        </div>
    </div>
</div>


</body>
</html>