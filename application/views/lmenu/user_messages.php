<? $active = $this->uri->segment(2); ?>
<section class="lmenu_block">
    <a href="/pms" <? if ($active == '') echo'class="selected"' ?>>Inbox (<?= $messages_new ?> new)</a><br>
    <a href="/pms/sent" <? if ($active == 'sent') echo'class="selected"' ?>>Sent</a><br>
    <a href="/pms/deleted" <? if ($active == 'deleted') echo'class="selected"' ?>>Deleted</a><br><br>
    <a href="/pms/new" class="button <? if ($active == 'new') echo'selected' ?>">New message</a>
    <br class="clear"><br><br><br>
</section>