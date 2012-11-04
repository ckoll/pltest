<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div class="page_title">
        <span>Invite Friends</span>
    </div>
    <div class="bg">

        <p>Get 50 buttons for every invitation you send, and<br>
            500 buttons for every user that signs up.</p>
        <p>You can send up to 50 invitations per day.</p>

        <?
        if ($this->input->post('invite')) {
            ?>
            <b>You are inviting <?= count($this->input->post('invite')) ?> friend(s).</b>
            <?
        } else {
            ?><br>
            <a href="/find_friends">Search friends</a> in Facebook, Twitter, Gmail, Hotmail or Yahoo!.<br>
            <a id="show_invite_form">Enter email address</a> without searching.<br>
            <?
        }
        ?>

        <form action="" method="post" class="<? if (!$this->input->post('invite')) echo 'hide'; ?> invite_form">
            <span class="err" style="display: none"></span>
            <input type="hidden" name="system" value="<?= @$this->input->post('system') ?>">
            <input type="hidden" name="selected_users" value="<?
        if ($this->input->post('invite')) {

            if (@$this->input->post('system') == 'twitter') {
                foreach ($this->input->post('invite') as $key => $val) {
                    $all[] = $key . '|' . $val;
                }
                $contacts_imploded = implode(',', $all);
                echo $contacts_imploded;
            } else {
                echo implode(',', $this->input->post('invite'));
            }
        }
        ?>">
            <div class="hide">
                <textarea name="email" id="invite_email" data-default="Separate email addresses with a comma">Separate email addresses with a comma</textarea><br>
            </div>
            <? if (@$this->input->post('system') != 'twitter') { ?>
                Message: <small>(optional)</small><br>
                <textarea name="message"></textarea><br>
            <? } ?>
            <input type="submit" name="send_invite" value="Send">

        </form>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>