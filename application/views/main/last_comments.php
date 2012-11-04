<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div class="page_title">
        <span>Last Comments</span>
    </div>
    <div class="bg">
        <?
        if (!empty($comments)) {
            foreach ($comments as $val) {
                ?>
                <div class="news_block">

                    <?
                    if ($val['type'] == 'dressup') {
                        ?>
                        <a class="left news_img" href="/<?= $this->user['username'] ?>/dressup/<?= $val['id'] ?>">
                            <img src="/files/users/dressup/<?= $val['id'] ?>.jpg" style="width: 160px">
                        </a>
                    <? } else {
                        ?>
                        <a class="left news_img" href="/<?= $this->user['username'] ?>/photo/<?= $val['id'] . $val['rand_num'] ?>">
                            <img src="/files/users/uploads/<?= $val['uid'] ?>/<?= $val['id'] . $val['rand_num'] ?>.jpg" style="width: 160px">
                        </a>   
                        <? }
                    ?>
                    <div class="left comment_details">
                        <a href="/<?=$comments_details[$val['type']][$val['id']]['username']?>">
                        <img src="<?= get_user_avatarlink($comments_details[$val['type']][$val['id']]['uid']) ?>" class="small_avatar">
                        </a>
                        <strong><?= time_from($val['last_comment']) ?></strong><br class="clear">
                        <p><?= $comments_details[$val['type']][$val['id']]['comment'] ?></p>
                    </div><br class="clear">
                </div>
                <?
            }
        } else {
            ?><em>*No comments</em><?
    }
        ?>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>