<section class="lmenu_block user_info">
    <div class="center">
        <?= show_user_avatar($id,'avatars',false, '200', $this->user['ribbon']) ?>
        <br>
        <a href="/editprofile">Edit profile picture</a>
    </div>
    <br>
    <h2 style="text-align: left"><label>Buttons:</label> <?= $buttons ?></h2>
    <h2 style="text-align: left"><label>Jewels:</label> <?= $jewels ?></h2>
    <br>
    <h3><label>new messages</label> <a href="/pms"><?= $messages ?></a></h3>
    <h3><label>new comments</label> <a href="/new/comments"><?= $comments ?></a></h3>
    <h3><label>new hearts</label> <a href="/new/hearts"><?= $hearts ?></a></h3>
    <h3><label>pending friend requests</label> <a href="/myfriends"><?= $pending_friend ?></a></h3>
    <br class="clear">
    <a href="/mystuff" class="center">go to mystuff</a>
    <br class="clear">
</section>