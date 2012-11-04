<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div class="page_title">
        <span>Brands</span>
    </div>
    <div class="bg">
        <br>
        <p class="left">Browsing items from brand: <?= $brand['title'] ?></p>
        <select name="selectAnotherBrand">
            <option selected="selected">Select Another Brand</option>
            <?
            if (!empty($brands))
                foreach ($brands as $val) {
                    ?>
                    <option value="<?= str_replace(' ', '-', strtolower($val['title'])) ?>"><?= $val['title'] ?></option>
                <? } ?>
        </select>
        <br class="clear">
        <a href="/brands/<?= str_replace(' ', '-', strtolower($brand['title'])) ?>/all?page=<?= (!empty($_GET['page'])) ? $_GET['page'] : 0; ?>">Return to page <?= (!empty($_GET['page'])) ? $_GET['page'] + 1 : 1; ?></a>
        <br><br>
        <div class="center brand-browser-img-lr">
            <? if (!empty($images[0])) { ?> 
                <a href="/brands/<?= str_replace(' ', '-', strtolower($brand['title'])) ?>/<?= ($images[0]['photo_id']) ?>?page=<?= (!empty($_GET['page'])) ? $_GET['page'] : 0; ?>" class="button">Prev</a><br>
                <span style="background-image: url('/files/users/uploads/<?= $images[0]['uid'] ?>/<?= ($images[0]['photo_id']) ?>.jpg');"></span>
                <?
            } else {
                echo "&nbsp;";
            }
            ?>
        </div>
        <div  class="center" id="brand-browser-img-center">
            <a href="/<?= $posted_by['username'] ?>/photo/<?= ($images[1]['photo_id']) ?>"><img src="/files/users/uploads/<?= $images[1]['uid'] ?>/<?= ($images[1]['photo_id']) ?>.jpg" width="300" ></a>

        </div>
        <div class="center brand-browser-img-lr">
            <? if (!empty($images[2])) { ?> 
                <a href="/brands/<?= str_replace(' ', '-', strtolower($brand['title'])) ?>/<?= ($images[2]['photo_id']) ?>?page=<?= (!empty($_GET['page'])) ? $_GET['page'] : 0; ?>" class="button">Next</a><br>
                <span style="background-image: url('/files/users/uploads/<?= $images[2]['uid'] ?>/<?= ($images[2]['photo_id']) ?>.jpg');"></span>
            <? } ?>
        </div>
        <br class="clear">
        <div>
            <span>Photo details</span><br>
            <div class="center left" style="width: 150px;">
                <span>posted by:</span><br>
                <div class="avatar100"><div class="border"></div><img src="<?= get_user_avatarlink($posted_by['id']) ?>"></div>
                <a href="/<?= $posted_by['username'] ?>"><?= $posted_by['username'] ?></a><br>
                <span>on <?= $images[1]['date'] ?></span>
            </div>
            <div class="left">
                <br>
                <div class="brands2">
                    <?= (!empty($images[1]['caption'])) ? $images[1]['caption'] : "Empty caption" ?>
                </div>
                <p>What's in the photo:</p>
                <ul class="brands3">
                    <? if (!empty($tags)) foreach ($tags as $tag) { ?>
                            <li><?= $tag['position'] ?>: <?= $tag['title'] ?> <a href="/brands/<?= str_replace(' ', '-', strtolower($tag['brand_title'])) ?>"><?= $tag['brand_title'] ?></a></li>
                        <? } ?>
                </ul>
            </div>
            <br class="clear">
        </div>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>