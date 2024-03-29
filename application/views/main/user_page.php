<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span>user profile page of: <?=$user_info['username']?></span>
    </div>
    <div class="bg">

        <div class="left" style="width: 100px">
            <a href="/<?= $user_info['username'] ?>/wall" class="wall_button">my wall</a>
            <a href="/users_shop/<?= $user_info['username'] ?>" class="wall_button">my shop</a>
            <a class="wall_button like_button hearts <?=!$content['day_look']['liked']?'grey':''?> likes" data-id="<?= $content['day_look']['id'] ?>" data-mode="dressup" data-type="<?=$content['day_look']['liked']?'remove':'add'?>"><?=$content['day_look']['like']?></a>

        </div>
        <? if (!empty($content['day_look']['id'])) { ?>
        <a href="/<?= $user_info['username'] ?>/dressup/<?= $content['day_look']['id'] ?>">
            <img src="/files/users/dressup/<?= $content['day_look']['id'] ?>.jpg?v=<?= rand(1, 1000) ?>"
                 style="float: right"/>
        </a><br class="clear">
        <?
    } else {
        ?><img src="/files/room/room1.jpg" class="right"><?
    }
        ?>
        <div class="center"><? if (!empty($user_info['doll_name'])) {
            ?>dollname: <?=
            $user_info['doll_name'] ?><?
        }
            ?></div>
        <br class="clear">

        <h1>Wall</h1>

        <div class="wall">
            <section>
                <?
                if (!empty($content['wall'])) {
                    foreach ($content['wall'] as $val) {
                        ?>
                        <div class="wall_message">
                            <div class="wall_user">
                                <?= show_username_link($val['username']) ?><br>
                                <small><?= time_from($val['date']) ?></small>
                            </div>
                            <div class="left wall_text"><?= $val['text'] ?></div>
                            <br class="clear">
                        </div>
                        <?
                    }
                } else {
                    echo '<em class="center">No messages yet</em>';
                }
                ?>
            </section>
            <form action="" method="post" class="center">
                <textarea name="wall_message" style="float: none; width: 500px" data-default="leave a message">leave a
                    message</textarea><br>
                <input type="submit" name="wall_send" value="send" style="margin-left: 100px">
                <a href="/<?= $user_info['username'] ?>/wall" class="right">view all messages</a>
            </form>
        </div>
        <br>

        <h1>Latest Photos</h1>
        <ul class="columns4">
            <?
            if (!empty($content['latest_photos'])) {
                foreach ($content['latest_photos'] as $val) {
                    ?>
                    <li style="width: 170px">
                        <div class="img_bg" style="height: 150px; width: 160px; margin: 0 auto;">
                            <a href="/<?= $user_info['username'] ?>/photo/<?= $val['id'] . $val['rand_num'] ?>">
                                <img src="/files/users/uploads/<?= $val['uid'] ?>/<?= $val['id'] . $val['rand_num'] ?>.<?=$val['image_type']?>">
                            </a>

                        </div>
                        <p style="margin: 0 6px 0 auto; width: 160px;">
                            <?= time_from($val['date']) ?>
                        </p>
                        <div style="width: 135px; margin: 0 auto">
                            <span style="display: inline-block; margin: 3px auto; float: left" class="hearts <?=!$val['liked']?'grey':''?> likes" data-id="<?= $val['id'] . $val['rand_num'] ?>" data-mode="upload"  data-type="<?=$val['liked']?'remove':'add'?>"><?=$val['like']?></span>
                            <a href="/<?= $user_info['username'] ?>/photo/<?= $val['id'] . $val['rand_num'] ?>" style="display: inline-block; color: #47433F; float: right" class="comments"><?=intval($val['comments'])?></a>
                        </div>

                    </li>
                    <?
                }
            }
            ?>
        </ul>
        <a href="<?=$user_info['username']?>/stuff/photos" class="right">view all (<?=$content['latest_photos_count']?>
            )</a><br class="clear">
        <br>


        <h1>Dressup Calendar</h1>

        <div>
            <input type="hidden" name="user_id" value="<?= $user_info['id'] ?>">

            <div class="calendar"></div>
            <div id="calendar_scrollbar">
                <div class="viewport">
                    <div class="overview"></div>
                </div>
                <div class="scrollbar">
                    <div class="track">
                        <div class="thumb"></div>
                    </div>
                </div>
            </div>
            <br class="clear">
            <a href="/<?= $user_info['username'] ?>/dressup_calendar">view full calendar</a>
        </div>

        <br><br>

        <h1>Recent activity</h1>

        <div style="line-height: 20px">
            <?
            if (!empty($content['recently_activity'])) {
                foreach ($content['recently_activity'] as $last_activity) {
                    $str = '';
                    switch ($last_activity['type']) {
                        case 'dressup':
                            $dressup = $this->dressup_model->dressup_details($last_activity['type_id']);
                            if ($dressup) {
                                $str = 'made a new dressup at ' . $last_activity['date'] . ' (<a href="/' . $user_info['username'] . '/dressup/' . $last_activity['type_id'] . '" target="_blank">view</a>)';
                            }
                            break;
                        case 'brand':
                            $brand = $this->brands_model->get_one(intval($last_activity['type_id']));
                            if ($brand) {
                                $str = 'added a new favorite brand at ' . $last_activity['date'] . ' (<a href="/brands/' . str_replace(' ', '-', strtolower($brand['title'])) . '" target="_blank">' . $brand['title'] . '</a>)';
                            }
                            break;
                        case 'friend':
                            $friend = $this->user_model->get_user_info('id', $last_activity['type_id']);
                            if ($friend) {
                                $str = 'added a new friend at ' . $last_activity['date'] . ' (<a href="/' . $friend['username'] . '" target="_blank">' . $friend['username'] . '</a>)';
                            }
                            break;
                        case 'upload':
                            $upload = $this->upload_model->photo_details($last_activity['type_id']);
                            if ($upload) {
                                $str = 'upload a new photo at ' . $last_activity['date'] . ' (<a href="/' . $upload['username'] . '/photo/' . $last_activity['type_id'] . '" target="_blank">view</a>)';
                            }
                            break;
                    }
                    if ($str) {
                        echo $user_info['username'] . ' ' . $str;
                        echo '<br>';
                    }
                }
            }
            ?>

        </div>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>