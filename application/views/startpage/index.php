<h1>Popular, Recent Photo</h1>
<?php foreach ($topPhotos as $photo): ?>
<div class="image-cont">
    <div class="author">
        Posted by: <a href="/<?=$photo['username']?>"><?=$photo['username']?></a>
    </div>
    <div class="hearts"><?=$photo['like']?></div>
    <div class="comments"><?=$photo['comments']?></div>
    <div class="clear"></div>
    <div class="image"><img src="/files/users/uploads/<?= $photo['uid'] ?>/<?= $photo['id'] . $photo['rand_num'] ?>.jpg" alt=""></div>
    <div class="descr"><?=$photo['caption']?></div>
    <?php foreach($photo['last3Comments'] as $comment): ?>
    <div class="comment">
        <a class="user-image"><img src="<? get_user_avatarlink($comment['uid']) ?>"></a>
        <div class="comment-cont">
            <a class="user-name" href="/<?=$comment['username']?>"><?=$comment['username']?></a>
            wrote on picture
            "<?=$comment['comment']?>"
        </div>
    </div>
    <div class="clear"></div>
    <?php endforeach; ?>
</div>
<?php endforeach; ?>



<h1>Popular, Recent Dressup</h1>
<?php foreach ($topDressup as $dressup): ?>
<div class="image-cont">
    <div class="author">
        Posted by: <a href="/<?=$dressup['username']?>"><?=$dressup['username']?></a>
    </div>
    <div class="hearts"><?=$dressup['like']?></div>
    <div class="comments"><?=$dressup['comments']?></div>
    <div class="clear"></div>
    <div class="image"><img src="/files/users/dressup/<?= $dressup['id']?>.jpg" alt=""></div>
    <div class="descr"><?=$dressup['dress_comment']?></div>
    <?php foreach($dressup['last3Comments'] as $comment): ?>
    <div class="comment">
        <a class="user-image"><img src="<? get_user_avatarlink($comment['uid']) ?>"></a>
        <div class="comment-cont">
            <a class="user-name" href="/<?=$comment['username']?>"><?=$comment['username']?></a>
            wrote on picture
            "<?=$comment['comment']?>"
        </div>
    </div>
    <div class="clear"></div>
    <?php endforeach; ?>
</div>
<?php endforeach; ?>


