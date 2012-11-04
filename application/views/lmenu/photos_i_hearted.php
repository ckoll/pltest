<section class="lmenu_block photos_i_hearted">
   <ul class="columns2">
       <? for($i=0; $i<count($photos_hearted); $i++){
           ?>
            <li>
                <a href="/<?=$photos_hearted[$i]['username']?>/photo/<?=$photos_hearted[$i]['photo_id']?>" class="image" style="background-image: url('/files/users/uploads/<?=$photos_hearted[$i]['uid']?>/<?=$photos_hearted[$i]['photo_id']?>.jpg')"></a>
            </li>
           <?
       }
       ?>
    </ul>
    <?
    $link = ($user['id'] == $this->user['id'])? 'mystuff/recent_photos_i_likes' : $user['username'].'/stuff/recent_photos_i_likes' ;
    ?>
    <a href="<?=$link?>" class="right">See all (<?=$photos_hearted_total ?>)</a>
    <br class="clear">
</section>