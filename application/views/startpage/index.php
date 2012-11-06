<h1>Popular, Recent Photo and Dressup</h1>


<?php for ($j = 1; $j < 3; $j++): ?>
<?php
    $max = $j * 5;
?>
<div class="column50">
    <?php for ($i = $max-5; $i < $max; $i++): ?>
    <?php
    $photo = $topPhotos[$i];
    $dressup = $topDressup[$i];
    ?>
    <div class="image-cont">
        <div class="author">
            Posted by: <a href="/<?=$photo['username']?>"><?=$photo['username']?></a>
        </div>
        <div class="hearts likes" data-id="<?= $photo['id'] . $photo['rand_num'] ?>" data-mode="upload"><?=$photo['like']?></div>
        <a href="/<?=$photo['username']?>/photo/<?=$photo['id'] . $photo['rand_num']?>" class="comments"><?=$photo['comments']?></a>
        <div class="clear"></div>
        <div class="image">
            <a href="/<?=$photo['username']?>/photo/<?=$photo['id'] . $photo['rand_num']?>">
                <img src="<?=getSquareUpload($photo)?>"
                     alt="">
            </a>
        </div>
        <div class="descr"><?=$photo['caption']?></div>
        <?php foreach ($photo['last3Comments'] as $comment): ?>
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

    <div class="image-cont">
        <div class="author">
            Posted by: <a href="/<?=$dressup['username']?>"><?=$dressup['username']?></a>
        </div>
        <div class="hearts likes" data-id="<?= $dressup['id'] ?>" data-mode="dressup"><?=$dressup['like']?></div>
        <a href="/<?=$dressup['username']?>/dressup/<?=$dressup['id']?>" class="comments"><?=$dressup['comments']?></a>
        <div class="clear"></div>
        <div class="image">
            <a href="/<?=$dressup['username']?>/dressup/<?=$dressup['id']?>">
                <img src="<?=getSquareDressup($dressup)?>" alt="">
            </a>
        </div>
        <div class="descr"><?=$dressup['dress_comment']?></div>
        <?php foreach ($dressup['last3Comments'] as $comment): ?>
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



    <?php endfor; ?>
</div>

<?php endfor; ?>

