<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span>Reply</span>
    </div>
    <div class="bg">
        <span class="err" style="display: none"></span>
        <em><?= ($reply_mess['from'] == $this->user['id']) ? 'To:' : 'From:'; ?></em>
        <? show_username_link($reply_mess['username']);
        ?><br>
        <em>Subject:</em><?= $reply_mess['subject'] ?><br>
        <div class="message_text">
            <?= $reply_mess['text'] ?></div>
        <form action="" method="POST">
            <br>
            <h2>Reply</h2>
            <span>Icon</span>
            <? 
            if(!empty($message_icons))
            foreach ($message_icons as $icon) { ?>
                <label style="margin-left: 10px">
                    <input type="radio" name="icon" <?= (!empty($_POST['icon']) && $_POST['icon'] == $icon['id']) ? 'checked="checked"' : '' ?> value="<?= $icon['filename'] ?>"><img src="/files/chaticons/<?= $icon['filename'] ?>.png">
                </label>
            <? } ?><br>
            Message:<br>
            <textarea name="text" class="tinymce"></textarea><br>
            <input type="hidden" name="members[]" value="<?= $reply_mess['uid'] ?>">
            <input type="hidden" name="subject" value="<?= $reply_mess['subject'] ?>">
            <input type="submit" value="Send" name="reply">
            <a href="/pms"><input type="button" value="Cancel"></a>
        </form>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>

<script>
    $(function(){
        $('input[name=reply]').click(function(){
            if(!$('textarea[name=text]').val()){
                $('.err').text('Please enter a message').show()
                return false;
            }
        })
    })
</script>