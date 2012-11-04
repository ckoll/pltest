<?
$friends_sort = $this->session->userdata('friends_sort');
?>
<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span>Your Friends</span>
    </div>
    <div class="bg">

        <? if ($incoming_friends_count) { ?>

            <h2>Incoming users</h2>
            <div id="incoming_friends">
                <? 
                    if(!empty($incoming_friends))
                    foreach ($incoming_friends as $inc_friend) { ?>
                    <div class="friend">
                        <a href="/<?= $inc_friend['username'] ?>" class="avatar100">
                            <div class="border"></div>
                            <img src="<?= get_user_avatarlink($inc_friend['uid']) ?>"> 
                        </a>
                        <?= $inc_friend['username'] ?><br>
                        <input type="button" data-id="<?= $inc_friend['uid'] ?>" id="user_add_confirm" value="Confirm user">
                    </div>
                <? } ?>
            </div><br class="clear"><hr>


        <? } ?>

        <div class="right">
            sort by:
            <select name="sort_friends">
                <option value="">-- No selection --</option>
                <option value="adding" <? if ($friends_sort == 'user_friends.adding') echo 'selected="selected"' ?>>Recently added</option>
                <option value="last_action" <? if ($friends_sort == 'users.last_action') echo 'selected="selected"' ?>>Last logged in</option>
            </select>
        </div>
        <br class="clear">
        <div id="incoming_friends">
            <?
            if (!empty($your_friends)) {
                foreach ($your_friends as $your_friend) {
                    ?>
                    <div class="friend">
                        <a href="/<?= $your_friend['username'] ?>" class="avatar100">
                            <div class="border"></div>
                            <img src="<?= get_user_avatarlink($your_friend['id']) ?>">
                        </a>
                        <?= $your_friend['username'] ?><br>
                        <input data-id="<?= $your_friend['id'] ?>" type="button" class="del_friend" value="delete friend">
                        <form action="/send_free_gifts" style="margin: 0">
                            <input type="hidden" name="user" value="<?= $your_friend['id'] ?>">
                            <input type="button" onclick="this.parentNode.submit()" value="Send gifts">
                        </form>
                    </div>
                <? } ?>
                <br class="clear">
                <div style="text-align">
                    <? if ($your_friends_count > 12) { ?>
                        <span>Page:&nbsp;</span>
                        <?
                        for ($i = 0; $i < ($your_friends_count / 12); $i++) {
                            if ($i == ($page - 1)) {
                                echo $page;
                            } else {
                                ?>
                                <a href="/myfriends?page=<?= ($i + 1) ?>"><?= ($i + 1) ?></a>&nbsp;&nbsp;
                                <?
                            }
                        }
                    }
                    ?>
                </div>

                <?
            } else {
                ?><em>You have not added any friends.</em><br>
                Find and invite some friends at
                <a href="http://<?= $_SERVER['SERVER_NAME'] ?>/find_friends">http://<?= $_SERVER['SERVER_NAME'] ?>/find_friends</a>
            <? }
            ?>
        </div>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>