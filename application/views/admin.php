<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Perfect-Look CMS</title>
        <meta name="description" content="">

        <link rel="stylesheet" href="<?= base_url() ?>css/normalize.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/main.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/admin.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.20.custom.css">
        <?
        if (!empty($styles)) {
            foreach ($styles as $val) {
                ?><link rel="stylesheet" href="<?= base_url() ?>css/<?= $val ?>"><?
    }
}
        ?>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?= base_url() ?>js/jquery-1.7.1.min.js"><\/script>')</script>
        <script src="<?= base_url() ?>js/modernizr-2.5.3.min.js"></script>
        <script src="<?= base_url() ?>js/tiny_mce/jquery.tinymce.js"></script>
    </head>
    <body>


        <div id="content">
            <div class="bg" style="width: 1000px; margin: auto">
                <div id="menubar">
                    <ul id="menu">
                        <? $active = $this->uri->segment(2); ?>
                        <li <? if (empty($active)) echo 'class="selected"' ?>><a href="/admincp">Admin Home</a></li>
                        <li <? if ($active == 'users') echo 'class="selected"' ?>><a href="/admincp/users">Users</a></li>
                        <li <? if ($active == 'brands') echo 'class="selected"' ?>><a href="/admincp/brands">Brands</a></li>
                        <li <? if ($active == 'announcements') echo 'class="selected"' ?>><a href="/admincp/announcements">Announcements</a></li>
                        <li <? if ($active == 'help') echo 'class="selected"' ?>><a href="/admincp/help">Help</a></li>
                        <li <? if ($active == 'items') echo 'class="selected"' ?>><a href="/admincp/items">Items</a></li>
                        <li <? if ($active == 'links') echo 'class="selected"' ?>><a href="/admincp/links">Links</a></li>
                    </ul>
                </div>
                <br class="clear">
                <div style="padding: 0 20px">
                    <? $this->tpl->show_block('admin'); ?>
                </div>
            </div>
        </div>


        <script src="<?= base_url() ?>js/plugins.js"></script>
        <script src="<?= base_url() ?>js/jquery-ui-1.8.20.custom.min.js"></script>
        <script src="<?= base_url() ?>js/admin.js"></script>
        <?
        if (!empty($scripts)) {
            foreach ($scripts as $val) {
                ?><script src="<?= base_url() ?>js/<?= $val ?>"></script><?
    }
}
        ?>
    </body>
</html>