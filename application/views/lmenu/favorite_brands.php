<section class="lmenu_block favorite_brands">
    <ul class="columns4">
        <?
        if (!empty($favorite_brands)) {
            foreach ($favorite_brands as $val) {
                $img=(is_file(FILES.'brands/'.$val['imagename']))? urlencode($val['imagename']) : 'default.png' ;
                ?>
                <li>
                    <a class="image" href="/brands/<?= str_replace(' ', '-', strtolower($val['title'])) ?>" style="background-image:url('/files/brands/<?=$img?>');"></a>
                </li>   
                <?
            }
        }
        ?>
    </ul>
    <?
    $link = ($user['id'] == $this->user['id'])? 'mystuff/brands' : $user['username'].'/stuff/brands' ;
    ?>
    <a href="/<?=$link?>" class="right">See all (<?=$favorite_brands_total ?>)</a>
    <br class="clear">
</section>