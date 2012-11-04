<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div class="page_title">
        <span>Latest dressups</span>
    </div>
    <div class="bg">
        <div style="margin-left: 50px">
        <?
        if (!empty($dressups)) {
            foreach ($dressups as $val) {
                ?>
                <div class="photo_tumb">
                    <a href="/<?=$val['username']?>/dressup/<?=$val['id']?>" style="background-image: url('/files/users/dressup/<?=$val['id']?>.jpg'); background-position: left center"></a><br>
                    <span class="likes" data-id="<?=$val['id']?>" data-mode="dressup"><?= $val['like'] ?></span>
                    <span class="comments"><?= $val['comment'] ?></span>
                </div>
                <?
            }
        } else {
            ?><em>*No dressups</em><?
    }
        ?>
        </div>
    <br class="clear">
    <?pagination($pages)?>
    </div>
    <div class="footer"></div>
</div>