<div id="content">
    <div class="page_title">
        <span>Dressup Details</span>
    </div>
    <br>
    <div class="bg">
        <div class="photo_cont">
            <div class="details-top-buttons">
                <?php
                $shareText = urlencode("@perfectlookorg " . $item['name'] . ' ' . $item['dress_comment'] . ' ' . base_url() . 'files/users/dressup/'.$item['id'].'.jpg');
                ?>
                <span class="photo_details_buttons" style="margin-right: 20px">
                    <a class="twitt_button cool-button"
                       onclick="twitterPopup('<?=$shareText?>', '<?=urlencode(current_url())?>')">twitter</a>
                </span>

                <span class="photo_details_buttons">
                   <a class="cool-button fb-button"
                      onclick="facebookPopup('<?=urlencode(current_url())?>')">facebook</a>
                </span>
            </div>
            <div class="clear"></div>
            <br>
            <a href="/files/users/dressup-HD/<?= $item['id'] ?>.jpg" target="_blank">
                <img src="/files/users/dressup/<?= $item['id'] ?>.jpg">
            </a>
            <br>
            <br>
            <span style="margin-left: 10px"
                  class="hearts <?=!$item['liked'] ? 'grey' : ''?> likes dressup_details photo_details_buttons"
                  data-id="<?= $item['id'] ?>" data-mode="dressup"
                  data-type="<?=$item['liked'] ? 'remove' : 'add'?>"><?=$item['like']?></span>
            <span class="comments dressup_details photo_details_buttons"><?= count($comments) ?></span>
            <?php if (!empty($admin) || $item['uid'] == $this->user['id']): ?>
            <a style="float: right; margin-right: 20px" href="/dressup/dress/<?= $item['id'] ?>"
               class="photo_details_buttons"><img
                    src="/images/edit.png"></a>
            <?php endif; ?>
            <?php if (!empty($admin)):?>
            <a style="float: right; margin-right: 20px" href="/dressup/regenerate_images/<?= $item['id'] ?>"
               class="photo_details_buttons"><img
                    src="/images/error.png"></a>
            <?php endif; ?>

        </div>
        <br class="clear">
        <br>
        Username: <a href="/<?= $item['username'] ?>"><?= $item['username'] ?></a><br>
        <?
        if (!empty($item['name'])) {
            echo 'Dressup: ' . $item['name'] . '<br>';
        }
        if (!empty($item['dress_comment'])) {
            echo 'Dressup description: ' . $item['dress_comment'];
        }
        ?>
    </div>
    <div class="footer"></div>
</div>

