<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div id="content">
        <div class="page_title">
            <span>New message</span>
        </div>
        <div class="bg">
            <form action="" method="POST">
                <span class="err" <? if (empty($err)) echo'style="display: none"' ?>><?= $err ?></span>
                <small>*To send a message to a user that you are not friends with yet, use the Send PM button from the user's profile page.</small>
                <br>
                <select data-placeholder="Select members" style="width:250px;" name="members[]" multiple class="chzn-select">
                    <?
                    if (!empty($members)) {
                        foreach ($members as $member) {
                            ?>
                            <option <?= (!empty($_POST['members']) && in_array($member['id'], $_POST['members'])) ? 'selected="selected"' : '' ?> <?= (!empty($_GET['to']) ? ($_GET['to'] == $member['id'] ? 'selected="selected"' : '') : '') ?> value="<?= $member['id'] ?>"><?= $member['username'] ?></option>
                            <?
                        }
                    }
                    ?>
                </select><br>

                <span>Icon</span>
                <?
                if(!empty($message_icons))
                foreach ($message_icons as $icon) { ?>
                    <label style="margin-left: 10px">
                        <input type="radio" name="icon" <?= (!empty($_POST['icon']) && $_POST['icon'] == $icon['id']) ? 'checked="checked"' : '' ?> value="<?= $icon['filename'] ?>"><img src="/files/chaticons/<?= $icon['filename'] ?>.png">
                    </label>
                <? } ?>
                <br>
                <br>
                <span>Message</span>
                <label>Subject</label><br>
                <input value="<?= @$_POST['subject'] ?>" type="text" size="43" name="subject"><br>
                <label>Message</label><br>
                <textarea class="tinymce" cols="40" rows="5" name="text"><?= @$this->input->post('text') ?></textarea><br>
                <input type="submit" value="Send" name="send">
                <a href="/pms"><input type="button" value="Cancel"></a>
            </form>
            <br class="clear">
        </div>
        <div class="footer"></div>
    </div>

    <script>
        $(function(){
            $(".chzn-select").chosen();
        
            $('input[name=send]').click(function(){
                if(!$('textarea[name=text]').val()){
                    $('.err').text('Please enter a message').show()
                    return false;
                }else if($('select[name^="members"] :selected').length == 0){
                    $('.err').text('Please, select members').show()
                    return false;
                }
            })
        })
    </script>