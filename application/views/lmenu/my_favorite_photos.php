<section class="lmenu_block my_favorite_photos">
    <ul class="columns2">
        <? for($i=0; $i<count($my_favorite_photos); $i++){
           ?>
            <li>
                <a href="/<?=$my_favorite_photos[$i]['username']?>/photo/<?=$my_favorite_photos[$i]['photo_id']?>" class="image" style="background-image: url('/files/users/uploads/<?=$my_favorite_photos[$i]['uid']?>/<?=$my_favorite_photos[$i]['photo_id']?>.jpg')"></a>
            </li>
           <?
       }
       ?>
    </ul>
    <?
    $link = ($user['id'] == $this->user['id'])? 'mystuff/favorite_photos' : $user['username'].'/stuff/favorite_photos' ;
    ?>
    <a href="/<?=$link?>" class="right">See all (<?= $my_favorite_photos_total ?>)</a>
    <br class="clear">
</section>