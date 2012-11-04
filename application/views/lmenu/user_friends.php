<section class="lmenu_block user_friends">
    <ul class="columns2">
        <?
        if (!empty($user_friends)) {
            foreach ($user_friends as $u_friend) {
                ?>
                <li>
                    <a class="avatar100" href="/<?=$u_friend['username']?>">
                        <div class="border"></div>
                        <img src="<?= get_user_avatarlink($u_friend['id']) ?>">
                    </a>
                </li>
                <?
            }
        }
        ?>
    </ul>
    <a href="/<?= $user_info['username'] ?>/friends" class="right">view all (<?= $user_friends_count ?>)</a>
    <br class="clear">
</section>