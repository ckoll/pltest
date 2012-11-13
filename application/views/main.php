<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Perfect-Look</title>
        <link href="/images/favicon.ico" type="image/ico" rel="shortcut icon">
        <meta name="description" content="">
        <link rel="stylesheet" href="<?= base_url() ?>css/normalize.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/main.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/dropdown.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.20.custom.css">
        <?
        if (!empty($styles)) {
            foreach ($styles as $val) {
                ?><link rel="stylesheet" href="<?= base_url() ?>css/<?= $val ?>"><?
            }
        }
        ?>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    </head>
    <body>

        <header id="top">
            <ul>
                <li><a href="/" name="scroll"><img src="/images/home1.png" class="imgTrans" data-hover="home1.png" data-nhover="home2.png"></a></li>
                <?php /*<li><a href="/dressup"><img src="/images/dressup1.png" class="imgTrans"  data-hover="dressup1.png" data-nhover="dressup2.png"></a></li>*/?>
                <li><a href="/dressup/dress/<?=$last_dressup?>"><img src="/images/dressup1.png" class="imgTrans"  data-hover="dressup1.png" data-nhover="dressup2.png"></a></li>
                <li><a href="/upload"><img src="/images/upload1.png" class="imgTrans"  data-hover="upload1.png" data-nhover="upload2.png"></a></li>
                <li><a href="/explore"><img src="/images/explore1.png" class="imgTrans"  data-hover="explore1.png" data-nhover="explore2.png"></a></li>
            </ul>

        </header>

        <div id="menu">
            <ul class="tabs">
                <li class="hasmore"><a href="#"><span><img src="/images/menu/else.png"></span></a>
                    <ul class="dropdown">
                        <li><a href="/mystuff/recent_photo_comments">New Comments</a></li>
                        <li><a href="/mystuff/recent_photo_likes">New Hearts</a></li>
                        <li><a href="/myfriends">New Friend Requests</a></li>
                        <li><a href="/my_gifts">New Gifts</a></li>
                        <li class="last"><a href="/wall">New Wall Messages</a></li>
                    </ul>
                </li>
                <li class="hasmore"><a href="#"><span><img src="/images/menu/message.png"></span></a>
                    <ul class="dropdown">
                        <li><a href="/pms">Inbox (<?=$this->user['messages']?> new)</a></li>
                        <li><a href="/pms/sent">Sent</a></li>
                        <li><a href="/pms/deleted">Deleted</a></li>
                        <li class="last"><a href="/pms/new">New message</a></li>
                    </ul>
                </li>
                <li class="hasmore"><a href="#"><span><img src="/images/menu/purse.png"></span></a>
                    <ul class="dropdown">
                        <li><a href="/mystuff/photos">My Photos</a></li>
                        <li><a href="/<?=$this->user['username']?>/dressup_calendar">My Dressups</a></li>
                        <li class="last"><a href="/dressup">My Items</a></li>
                    </ul>
                </li>
                <li class="hasmore"><a href="#"><span><img src="/images/menu/edit.png"></span></a>
                    <ul class="dropdown">
                        <li><a href="/editprofile">Edit Profile</a></li>
                        <li><a href="/find_friends">Find Friends</a></li>
                        <li class="last"><a href="/logout">Logout</a></li>
                    </ul>
                </li>

                <li><a href="/mystuff/button_history"><span><img src="/images/menu/button.png"> <?=$this->user['buttons']?> </span></a></li>
                <li><a href="/mystuff/diamonds_history"><span><img src="/images/menu/diamond.png"> <?=$this->user['jewels']?> </span></a></li>
            </ul>
        </div>

        <div id="main">
            <? $this->tpl->show_block('main'); ?>
            <br class="clear">
        </div>

        <footer id="mainfooter">
        </footer>

        <script src="<?= base_url() ?>js/plugins.js"></script>
        <script src="<?= base_url() ?>js/jquery-ui-1.8.20.custom.min.js"></script>
        <?
        if (!empty($scripts)) {
            foreach ($scripts as $val) {
                ?><script src="<?= base_url() ?>js/<?= $val ?>"></script><?
    }
}
        ?>
    </body>
</html>