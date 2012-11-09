<div id="columns-cont">

<?php
    $max = count($topPhotos);
?>
    <?php for ($i = 0; $i <= $max; $i++): ?>
    <?php
    $photo = $topPhotos[$i];
    $dressup = $topDressup[$i];
    ?>
    <div class="image-cont">
        <textarea style="display: none">
        <?php print_r($photo); ?>
        </textarea>
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
    <?php if (isset($photo['last3Comments'])) : ?>
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
    <?php endif; ?>
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
        <?php if (isset($dressup['last3Comments'])) : ?>
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
        <?php endif; ?>
    </div>



    <?php endfor; ?>


</div>
<div id='home-pagination' style="display: none">
    <a href="/index.php?page=2">Last ›</a>
</div>


    <script type="text/javascript">
        $(document).ready(function(){


            $('#columns-cont').masonry({
                itemSelector: '.image-cont'
            });


            $('#columns-cont').infinitescroll({
                navSelector  : "#home-pagination",
                nextSelector : "#home-pagination a:last",
                itemSelector : "#columns-cont div.image-cont",
                bufferPx     : 100,
                loading: {
                    img: "/images/loading.gif",
                    msgText: "Loading more images",
                    speed: 'slow',
                    finished : function(){
                        $('#columns-cont').masonry( 'reload' );
                    }
                }
            });
        });


    </script>


