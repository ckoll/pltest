<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div class="page_title">
        <span>Dressup calendar</span>
    </div>
    <div class="bg">
        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
        <div id="full_calendar" data-uid="<?= $user['id'] ?>"></div>
        <div class="calendar_data"></div>
        
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>