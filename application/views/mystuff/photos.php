<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
</div>

<div id="content">
    <div class="page_title">
        <span><?= $title ?></span>
    </div>
    <div class="bg">
        <?
        if (!empty($photos))
            foreach ($photos as $photo) {
                ?>
                <div data-url="<?=  base_url()?><?=$user['username']?>/photo/<?=$photo['photo_id']?>" class="my_photo">
                    <?
                    if (method_exists('Mystuff', 'photos') && $this->user['id'] == $user['id']) {
                        ?>
                        <div class="photo_edit">
                            <a href="/upload/photo_upload/<?=$photo['photo_id']?>/edit"><img src="/images/edit.png"></a>
                            <img class="delete_photo" data-id="<?=$photo['photo_id']?>" src="/images/del.gif">

                        </div>


                        <? } ?>
                    <img style="max-width: 130px; max-height: 130px;"
                         src='/files/users/uploads/<?= $user['id'] . '/' . $photo['photo_id'] . '.' . $photo['image_type'] ?>'>
                </div><?
            }
        ?>
        <br class="clear">
        <? pagination($pages); ?>
    </div>
    <div class="footer"></div>
</div>
