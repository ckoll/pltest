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
            <div class="part">
                <input name="Search the site" type="text" maxlength="22" />
                <input type="submit" name="submit" value="Search" />
                </div>
            <div class="sidetitle">
                <h1>Top Tags</h1>
            </div>
            <div class="part">
                <a href="http://v1.perfect-look.org/tags/fashion" class="style1">fashion</a>,<a href="http://v1.perfect-look.org/tags/hair"> hair</a>, <a href="http://v1.perfect-look.org/tags/makeup" class="style2">makeup</a>, <a href="http://v1.perfect-look.org/tags/nails">nails</a>, <a href="http://v1.perfect-look.org/tags/celebs">celebs</a>, <a href="http://v1.perfect-look.org/tags/personal" class="style3">personal</a>
            </div>
            <div class="sidetitle">
                <h1>Latest Announcements</h1>
            </div>
            <div class="part">
                <ul>
                    <li><a href="http://news.perfect-look.org/index.php?subaction=showcomments&amp;id=1351398638&amp;archive=&amp;start_from=&amp;ucat=&amp;">Perfect-Look.Org Free Hosting</a></li>
                    <li><a href="http://news.perfect-look.org/index.php?subaction=showcomments&amp;id=1351220972&amp;archive=&amp;start_from=&amp;ucat=&amp;">Looking for feedback sessions helpers</a></li>
                    <li><a href="http://news.perfect-look.org/index.php?subaction=showcomments&amp;id=1351180511&amp;archive=&amp;start_from=&amp;ucat=&amp;">Secret Items for the first 1000 users released</a></li>
                    <li><a href="http://news.perfect-look.org/index.php?subaction=showcomments&amp;id=1351025166&amp;archive=&amp;start_from=&amp;ucat=&amp;">New Halloween Items</a></li>
                    <li><a href="http://news.perfect-look.org/index.php?subaction=showcomments&amp;id=1349977231&amp;archive=&amp;start_from=&amp;ucat=&amp;">Perfect-Look.Org Promo Contest</a></li>
                </ul>
            </div>

            <div class="sidetitle">
                <h1>Featured Users</h1>
            </div>
            <div class="part">
                <a href="http://v1.perfect-look.org/diirectioner"><img src="images/user01.jpg" width="60" height="60" /></a> <a href="http://v1.perfect-look.org/taylorswift"><img src="images/user02.jpg" width="60" height="60" /></a> <a href="http://v1.perfect-look.org/_ibiebsswag"><img src="images/user03.jpg" width="60" height="60" /></a> <a href="http://v1.perfect-look.org/bdaysuits"><img src="images/user04.jpg" width="60" height="60" /></a></a> <a href="http://v1.perfect-look.org/perfectlookadmin"><img src="images/user05.jpg" width="60" height="60" /></a>
            </div>

            <div class="sidetitle">
                <h1>LATEST DRESSUP ITEMS</h1>
            </div>
            <div class="part">
                <table width="100%" border="0" cellspacing="3" cellpadding="0">
                    <tr>
                        <td><div align="center"><img src="http://v1.perfect-look.org/files/items/halloween/profilepics/profilepics-bat.png" width="60" height="60" /></div></td>
                        <td><div align="center"><img src="http://v1.perfect-look.org/files/items/halloween/profilepics/profilepics-cauldron.png" width="60" height="60" /></div></td>
                        <td><div align="center"><img src="http://v1.perfect-look.org/files/items/halloween/profilepics/profilepics-witchhat.png" width="60" height="60" /></div></td>
                    </tr>
                    <tr>
                        <td><div align="center"><a href="http://v1.perfect-look.org/item/bat">Bat</a></div></td>
                        <td><div>
                            <div align="center"><a href="http://v1.perfect-look.org/item/cauldron">Cauldron </a></div>
                        </div></td>
                        <td><div>
                            <div align="center"><a href="http://v1.perfect-look.org/item/witchhat">Witch Hat </a></div>
                        </div></td>
                    </tr>
                    <tr>
                        <td><div align="center"><img src="http://v1.perfect-look.org/files/items/halloween/profilepics/profilepics-witchoutfit.png" width="60" height="60" /></div></td>
                        <td><div align="center"><img src="http://v1.perfect-look.org/files/items/halloween/profilepics/profilepics-shoes-halloween.png" width="60" height="60" /></div></td>
                        <td><div align="center"><img src="http://v1.perfect-look.org/files/items/innerwear/profilepics/Profilepics-Innerwear-stockings1.png" width="60" height="60" /></div></td>
                    </tr>
                    <tr>
                        <td><div align="center">
                            <div> <a href="http://v1.perfect-look.org/item/witchoutfit">Witch Outfit</a> </div>
                        </div></td>
                        <td><div align="center">
                            <div><a href="http://v1.perfect-look.org/item/shoes-halloween"> Halloween Shoes </a></div>
                        </div></td>
                        <td><div align="center"><a href="http://v1.perfect-look.org/item/stockings">Stockings</a></div></td>
                    </tr>
                </table>
            </div>


        </div>
    </div>
</div>


</body>
</html>