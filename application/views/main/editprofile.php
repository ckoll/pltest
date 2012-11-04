<?
$user['x2'] = (empty($user['x2'])) ? 200 : $user['x2'];
$user['y2'] = (empty($user['y2'])) ? 200 : $user['y2'];
$user['x1'] = (empty($user['x1'])) ? 0 : $user['x1'];
$user['y1'] = (empty($user['y1'])) ? 0 : $user['y1'];
?>

<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div class="page_title">
        <span>Edit Profile</span>
    </div>
    <div class="bg">

        <?
        if ($this->input->get('saved') == 'ok') {
            $message = 'User profile updated.';
        } elseif ($this->input->get('save_image') == 'ok') {
            $message = 'Profile image saved.';
        } elseif ($this->input->get('password') == 'ok') {
            $message = 'Your password has been updated.';
        }

        if (!empty($message)) {
            ?>
            <span class="message"><?= $message ?></span>
        <? }
        ?>
        <p  class="center">Edit Profile</p>
        <? if (!empty($err)) { ?>
            <span class="err"><?= $err ?></span>
        <? } ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="center">
                <img src="<?= get_user_avatarlink($user['id'], 'originals') ?>" id="avatar" <? if (strpos(get_user_avatarlink($user['id'], 'originals', true), 'default') == false) echo'class="avatar"' ?>>
                <br><br>
                <input type="file" name="avatar" id="change_avatar_button">
                <span id="change_avatar">Change Picture</span>
            </div>

            <div class="center">
                <input type="submit" name="save_image" value="Save Profile Image" class="save_image hide">
            </div>
            <input type="hidden" name="avatar_x1" value="<?= $user['x1'] ?>" />
            <input type="hidden" name="avatar_y1" value="<?= $user['y1'] ?>" />
            <input type="hidden" name="avatar_x2" value="<?= $user['x2'] ?>" />
            <input type="hidden" name="avatar_y2" value="<?= $user['y2'] ?>" />
            <b>Email:</b> <input type="text" name="email" autocomplete="off" value="<? if (!empty($user['email'])) echo $user['email']; ?>" size="40"><br>
            <div class="hide"><b>Old Password</b> <input type="password" name="old_password"><br></div>
            <b>Password:</b> <input type="password" name="password" value="<?= (!empty($user['password'])) ? '*******' : ''; ?>" disabled="disabled" readonly="readonly"><br>
            <span><b>&nbsp;</b> <a id="change_password">Change Password</a><br></span>
            <div class="hide"><b>Confirm Password</b> <input type="password" name="password2"><br></div>

            <b>Favorite Brands</b><a id="open_edit_favorite_brands">Edit</a><br>

            <b>Short bio<br><small>max 150 characters</small></b> <textarea cols="37" rows="3" name="bio" maxlength="150"><?= $user['bio']; ?></textarea><br>

            <b>Sharing Settings</b>
            <a style="width: 200px" href="/facebook" class="button <? if (!empty($user['fb_id'])) echo'checked' ?>"><?= (!empty($user['fb_id'])) ? 'Connected to Facebook' : 'Connect to Facebook'; ?></a><?
        if ((empty($user['password']) || empty($user['username'])) && !empty($user['fb_id'])) {
            ?><span class="status green">connected to <?= $user['fb_fullname'] ?></span><?
        } elseif (!empty($user['password']) && !empty($user['fb_id'])) {
            ?><div class="status green">connected to <?= $user['fb_fullname'] ?><br><a id="fb_disconnect">X Disconnect</a></div><?
        } else {
            ?><span class="status red">not connected</span><?
            }
        ?></span><br>

            <b>&nbsp;</b><a style="width: 200px" href="/twitter" class="button <? if (!empty($user['tw_id'])) echo'checked' ?>"><?= (!empty($user['tw_id'])) ? 'Connected to Twitter' : 'Connect to Twitter'; ?></a><?
            if ((empty($user['password']) || empty($user['username'])) && !empty($user['tw_id'])) {
            ?><span class="status green">connected to <?= $user['tw_username'] ?></span><?
        } elseif (!empty($user['password']) && !empty($user['tw_id'])) {
            ?><div class="status green">connected to <?= $user['tw_username'] ?><br><a id="tw_disconnect">X Disconnect</a></div><?
        } else {
            ?><span class="status red">not connected</span><?
            }
        ?>
            <br>
            <b>Blocked Users</b><span><?
                if (!empty($blocked)) {
                    $x = 0;
                    foreach ($blocked as $val) {
                        if ($x == 3)
                            break;
                        echo $val['username'] . ', ';
                        $x++;
                    }
                    if (count($blocked) > 2) {
                        echo 'and ' . count($blocked) . ' more<br>';
                    }
            ?><a id="edit_blocked">Edit</a><?
            } else {
                echo '<em>*no blocked users</em>';
            }
        ?></span><br>
            <b>Notifications</b>
            <input type="radio" name="notif" <?= ($user['notif']) ? 'checked="checked"' : '' ?>  value="1">On 
            <input type="radio" name="notif" <?= (!$user['notif']) ? 'checked="checked"' : '' ?> value="0">Off
            <a id="open_notif" <? if (!$user['notif']) echo 'class="hide"'; ?>>Edit</a>
            <br>

            <?
            if ($user['id'] <= 1000) {
                ?>
                <b>Display ribbon?</b>
                <input type="radio" name="ribbon" <?= ($user['ribbon']) ? 'checked="checked"' : '' ?> value="1">Yes
                <input type="radio" name="ribbon" <?= (!$user['ribbon']) ? 'checked="checked"' : '' ?>  value="0">No 
                <br>
                <?
            }
            ?>


            <b>&nbsp;</b><input type="submit" value="Update" name="saveprofile"/>
        </form>
            <br class="clear">
    </div>
    <div class="footer"></div>
