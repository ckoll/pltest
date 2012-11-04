<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div class="page_title">
        <span>user profile page of: <?=$this->user['username']?></span>
    </div>
    <div class="bg">
        
        <? if (!empty($content['last_dressup']['id'])) { ?>
            <a href="/<?= $this->user['username'] ?>/dressup/<?= $content['last_dressup']['id'] ?>">
                <img src="/files/users/dressup/<?= $content['last_dressup']['id'] ?>.jpg?v=<?= rand(1, 1000) ?>">
            </a><br class="clear">
        <? } else {
            ?><img src="/files/room/room1.jpg"><br class="clear"><? }
        ?>
        <div>
            your doll: <span><?= (!empty($this->user['doll_name'])) ? $this->user['doll_name'] : 'Not set yet ' ?></span> <a id="new_doll_name">edit</a>
            <span class="hide dollname_form">
                <input type="text" name="doll_name" maxlength="30" value="<? if (!empty($this->user['doll_name'])) echo $this->user['doll_name']; ?>"> 
                <input type="button" name="save_dollname" value="save"> 
                <input type="button" name="save_dollname" value="cancel">
            </span>
        </div>
        <br/>
        
        <input type="hidden" name="user_id" value="<?= $this->user['id'] ?>">
        
        <h1 class="box-title show-hide-button">Latest dressups</h1>
        <div class="box show-hide-box">
            <div class="calendar"></div>

            <div id="calendar_scrollbar">
                <div class="viewport">
                    <div class="overview"></div>
                </div>
                <div class="scrollbar"><div class="track"><div class="thumb"></div></div></div>
            </div>

            <br class="clear">
            <a href="/<?= $this->user['username'] ?>/dressup_calendar">view full calendar</a>
        </div>


        <? if (count($content['announcements']) > 0) { ?>
            <h1 class="box-title show-hide-button">site announcements</h1>
            <div class="box show-hide-box">
                <? $ann_count = 0; ?>
                <?
                if (!empty($content['announcements'])) {
                    foreach ($content['announcements'] as $announcment) {
                        ?>
                        <? $ann_count++; ?>
                        <b><a href="/announcements/<?= $announcment['id'] ?>"><?= $announcment['title'] ?></a> | <?= $announcment['date'] ?></b>
                        <p><?= (strlen($announcment['text']) > 300) ? substr($announcment['text'], 0, 300) . '...' : $announcment['text']; ?></p>
                        <? if ($ann_count < count($content['announcements'])) { ?>
                            <hr>
                        <?
                        }
                    }
                }

                if (count($content['announcements']) > 1) {
                    ?>
                    <br><p><a href="/announcements">see all news</a></p>
            <? } ?>
            </div>
<? } ?>

        <h1 class="box-title show-hide-button">My friends' latest doll updates</h1>
        <div class="box show-hide-box">
            <ul class="columns4">
                <? foreach ($content['friends_dolls'] as $key => $doll) {
                    ?>
                    <li>
                        <a href="/<?= $doll['username'] ?>/dressup/<?= $doll['id'] ?>">
                            <img src="/files/users/dressup/<?= $doll['id'] ?>.jpg">
                        </a>
                        <p>
                            <a href="/<?= $doll['username'] ?>"><?= $doll['username'] ?></a>
                            <br><?= time_from($doll['date']) ?>
                        </p>
                    </li>
                    <?
                    if ($key == 3)
                        break;
                }
                ?>
            </ul>
    <!--        <p><a href="#">see more</a></p>-->
        </div>

        <h1 class="box-title show-hide-button">My friends' latest photos</h1>
        <div class="box show-hide-box">
            <ul class="columns4">
<? foreach ($content['friends_photos'] as $photos) {
    ?>
                    <li>
                        <a href="/<?= $photos['username'] ?>/photo/<?= $photos['id'] . $photos['rand_num'] ?>">
                            <img src="/files/users/uploads/<?= $photos['uid'] ?>/<?= $photos['id'] . $photos['rand_num'] ?>.jpg">
                        </a>
                        <p>
                            <a href="/<?= $photos['username'] ?>"><?= $photos['username'] ?></a>
                        </p>
                    </li>
<? } ?>
            </ul>
    <!--        <p><a href="#">see more</a></p>-->
        </div>

        <h1 class="box-title show-hide-button">Recently added friends</h1>
        <div class="box show-hide-box">
            <ul class="columns4">
                <?
                if (!empty($content['recently_friends'])) {
                    $x = 0;
                    foreach ($content['recently_friends'] as $friend) {
                        ?>
                        <li class="center">
                            <a class="avatar100" href="/<?= $friend['username'] ?>">
                                <div class="border"></div>
                                <img src="<?= get_user_avatarlink($friend['id']) ?>">
                            </a>
                            <p>
                        <?= show_username_link($friend['username']) ?>
                                <br><small>added <?= time_from($friend['adding']) ?></small>
                            </p>
                        </li>
                        <?
                        if ($x == 4)
                            break;
                        $x++;
                    }
                }
                ?>
            </ul>
<? if (count($content['recently_friends']) > 0) { ?>
                <p><a href="/myfriends">view all (<?= count($content['recently_friends']) ?>)</a></p>
<? } ?>
        </div>
        <br class="clear">
    </div>

    <div class="footer"></div>
</div>