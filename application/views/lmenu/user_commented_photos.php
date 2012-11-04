<section class="lmenu_block user_commented_photos">
    <ul class="columns2">
        <? for($i=0; $i<count($commented_photos); $i++){
           ?>
            <li>
                <a href="/<?=$commented_photos[$i]['username']?>/photo/<?=$commented_photos[$i]['photo_id']?>"><img src="/files/users/uploads/<?=$commented_photos[$i]['uid']?>/<?=$commented_photos[$i]['photo_id']?>.jpg"></a>
            </li>
           <?
       }
       ?>
    </ul>
    <?
    $link = ($user['id'] == $this->user['id'])? 'mystuff/most_commented_photos' : $user['username'].'/stuff/most_commented_photos' ;
    ?>
    <a href="/<?=$link?>" class="right">See all (<?= $commented_photos_total ?>)</a>
    <br class="clear">
</section>