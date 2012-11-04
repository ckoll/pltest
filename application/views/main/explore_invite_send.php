<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>


<div id="content">

    <div class="page_title">
        <span>Invite Friends</span>
    </div>
    <div class="bg">

        <?
        if ($system == 'facebook') {
            ?>
            <div id="fb-root"></div>
            <script src="http://connect.facebook.net/en_US/all.js"></script>
            <script>
                var last_contact = '';
                var last_button = '';
                
                FB.init({
                    appId  : '392636414121505',
                    frictionlessRequests: true
                })
                            
                function facebook_send_message(button,user) {
                    last_contact = user
                    last_button = button
                    
                    FB.ui({
                        app_id:'392636414121505',
                        method: 'send',
                        name: "Perfect-Look",
                        link: 'https://www.perfect-look.org',
                        to: user,
                        description:'Join to www.perfect-look.org - fun fashion site where you can share and tag fashion photos and create perfect look dressup.'
                    }, requestCallback);
                }
                
                function requestCallback(response) {
                    if(response){
                        $.post('/explore/ajax',{
                            'func': 'facebook_invites',
                            'data': last_contact
                        },function(data){
                            if(data.err){
                                $(last_button).val('Is not Sent').css('width','100px').css('background-color','red').attr('disabled','disabled')
                            }else{
                                $(last_button).val('Sent').css('width','80px').attr('disabled','disabled')
                                $('.num_invites').text(parseInt($('.num_invites').text())-1)
                            }
                        },'json')
                    }
                }
            </script>

            <?
        }

        if (!empty($sended['all_sended_status'])) {
            ?><table width="100%" class="friends_table">
                <tr class="title">
                    <td>User</td>
                    <td>Status</td>
                </tr>
                <?
                $x = 0;
                foreach ($sended['all_sended_status'] as $key => $val) {
                    ?>
                    <tr <? if ($x % 2 == 0) echo'class="odd"' ?>>
                        <td width="60%"><?= $key ?></td>
                        <td><em><?
            switch ($val) {
                case '1':
                    if ($system == 'facebook') {
                                ?><input type="button" class="button fbbutton_send" onclick="facebook_send_message(this,<?= $sended['fb_more_data'][$key] ?>); return false;" value="Send"/><?
                    } else {
                        echo '<span class="green">Invite Sended</span>';
                    }
                    break;
                case '0':
                    echo '<span class="red">No more Invite today</small>';
                    break;
                case '-1':
                    $time = (!empty($sended['already_sended'][$key]['date'])) ? $sended['already_sended'][$key]['date'] : 'earlier';
                    echo '<span class="red">Not sended</span> <small>You send this invite ' . $time . '</small>';
                    break;
                case '-2':
                    echo '<span class="red">Wrong format</small>';
                    break;
                case '-3':
                    echo '<span class="red">User already registered</small>';
                    break;
            }
                    ?></em></td>
                    </tr>
                    <?
                    $x++;
                }
                ?></table><?
        } else {
                ?><span class="err">Error, Invite not sended, please try again later.</span><?
        }

        if ($sended['today_sended'] < 50) {
                ?>Today you have <span class="num_invites"><?= (50 - $sended['today_sended']) ?></span> more invites.<?
    } else {
                ?>Today you used all invites.<?
    }
            ?>
                <br class="clear">
    </div>
    <div class="footer"></div>
</div>