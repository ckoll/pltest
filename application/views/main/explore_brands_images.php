<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div class="page_title">
        <span>Brands</span>
    </div>
    <div class="bg explore-brand-images">
        <div>
            <br>
            <p style="float:left;">Showing photos with items from the brand: <?= $brand['title'] ?></p>
            <select name="selectAnotherBrand" style="float:right; margin-right: 30px; width:190px">
                <option selected="selected">Select Another Brand</option>
                <?
                if(!empty($brands))
                foreach ($brands as $val) { ?>
                    <option value="<?=str_replace(' ','-',strtolower($val['title']))   ?>"><?= $val['title'] ?></option>
                <? } ?>
            </select>
            <br class="clear">
            <? pagination($pages);  ?>
            <?
            for($i=0; $i<count($images); $i++){
                ?>
                <div class="center brand-image"><a href="/brands/<?= str_replace(' ','-',  strtolower($brand['title'])) ?>/<?= $images[$i]['photo_id'] ?>?page=<?= (!empty($_GET['page'])) ? $_GET['page'] : 0; ?>" style="background-image: url('<?= '/files/users/uploads/' . $images[$i]['uid'] . '/' . $images[$i]['photo_id'] ?>.jpg');"></a><?= (!empty($images[$i]['caption'])) ? '<span>'. $images[$i]['caption'] . '</span><br>' : '' ?><a href='/<?= $images[$i]['username'] ?>'><?= $images[$i]['username'] ?></a></div>
                <?
                if($i!=0 && ($i%5) == 0) {?><br class="clear"><?}
            }
            ?>
        </div>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>