</div>

<div id="blocked_modal" class="hide" title="Blocked Users">
    <div class="modal">
        <table width="100%">
            <tr>
                <td>Username</td>
                <td>In Blacklist from</td>
                <td>Action</td>
            </tr>
            <?
            if (!empty($blocked)) {
                foreach ($blocked as $val) {
                    ?><tr>
                        <td width="50%"><b><?= $val['username'] ?></b></td>
                        <td><?= $val['added'] ?></td>
                        <td width="20"><img src="/images/del.gif" class="del_blocked_user" data-id="<?= $val['black_id'] ?>"></td>
                    </tr><?
        }
    }
            ?>
        </table>
    </div>
</div>
<div id="notif_modal" class="hide" title="Edit email notifications">
    <div class="modal">
        <input type="checkbox" name="friend_request" <?= (!empty($user['friend_request'])) ? "checked='checked'" : ""; ?>> When someone request to be your friend<br>
        <input type="checkbox" name="received_gift" <?= (!empty($user['received_gift'])) ? "checked='checked'" : ""; ?>> Received gifts<br>
        <input type="checkbox" name="item_sold" <?= (!empty($user['item_sold'])) ? "checked='checked'" : ""; ?>> Shop item/auction item has sold/or not been sold<br>
        <input type="checkbox" name="_7days" <?= (!empty($user['_7days'])) ? "checked='checked'" : ""; ?>> When you haven't logged in for 7 days.<br>
    </div>
</div>
<div id="fav_brands_modal" class="hide" title="Favorite brands">
    <div class="modal">
        <div class="message" style="display:none;" ></div>
        <? foreach ($favorite_brands as $brand) {
            ?>
            <div id="brand_<?= $brand['id'] ?>" style="width:75px;height: 120px;float:left; margin:8px; position:relative;" class="center">
                <img src="/files/brands/<?= (empty($brand['imagename']) || !file_exists(FILES.'brands/'.$brand['imagename']))?'default.png':urlencode($brand['imagename']) ?>" width="75"><br>
                <?= $brand['title'] ?>
                <img src="/images/del.png" data-id="<?= $brand['id'] ?>" class="del_fav_brand" style="position:absolute; top:0; right: 0;">
            </div>    
            <?
        }
        ?>
    </div>
</div>
<script>
    $(function(){
        var avatarWidth = 0
        var avatarHeight = 0
        var firstClick = 0;
        rand = Math.floor(Math.random()*5000)
        
        if($('img.avatar').length > 0){
            if( $('img.avatar').attr('src').indexOf('default.png') == -1){
                updateLAvatar()
            }
        }
        
        function updateLAvatar(){
            var scaleX = 200 / ( <?= ($user['x2'] - $user['x1']) ?> || 1)
            var scaleY = 200 / ( <?= ($user['y2'] - $user['y1']) ?> || 1)
            $('img.avatar').attr("src", $('img.avatar').attr("src")).load(function() {
                avatarWidth = this.width
                avatarHeight = this.height
                width = avatarWidth * scaleX ;
                height = avatarHeight * scaleY ;
                $('.avatar200 img').css('width',width+'px').css('height',height+'px')
            });
            $('div.avatar200 img').attr('src','/files/users/originals/<?= $user['id'] ?>.jpg?val='+rand).css({
                width: Math.round(scaleX * avatarWidth) + 'px',
                height: Math.round(scaleY * avatarHeight) + 'px',
                marginLeft: '-' + Math.round(scaleX * <?= $user['x1'] ?>) + 'px',
                marginTop: '-' + Math.round(scaleY * <?= $user['y1'] ?>) + 'px'
            });    
        }
        var i=0;
        function showPreview(img, selection){
            $('div[class^=imgareaselect-]').show();
            if(i==0){i++}else{
                var scaleX = 200 / (selection.width || 1);
                var scaleY = 200 / (selection.height || 1);
                $('div.avatar200 img').css({
                    width: Math.round(scaleX * $('img.avatar').width()) + 'px',
                    height: Math.round(scaleY * $('img.avatar').height()) + 'px',
                    marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
                    marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
                });
            }
            
            $('.save_image').fadeIn() //Show "Save Image" button
        }
        
        $('img.avatar').imgAreaSelect({
            handles: true,
            onSelectChange: showPreview,
            aspectRatio: '1:1',
            minWidth: 25,
            minHeight: 25,
            x1: <?= $user['x1'] ?>, 
            y1: <?= $user['y1'] ?>, 
            x2: <?= $user['x2'] ?>, 
            y2: <?= $user['y2'] ?>,
            onSelectEnd: function(img, selection){
                $('input[name="avatar_x1"]').val(selection.x1);
                $('input[name="avatar_y1"]').val(selection.y1);
                $('input[name="avatar_x2"]').val(selection.x2);
                $('input[name="avatar_y2"]').val(selection.y2);
            }
        })
        
        $('#avatar').mouseleave(function(){
            $('.imgareaselect-outer').prev().show()
        })
        
    })
    
</script>