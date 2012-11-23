<div id="columns-cont">

    <?php for ($i = 0; $i <= $max; $i++): ?>
    <?php
    $photo = isset($topPhotos[$i])?$topPhotos[$i]:null;
    $dressup = isset($topDressup[$i])?$topDressup[$i]:null;
    ?>
    <?php if ($photo && file_exists(_getUploadPath($photo))): ?>
    <div class="image-cont">
        <div class="author">
            Posted by: <a href="/<?=$photo['username']?>"><?=$photo['username']?></a>
        </div>
        <div class="hearts <?=!$photo['liked']?'grey':''?> likes" data-id="<?= $photo['id'] . $photo['rand_num'] ?>" data-mode="upload"  data-type="<?=$photo['liked']?'remove':'add'?>"><?=$photo['like']?></div>
        <a href="/<?=$photo['username']?>/photo/<?=$photo['id'] . $photo['rand_num']?>" class="comments"><?=(int)$photo['comments']?></a>
        <div class="clear"></div>
        <div class="image">
            <a href="/<?=$photo['username']?>/photo/<?=$photo['id'] . $photo['rand_num']?>">
                <img src="<?=getSquareUpload($photo)?>"
                     alt="" <?=getSquareUploadSize($photo)?>>
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
    <?php endif; ?>

    <?php if ($dressup && file_exists(_getDressupPath($dressup))): ?>
    <div class="image-cont">
        <div class="author">
            Posted by: <a href="/<?=$dressup['username']?>"><?=$dressup['username']?></a>
        </div>
        <div class="hearts <?=!$dressup['liked']?'grey':''?> likes" data-id="<?= $dressup['id'] ?>" data-mode="dressup" data-type="<?=$dressup['liked']?'remove':'add'?>"><?=$dressup['like']?></div>
        <a href="/<?=$dressup['username']?>/dressup/<?=$dressup['id']?>" class="comments"><?=(int)$dressup['comments']?></a>
        <div class="clear"></div>
        <div class="image">
            <a href="/<?=$dressup['username']?>/dressup/<?=$dressup['id']?>">
                <img src="<?=getSquareDressup($dressup)?>" alt="" <?=getSquareDressupSize($photo)?>>
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
    <?php endif; ?>


    <?php endfor; ?>


</div>
    <div class="clear"></div>
<div id='home-pagination' style="visibility: hidden">
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
                bufferPx     : 0,
                loading: {
                    img: "/images/loading.gif",
                    msgText: "Loading more images",
                    speed: 'slow',
                    finished : function(){
                        $('#columns-cont').masonry( 'reload' );
                        likeInit();
                    }
                }
            });
        });


    </script>


