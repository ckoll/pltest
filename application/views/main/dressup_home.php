<?
$curr_dresup = $this->uri->segment(3);
?>

<div id="content" style="width: 1000px">

    <div class="bg">

        <div style="margin-bottom: 6px;">
            <input type="button" onclick="location.href='/dressup/dress/'" value="New dressup" />
            <br>
            Your doll: <span><?= (!empty($this->user['doll_name'])) ? $this->user['doll_name'] : 'Not set yet ' ?></span> <a id="new_doll_name">edit dollname</a>
            <span class="hide dollname_form">
                <input type="text" name="doll_name" maxlength="30" value="<? if (!empty($this->user['doll_name'])) echo $this->user['doll_name']; ?>"> 
                <input type="button" name="save_dollname" value="save"> 
                <input type="button" name="save_dollname" value="cancel">
            </span>
        </div>

        <div class="right_sidebar">
            <? $this->tpl->render_blocks('rmenu'); ?>
        </div>
        
        <section id="dressup" style="background-image: url(/files/items/backgrounds/<?=$doll['doll']['background']?>);">
            <div id="doll_position">
                <?
                if (!empty($doll['code'])) {
                    foreach ($doll['code'] as $val) {
                        ?><div style="background-image: url(/files/<?= $val ?>)"></div><?
            }
        }
                ?>
            </div>
            <div class="load_dressup">
                <img src="/images/loading_big.gif">
            </div>
        </section>

        <input type="hidden" name="dressup_id" value="">
        <div id="dressup_items">

            <?php $this->load->view('ajax_block/dressup_item_list', array('items' => $recent_items));?>

        </div>
        
        <div class="left" style="width: 350px; margin-top: 20px;">
            <input type="button" value="Save look" name="save_doll">
            <input type="button" value="Quick save outfit" name="save_outfit">
            Share
            <input type="button" value="@" class="share_email" data-mode="dressup">
            <input type="button" value="FB" class="share_fb" data-mode="dressup">
            <input type="button" value="TW" class="share_tw" data-mode="dressup">
            <br><br>
            <!--<input type="button" class="button_gray" name="arms_change_pos" style="width: 120px" value="Arm <?=($doll['arms']=='back')? 'Forward' : 'Back'; ?>">-->
        </div>
        <div id="item_more"></div>
        
        <br style="clear: left"><br>
        <h2>Item you are using now &nbsp;&nbsp;<a href="/dressup" target="_blank">Item Inventory</a></h2>

        <div id="scrollbar" class="used_items">
            <div class="viewport">
                <div class="overview">
                    <?
                    if (!empty($doll['items'])) {
                        foreach ($doll['items'] as $val) {
                            ?>
                            <div data-id="<?= $val['id'] ?>" class="used">
                                <img src="/files/items/<?= $val['directory'] ?>/<?=($val['profileimage_dir']=='[default]')? 'profilepics' : $val['profileimage_dir'] ;?>/<?=$val['profileimage']?>">
                                <span class="remove_item hide"></span>
                            </div> 
                            <?
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="scrollbar"><div class="track"><div class="thumb"></div></div></div>
        </div>	
        <br class="clear">
    </div>
</div>

<div id="save_look_modal" class="hide" title="Save Look">
    <div class="modal">
        <div class="step1">
            <p>Save this doll's look to you'r doll lookbook</p><br>
            <select name="saving" class="hide">
                <option value="0">Save as new look</option> 
                <option value="<?= $curr_dresup; ?>" <? if (!empty($_GET['edit'])) echo'selected="selected"'; ?>>Save current look</option>
            </select>
            <div class="day_look">
                <b>Set as doll's look of the day?</b> &nbsp;
                <input type="radio" name="daylook" value="1" <? if(empty($today_dressup_finded))echo'checked="checked"'?>> yes &nbsp;
                <input type="radio" name="daylook" value="2" <? if(!empty($today_dressup_finded))echo'checked="checked"'?>> no<br><br>
            </div>
            <strong>Comment:</strong><small>(optional)</small><br>
            <textarea name="comment" style="height: 40px"></textarea><br><br>
            <p class="center">
                <input type="button" name="save_look" class="big_button" value="Save" style="margin-right: 30px">
                <input type="button" name="close_save_modal" value="Cancel">
            </p>
        </div>
        <div class="step2 hide">
            <p class="center"><strong>You look has been saved!</strong></p><br>
            <a href="#" target="_blank" class="result_dressup"></a>
            <p class="left">
                Share:<br>
                <input type="button" value="@" class="share_email" data-mode="dressup"><br><br>
                <input type="button" value="FB" class="share_fb" data-mode="dressup"><br><br>
                <input type="button" value="TW" class="share_tw" data-mode="dressup"><br><br><br>
                You look url: <br><input type="text" name="look_url" style="width: 280px; font-size: 11px" value="">
                <input type="hidden" name="look_url_redirect" value="">
                <input type="hidden" name="base_dressup_url" value="http://<?= $_SERVER['SERVER_NAME'] ?>/<?= $this->user['username'] ?>/dressup/">
            </p>
            <br class="clear">


        </div>
    </div>
</div>

<div id="outfit_modal" class="hide" title="Save Outfit">
    <div class="modal center">
        <div class="step_outfit1">
            <p>You can quick-save an outfit for easy<br> dressup later convenient for saving <br>frequently worn item groups or looks.</p><br><br>
            <b>Name: </b><input type="text" name="outfit_name"><br><br><br>
            <input type="button" name="save_outfit_send" value="Save" class="big_button" style="margin-right: 30px">
            <input type="button" name="close_outfit_modal" value="Cancel">
        </div>
        <div class="step_outfit2 hide">
            <div class="result_dressup"></div>
            <div class="left" style="text-align: left">
                <strong>Outfit has been saved!</strong><br><br>
                <input type="button" name="close_outfit_modal" value="Close">
            </div>
        </div>
    </div>
</div>

<div id="share_email_modal" class="hide" title="Share Dressup">
    <div class="modal center">
        <div class="first">
            Email list:<br>
            <textarea name="share_emails"></textarea><br>
            <small class="left">*Separate email addresses with a comma</small><br><br>
            <input type="button" name="share_email_run" class="big_button" value="Share">
            <input type="button" name="close_share_email" value="Close">
        </div>
        <div class="result"></div>
    </div>
</div>

<div id="share_fb_modal" class="hide" title="Share Dressup">
    <div class="modal center">
        <div class="first">
            <?
            if (!empty($this->user['fb_id'])) {
                ?>
                Share for: <?= $this->user['fb_username'] ?><br>
                Your comments: <small>(optional)</small><br>
                <textarea name="share_fb_descr" maxlength="150"></textarea><br>
                <input type="button" name="share_fb_run" class="big_button" value="Share">
                <input type="button" name="close_share_fb" value="Close">
                <?
            } else {
                ?>
                <a href="/facebook" class="center button">connect to facebook</a>    
                <?
            }
            ?>

        </div>
        <div class="result"></div>
    </div>
</div>

<div id="share_tw_modal" class="hide" title="Share Dressup">
    <div class="modal center">
        <div class="first">
            <?
            if (!empty($this->user['tw_id'])) {
                ?>
                Share for: <?= $this->user['tw_username'] ?><br>
                Your comments: <small>(optional)</small><br>
                <textarea name="share_tw_descr" maxlength="100"></textarea><br>
                <input type="button" name="share_tw_run" class="big_button" value="Share">
                <input type="button" name="close_share_tw" value="Close">
                <?
            } else {
                ?>
                <a href="/twitter" class="center button">connect to twitter</a>    
                <?
            }
            ?>

        </div>
        <div class="result"></div>
    </div>
</div>