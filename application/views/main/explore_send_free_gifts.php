<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span>Send Free Gifts</span>
    </div>
    <div class="bg">

        <? if (!$this->input->get('free_gift')) { ?>
            <h2>Gifts are free for you to send, and you can send one gift to each friend per day.</h2>
            <form method="GET" id="free_gift_form">
                <?
                if (!empty($_GET['user'])) {
                    ?><input type="hidden" name="user" value="<?= $_GET['user'] ?>"><?
        }
                ?>
                <b>Select gift:</b><input type="submit" value="Next page"><br class="clear">
                
                    <? 
                        if(!empty($gifts))
                        foreach ($gifts as $gift) { ?> 
                        <div class="single">
                            <label>
                                <img src="/<?= APPPATH ?>files/items/<?= $gift['directory'].'/'.(($gift['profileimage_dir']=='[default]')?'profilepics':$gift['profileimage_dir']).'/'.$gift['profileimage'] ?>" alt="<?= $gift['item_name'] ?>"><br>
                                <p class="title" style="overflow: hidden; height: 20px">
                                    <?= $gift['item_name'] ?>
                                </p>
                                <input type="radio" name="free_gift" value="<?= $gift['gift_id'] ?>" >
                            </label>
                        </div>
                    <? } ?>


                <br class="clear">
                <? pagination($pages)?>
            </form>
            

        <? } else { ?>

            <h2>Choose the friends you want to send this to</h2>
            <form method="POST" id="free_gift_form">
                <input type="hidden" name="free_gift" value="<?= $this->input->get('free_gift') ?>">
                <? if (!empty($gift_to_friends)) { ?>
                    <b>Select friends:</b><input type="submit" value="Send">
                    <br class="clear">
                <? } ?>
                    <?
                    if (!empty($gift_to_friends)) {
                        foreach ($gift_to_friends as $friend) {
                            ?> 
                            <div class="single_users">
                                <label>
                                    <a href="/<?=$friend['username']?>" target="_blank" class="avatar100">
                                        <div class="border"></div>
                                        <img src="<?= get_user_avatarlink($friend['id']) ?>" alt="<?= $friend['username'] ?>">
                                    </a>
                                    <span class="single-name <? if (in_array($friend['id'], $sended_today)) echo'disabled' ?>"><?= $friend['username'] ?></span><br>
                                    <input type="checkbox" name="free_gift_user[]" <? if ($friend['id'] == @$_GET['user']) echo 'checked="checked"' ?> value="<?= $friend['id'] ?>" <? if (in_array($friend['id'], $sended_today)) echo'disabled="disabled"' ?> >
                                </label>
                            </div>
                        <?
                        }
                    }else {
                        ?>
                        <strong>You have no friends. <a href="/invite_friends">Add friends</a> to send free gifts.</strong>
                        <?
                    }
                    ?>
                    <br class="clear">
                <? pagination($pages)?>
            </form>
<? } ?>
            <br class="clear">
    </div>
    <div class="footer"></div>
</div>

<div id="gifts_sended_modal" class="hide" title="Sending Gifts">
    <div class="modal">
        <div id="content_sended"></div>
        <br class="clear">
        <div id="content_no_sended"></div> 
    </div>
</div>