<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span>Wall</span>
    </div>
    <div class="bg">

        <section>
            <?
            if (!empty($wall)) {
                foreach ($wall as $val) {
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
        <form action="<?= '/'.$this->uri->segment(1).(($this->uri->segment(2)!="")?'/'.$this->uri->segment(2):'') ?>" method="post" class="center">
            <textarea name="wall_message" style="float: none; width: 500px" data-default="leave a message">leave a message</textarea><br>
            <input type="submit" name="wall_send" value="send" >
        </form>
        <br>
        <?
        pagination($pages);
        ?>
                <br class="clear">
    </div>
    <div class="footer"></div>
</div>