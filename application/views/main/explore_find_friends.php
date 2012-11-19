<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div class="page_title">
        <span>Find users who are already using the site</span>
    </div>
    <div class="bg">

        <div id="tabs">
            <ul>
                <li><a href="#tabs2">By username</a></li>
                <li><a href="#tabs1">From your contacts</a></li>
            </ul>
            <div id="tabs1">
                <b class="left">Search your contacts:</b>
                <div class="left">
                    <a href="https://accounts.google.com/o/oauth2/auth?client_id=<?= $this->config->item('gmail_client_id') ?>&redirect_uri=<?= $this->config->item('gmail_redirect_uri') ?>&scope=https://www.google.com/m8/feeds/&response_type=code" class="button contacts_small <? if ($system == 'gmail') echo'checked' ?>" data-system="gmail">Gmail</a>
<!--                    <a href="/find_friends/?hotmail=1" class="button contacts_small <? if ($system == 'hotmail') echo'checked' ?>" data-system="hotmail">Hotmail</a>-->
                    <a href="/find_friends/?yahoo=1" class="button contacts_small <? if ($system == 'yahoo') echo'checked' ?>" data-system="yahoo">Yahoo!</a>
                    <br clear="all">
                    <a href="/find_friends/?fb=1" class="button contacts_big <? if ($system == 'facebook') echo'checked' ?>" data-system="facebook">Find on Facebook</a><br  clear="all">
                    <a href="/find_friends/?tw=1" class="button contacts_big <? if ($system == 'twitter') echo'checked' ?>" data-system="twitter">Find on Twitter</a>
                </div>
                <br class="clear">
                <form action="/invite_friends" method="post">
                    <?
                    if (!empty($err)) {
                        ?><span class="err"><?= $err ?></span><?
                }
                if (!empty($mail_contacts)) {
                    $finded_emails = array_keys($finded);
                        ?>
                        <hr>
                        <table width="100%" class="friends_table">
                            <tr class="title">
                                <td>Invite</td>
                                <td>Email</td>
                                <td>Registered</td>
                            </tr>
                            <?
                            $x = 0;
                            foreach ($mail_contacts as $key => $val) {
                                ?>
                                <tr <? if ($x % 2 == 0) echo'class="odd"' ?>>
                                    <td width="20">
                                        <?
                                        if (!in_array($key, $finded_emails)) {
                                            ?><input type="checkbox" name="invite[]" value="<?= $key ?>"><?
                            } else {
                                            ?><input type="checkbox" disabled="disabled"><? }
                                        ?>
                                    </td>
                                    <td><?
                                echo $key;
                                if (!empty($val) && $val != $key) {
                                    echo ' <small><' . $val . '></small>';
                                }
                                        ?></td>
                                    <td width="100">
                                        <?
                                        if (in_array($key, $finded_emails)) {
                                            ?><a href="/<?= (empty($finded[$key]['username'])) ? 'id' . $finded[$key]['id'] : $finded[$key]['username']; ?>" target="_blank">view profile</a><?
                            } else {
                                            ?><em class="red">not registered</em><?
                            }
                                        ?>
                                    </td>
                                </tr>
                                <?
                                $x++;
                            }
                            ?></table>
                        <input type="submit" value="Invite Friends >" class="button contacts_medium">
                        <br class="clear">
                        <?
                    } elseif (!empty($social_contacts)) {
                        $finded_ids = array_keys($finded);
                        ?><hr>
                        <table width="100%" class="friends_table">
                            <tr class="title">
                                <td>Invite</td>
                                <td>Username</td>
                                <td>Registered</td>
                            </tr>
                            <?
                            $x = 0;
                            foreach ($social_contacts as $key => $val) {
                                ?>
                                <tr <? if ($x % 2 == 0) echo'class="odd"' ?>>
                                    <td width="20"><?
                        if (!in_array($val['id'], $finded_ids)) {
                                    ?><input type="checkbox" name="invite[<?= $val['id'] ?>]" value="<?= $val['name'] ?>"><?
                            } else {
                                    ?><input type="checkbox" disabled="disabled"><? }
                                ?></td>
                                    <td><?= $val['name'] ?></td>
                                    <td width="100">
                                        <?
                                        if (in_array($val['id'], $finded_ids)) {
                                            ?><a href="/id<?= $finded[$val['id']]['id'] ?>" target="_blank">view profile</a><?
                            } else {
                                            ?><em class="red">not registered</em><?
                            }
                                        ?>
                                    </td>
                                </tr>
                                <?
                                $x++;
                            }
                            ?></table>
                        <input type="submit" <? if ($system == 'facebook') echo'name="send_invite"' ?>  value="Invite Friends >" class="button contacts_medium right">
                        <br class="clear">
                        <?
                    }elseif ($system && empty($err)) {
                        ?><span class="err">*You have no <?= ($system == 'facebook' || $system == 'twitter') ? 'friends' : 'contacts'; ?> on <?= $system ?></span><?
                }
                    ?>
                    <input type="hidden" name="system" value="<?= $system ?>">
                </form>
            </div>

            <div id="tabs2">
                <b class="left" style="margin-right: 80px">Username:</b>
                <div class="left">
                    <form action="" method="get" style="margin: 0">
                        <input type="text" name="username" value="<?= @$this->input->post('username') ?>"> <input type="submit" name="search_username" value="Search">
                    </form>
                </div>
                <br class="clear">
                <?
                if ($this->input->get('search_username')) {
                    ?><hr><?
                if (!empty($finded_users)) {
                    foreach ($finded_users as $val) {
                            ?>
                            <div class="friend">
                                <a href="/<?= $val['username'] ?>" class="avatar100">
                                    <div class="border"></div>
                                    <img src="<?= get_user_avatarlink($val['id']) ?>">
                                </a>
                                <?= $val['username'] ?>
                            </div>
                            <?
                        }
                    } else {
                        if ($this->input->post('username')) {
                            ?><span class="err">*User "<?= $this->input->post('username') ?>" not found.</span><?
            } 
        }
    }
                ?>
                <br class="clear">
            </div>
        </div>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>

<div id="login_modal" class="hide" title="Login Form">
    <div class="modal">
        <form action="/find_friends" method="post" style="padding-top: 14px;">
            <span class="err modal_err"></span>
            <b>*Full email:</b> <input type="text" name="u_login"><br>
            <b>Password:</b> <input type="password" name="u_password"><br>
            <input type="hidden" name="system" value="" id="system">
            <b>&nbsp;</b><input type="submit" value="Login" id="login_me"><br><br>
            <small>*Include the @<span class="system_name"></span>.com part in the email address</small>
        </form>
    </div>
</div>

<script>
    $(function(){
        $( "#tabs" ).tabs({ selected: <?= ($this->input->get('search_username')) ? 1 : 0; ?> });
    })
</script>