<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
</div>

<div id="content">
    <div class="page_title">
        <span><?=$title?></span>
    </div>
    <div class="bg">
        <?
        if (!empty($comments)) {
            foreach ($comments as $comment) {
                ?>
                <div>
                    <div style="float:left; width:150px; margin:0 8px; text-align: center; height: 150px;"><a
                            href="/<?=$user['username']?>/photo/<?=$comment['full_photo_id']?>"><img
                            style="max-width: 140px; max-height: 140px;"
                            src='/files/users/uploads/<?=$user['id'] . '/' . $comment['full_photo_id'] . '.'.$comment['image_type']?>'></a>
                    </div>
                    <div><p><?=$comment['comment']?><br>comment by: <a
                            href="/<?=$comment['username']?>"><?=$comment['username']?></a> /
                        on: <?=$comment['date_comment']?></p></div>
                </div>
                <br class="clear">
                <? }
        }?>
        <br class="clear">
        <? pagination($pages);?>
    </div>
    <div class="footer"></div>
</div>
