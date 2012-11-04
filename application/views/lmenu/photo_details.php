<div class="center">
    <br>
    Posted by:<br>
    <div class="avatar100">
        <div class="border"></div>
        <img src="<? get_user_avatarlink($user_info['id']) ?>">
    </div>
    <a href="/<?= $user_info['username'] ?>"><?= $user_info['username'] ?></a>
    <br><br>
    <? if (count($photos) > 1) { ?>
        Other photos by <?= $user_info['username'] ?><br>
        <?
        $current = $this->uri->segment(3);
        foreach ($photos as $val) {
            if ($val['id'] . $val['rand_num'] != $current) {
                ?>
                <a class="other_user_photo" href="/<?= $user_info['username'] ?>/photo/<?= $val['id'] . $val['rand_num'] ?>" style="background-image: url('/files/users/uploads/<?= $user_info['id'] ?>/<?= $val['id'] . $val['rand_num'] ?>.jpg')"></a>
                <?
            }
        }
    }
    ?>
</div>