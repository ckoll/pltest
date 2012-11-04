<section class="lmenu_block">
    <b>Viewing profile page of: <?= $user_info['username'] ?></b><br><br>
    <div class="center">
        <?= show_user_avatar($user_info['id'],'avatars',false, '200') ?>
        <?= $user_info['username'] ?>
    </div><br>
    <span id="status" class="center"><?= $user_info['bio'] ?></span>
    <br>
    <a href="/pms/new?to=<?= $user_info['id'] ?>" class="button">send pm</a><br>

    <input data-id="<?= $user_info['id'] ?>" type="button" class="button"  
    <?= ($friend_status == 'wait') ? 'disabled="disabled" id="add_friend" value="request sent"' : '' ?>
    <?= ($friend_status == 'none') ? 'id="add_friend" value="add friend"' : '' ?> 
    <?= ($friend_status == 'friend') ? 'disabled="disabled" id="add_friend" value="already added"' : '' ?>
           <?= ($friend_status == 'confirm') ? 'id="user_add_confirm" value="confirm add friend"' : '' ?>>
    <br class="clear">
    <a href="/<?=$user_info['username']?>/stuff" class="center">go to stuff</a>
    <br class="clear">
</section>