<?
$user_n = $this->uri->segment(1);
?>
<section class="lmenu_block my_recent_photos">
    <ul class="columns2">
        <?
        if (!empty($recent_photos)) {
            foreach ($recent_photos as $val) {
                ?>
                <li>
                    <a class="image" href="/<?=$user_n?>/photo/<?=$val['id'].$val['rand_num']?>" style="background-image:url('/files/users/uploads/<?=$val['uid']?>/<?=$val['id'].$val['rand_num']?>.<?=$val['type']?>');"></a>
                    <p>
                        <?=intval($val['like'])?> hearts<br>
                        <?=intval($val['comments'])?> comments
                    </p>
                </li> 
                <?
            }
        }
        ?>
    </ul>
    <?
    $link = ($user['id'] == $this->user['id'])? 'mystuff/photos' : $user['username'].'/stuff/photos' ;
    ?>
    <a href="/<?=$link?>" class="right">See all (<?= $recent_photos_total ?>)</a>
    <br class="clear">
</section>