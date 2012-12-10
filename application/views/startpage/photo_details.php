<div id="content">
    <div class="page_title">
        <span>
            Photo details
        </span>
    </div>
    <br>
    <div class="bg">
        <div class="photo_cont">
            <div class="details-top-buttons">
            <?php
            $shareText = urlencode("@perfectlookorg " . $photo['caption'] . ' '.base_url().'files/users/uploads/'.$photo['uid'].'/'.$photo['id'] . $photo['rand_num'].'.'.$photo['image_type']);

                ?>
            <span class="photo_details_buttons"  style="margin-right: 20px">
            <a class="twitt_button cool-button"
               onclick="twitterPopup('<?=$shareText?>', '<?=urlencode(current_url())?>')">twitter</a>
            </span>

            <span class="photo_details_buttons">
               <a class="cool-button fb-button" onclick="facebookPopup('<?=urlencode(current_url())?>')">facebook</a>
            </span>
            </div>
            <div class="clear"></div>
            <a href="/files/users/uploads/<?= $photo['uid'] ?>/<?= $photo['id'] . $photo['rand_num'] ?>_original.<?=$photo['image_type']?>"
               target="_blank">
                    <img style="max-height: 500px; max-width: 500px;"
                         src="/files/users/uploads/<?= $photo['uid'] ?>/<?= $photo['id'] . $photo['rand_num'] ?>.<?=$photo['image_type']?>">

            </a><br>
            <br>

            <div style="margin-left: 10px" class=" photo_details_buttons hearts <?=!$photo['liked'] ? 'grey' : ''?> likes"
                 data-id="<?= $photo['id'] . $photo['rand_num'] ?>" data-mode="upload"
                 data-type="<?=$photo['liked'] ? 'remove' : 'add'?>"><?=$photo['like']?></div>

            <span class="comments dressup_details photo_details_buttons"><?= count($comments) ?></span>

            <?php if (!empty($admin) || $photo['uid'] == $this->user['id']): ?>
            <a style="float: right; margin-right: 20px" href="/upload/photo_upload/<?= $photo['id'] . $photo['rand_num'] ?>/edit"
               class="photo_details_buttons"><img
                    src="/images/edit.png"></a>
            <?php endif; ?>
        </div>
        <br class="clear">
        <?
        if (!empty($photo['caption'])) {
            echo $photo['caption'] . '<br>';
        }
        ?>

        <br>

        <br class="clear">
    </div>
    <div class="footer"></div>
</div>


<script type="text/javascript">


</script>