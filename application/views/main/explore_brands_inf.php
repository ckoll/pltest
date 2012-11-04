<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content" class="brand_info">

    <div class="page_title">
        <span>Brands</span>
    </div>
    <div class="bg">

        <div class="center">
            <p><?= $brand['title'] ?></p><br>
            <? 
            $img = (is_file(FILES . 'brands/' . $brand['imagename'])) ? urlencode($brand['imagename']) : 'default.png'; ?>
            <img src="/files/brands/<?= $img ?>"><br class="clear">
            <form method="post"><input type="submit" name="<?= (!empty($fav_brand)) ? 'del_from_favorite' : 'add_to_favorite' ?>" value="<?= (!empty($fav_brand)) ? '- Delete from favorite brands' : '+ Add to favorite brands' ?>"></form><br>
            <p class="brand_desc"><?= $brand['description'] ?></p>

            <br>
            <p>Photos containing this brand</p>
            <div class="brands_photo_containing">
                
                <?
                if (!empty($images)) {
                    ?><div class="center"><?
                    foreach ($images as $val) {
                        ?>
                        <a class="photo" href="/brands/<?=str_replace(' ','-',strtolower($brand['title']))?>/<?= $val['photo_id'] ?>?page=0" style="background-image: url('/files/users/uploads/<?= $val['uid'] ?>/<?= $val['photo_id'] ?>.jpg');"></a>    
                        <?
                    }
                    ?>
                    </div>
                    <br class="clear">
                    <div><a href="/brands/<?= str_replace(' ','-',strtolower($brand['title'])) ?>/all">see more with the brands browser</a></div>
                <? } else { ?>
                    <span>NO IMAGES</span> 
                <? } ?>
            </div>
        </div>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>