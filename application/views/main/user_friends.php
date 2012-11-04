<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span>User friends</span>
    </div>
    <div class="bg">

        <?
        if (!empty($friends_list)) {
            foreach ($friends_list as $u_friend) {
                ?>
                <div class="friend">
                    <a href="/<?= $u_friend['username'] ?>"><img src="<?= get_user_avatarlink($u_friend['id']) ?>" alt="<?= $u_friend['username'] ?>"></a><br>
                    <a href="/<?= $u_friend['username'] ?>"><?= $u_friend['username'] ?></a>
                </div>
            <?
            }
        }
        ?>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>