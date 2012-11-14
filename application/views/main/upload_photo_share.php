<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span>Upload and share fashion photos!</span>
    </div>
    <div class="bg">
        <input type="hidden" name="dressup_id" value="<?= $photo['id'] . $photo['rand_num'] ?>">
        <?
        if(strpos($_SERVER['HTTP_REFERER'], 'edit')!==FALSE){
            ?><strong class="center">Success! Your photo has been saved!</strong><br><?
        }
        ?>        
        
        <div class="left" style="width: 260px">
            <a href="/files/users/uploads/<?= $photo['uid'] ?>/<?= $photo['id'] . $photo['rand_num'] ?>_original.<?=$photo['image_type']?>" target="_blank">
            <img src="/files/users/uploads/<?= $photo['uid'] ?>/<?= $photo['id'] . $photo['rand_num'] ?>.<?=$photo['image_type']?>" style="max-width: 100%">
            </a>
            <br>
            <strong class="center">Share this look:</strong>
            <a class="button share_fb" data-mode="upload">Share on Facebook</a>
            <a class="button share_tw"  data-mode="upload">Share on Twitter</a>
            <a class="button share_email" data-mode="upload">Share via Email</a>
        </div>

        <?php if ($photo['image_type'] != 'gif'): ?>

        <div class="right upload_tags" style="background-color: #F9F9F9">
            <strong class="center">The Look</strong><br>
            <table width="100%">
                <?
                if (!empty($tags)) {
                    foreach ($tags as $k => $v) {
                        ?>
                        <tr>
                            <td width="20"><?= $k + 1 ?></td>
                            <td width="60"><?= $v['position'] ?></td>
                            <td><?= $v['title'] ?></td>
                            <td width="140"><? if (!empty($v['brand_id'])) echo 'by <a href="/brands/' . $v['brand_id'] . '">' . $brands[$v['brand_id']]['title'] . '</a>' ?></td>
                        </tr>    
                        <?
                    }
                }
                ?>
            </table>
            <a href="/upload/photo_upload/<?= $photo['id'] . $photo['rand_num'] ?>/edit" class="right">Edit tags</a>
        </div>
        <?php endif; ?>
        <? if (!empty($photo['caption'])) { ?>
            <div class="right upload_tags">
                <?= $photo['caption'] ?>
            </div>
        <? } ?>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>


<div id="share_email_modal" class="hide" title="Share Photo">
    <div class="modal ">
        <div class="first">
            To:<br>
            <textarea name="share_emails"></textarea><br>
            <small class="left">*Separate email addresses with a comma</small><br><br>
            <input type="button" name="share_email_run" class="big_button" value="Send">
            <input type="button" name="close_share_email" value="Close">
        </div>
        <div class="result"></div>
    </div>
</div>

<div id="share_fb_modal" class="hide" title="Share Photo">
    <div class="modal center">
        <div class="first">
            Your comments: <small>(optional)</small><br>
            <textarea name="share_fb_descr" maxlength="150"></textarea><br>
            <input type="button" name="share_fb_run" class="big_button" value="Share">
            <input type="button" name="close_share_fb" value="Close">
        </div>
        <div class="result"></div>
    </div>
</div>

<div id="share_tw_modal" class="hide" title="Share Photo">
    <div class="modal center">
        <div class="first">
            Your comments: <small>(optional)</small><br>
            <textarea name="share_tw_descr" maxlength="100"></textarea><br>
            <input type="button" name="share_tw_run" class="big_button" value="Share">
            <input type="button" name="close_share_tw" value="Close">
        </div>
        <div class="result"></div>
    </div>
</div>