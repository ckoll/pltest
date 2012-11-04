<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span><?= ucfirst($folder) ?></span>
    </div>
    <div class="bg">

        <? if ($messages_all) { ?>
            <? if ($folder == 'inbox') { ?>&nbsp;&nbsp;<span><?= $messages_new ?> new message(s)</span>,&nbsp;<? } ?><span> total messages: <?= $messages_all ?></span>
            <form action="" name="messages" method="POST">
                <table class="messages">
                    <tr>
                        <td width="40">New? </td>
                        <td>Subject</td>
                        <td width="120"><? if ($folder != 'sent') echo 'Sender'; else echo 'Addressee'; ?></td>
                        <td width="160">Date</td>
                        <td width="20">Select</td>
                    </tr>
                    <? foreach ($messages as $mess) { ?>
                        <tr>
                            <td><a href="/pms/<?= $mess['id'] ?>"><img src="/files/chaticons/<?= (!$mess['view']) ? $mess['img'] : $mess['img'] . '-black' ?>.png"></a></td>
                            <td><a href="/pms/<?= $mess['id'] ?>"><?= $mess['subject'] ?></a></td>
                            <td><?= show_username_link($mess['username']) ?></td>
                            <td><?= date_format(new DateTime($mess['date']), 'Y-m-d h:i:s A') ?></td>
                            <td style="text-align: center"><input type="checkbox" name="select_mess[]" value="<?= $mess['id'] ?>"></td>
                        </tr>
                    <? } ?>
                </table>

                <? if ($folder != 'deleted') { ?>
                    <input name="mark_sel" type="button" value=">>" class="right" style="margin-top: 10px">
                    <select name="mark" class="right" style="margin-top: 10px">
                        <option value="">-- for selected --</option>
                        <option value="unread">mark as unread</option>
                        <option value="read">mark as read</option>
                        <option value="delete">delete</option>
                    </select>
                <? } else { ?>
                    <input type="submit" class="right" value="Undelete" name="undelete">
                    <input type="submit" class="right <?= (!$messages_all) ? 'hide' : '' ?>"  value="Empty trash" name="empty_trash">
                <? } ?>

                <? if ($messages_all / 20 > 1) { ?>
                    <span>Page:&nbsp;</span>
                    <?
                    for ($i = 0; $i < ($messages_all / 20); $i++) {
                        if ($i == ($page - 1)) {
                            echo $page;
                        } else {
                            ?>
                            <a href="/pms/<?= $folder . '?page=' . ($i + 1) ?>"><?= ($i + 1) ?></a>&nbsp;&nbsp;
                            <?
                        }
                    }
                }
                ?>

            </form>
        <? } else { ?>
            <h3>No Messages</h3>
        <? } ?>
            <br class="clear">
    </div>
    <div class="footer"></div>
</div>