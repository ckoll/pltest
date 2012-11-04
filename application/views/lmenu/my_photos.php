<section class="lmenu_block user_photos">
    
    <ul class="columns2">
        <? for($i=0; $i<count($my_photos); $i++){
           ?>
            <li>
                <a href="/<?=$my_photos[$i]['username']?>/photo/<?=$my_photos[$i]['photo_id']?>" class="image" style="background-image: url('/files/users/uploads/<?=$my_photos[$i]['uid']?>/<?=$my_photos[$i]['photo_id']?>.jpg')"></a>
            </li>
           <?
       }
       ?>
    </ul>
    <?
    $link = ($user['id'] == $this->user['id'])? 'mystuff/most_hearted_photos' : $user['username'].'/stuff/most_hearted_photos' ;
    ?>
    <a href="/<?=$link?>" class="right">See all (<?= $my_photos_total ?>)</a>
    <br class="clear">
</section>