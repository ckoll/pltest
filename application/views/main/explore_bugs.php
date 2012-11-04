<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div class="page_title">
        <span>Bugs Reporting</span>
    </div>
    <div class="bg">

        <strong>Bugs</strong><br>
        <div class="comments_block">
            <form method="post" enctype="multipart/form-data">
                <p style="margin-left: 8px">Leave a bug:</p>
                <textarea name="text" style="width: 430px"></textarea>
                <br>
                Attach a file:<input type="file" name="attach">
                <div class="center"><input type="submit" name="send" value="send"></div>
            </form>
            <?
            pagination($pages);
            if (!empty($bugs)) {
                echo "<div>";
                foreach ($bugs as $bug) {
                    ?>
                    <div class="comment">
                        <div class="center userinfo">
                            <div class="avatar100"><div class="border"></div><img src="<?= get_user_avatarlink($bug['uid']) ?>"></div>
                            <a href="/<?= $bug['username'] ?>"><?= $bug['username'] ?></a>
                        </div>
                        <div class="text">
                            <span class="right">
                                <? if ($bug['uid'] == $this->user['id'] || !empty($admin)) { ?>
                                    <a class="bug_modal_open" data-id="<?= $bug['id'] ?>">Edit</a>&nbsp;&nbsp; <a href="/explore/bugwall/?rem=<?= $bug['id'] ?>">Remove</a>&nbsp;&nbsp;
                                <? } ?>
                                <?
                                if (!empty($admin)) {
                                    ?>
                                    <select name="bug_status" data-id="<?= $bug['id'] ?>">
                                        <option value="not_solved">Not solved</option>
                                        <option value="solved" <? if ($bug['status'] == 'solved') echo'selected="selected"' ?>>Solved</option>
                                    </select>
                                    <?
                                }else {
                                    if ($bug['status'] == 'solved') {
                                        ?><b>Solved</b><?
                    } else {
                                        ?><b>Not solved</b><?
                    }
                }
                                ?>
                            </span><br>
                            <span><?= time_from($bug['date']) ?><br></span>
                            <span class="mess_text"><?= $bug['text'] ?><br></span>
                            <?
                            if(!empty($bug['attach'])){
                                ?><a href="/files/bugs/<?=$bug['id']?>-<?=$bug['attach']?>" target="_blank">attached file</a><?
                            }
                            ?>
                        </div>
                        <br class="clear">
                    </div>
                    <?
                }
                echo "</div>";
            }
            ?>
        </div>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>
<div id="edit_bug_modal" class="hide" title="Edit bug message">
    <div class="modal">
        Message text:<br>
        <textarea name="mess_text" style="width: 450px; height: 200px;"></textarea>
        <br>
        <input type="button" name="save_bug_text" value="Save" class="left">
        <input type="hidden" name="hid_bug_id" value="">
    </div>
</div>


<script>
    $(function(){
        $('select[name=bug_status]').change(function(){
            var val = $(this).val()
            var id = $(this).data('id')
            $.post('/explore/ajax',{
                'func': 'bug_status',
                'val': val,
                'id': id
            },function(){
                alert('Status saved')
            })
        })
    
        $('.bug_modal_open').click(function(){
            $('#edit_bug_modal').dialog('open')
            texts = $(this).parent().next().next().next().text()
            $('textarea[name=mess_text]').val(texts)
            $('input[name=hid_bug_id]').val($(this).data('id'))
        })
    
        $('#edit_bug_modal').dialog({
            width: 500,
            height: 340,
            autoOpen: false,
            modal: true,
            resizable: false
        })
        $('.close_del_modal').live('click',function(){
            $('#edit_bug_modal').dialog('close')
        })
        $('input[name=save_bug_text]').click(function(){
            $.post('/explore/ajax',{
                'func': 'bug_text_save',
                'val': $('textarea[name=mess_text]').val(),
                'id': $('input[name=hid_bug_id]').val()
            },function(){
                document.location = document.URL
            })
        })
    
    })
</script>