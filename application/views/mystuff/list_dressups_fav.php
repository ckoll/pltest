<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
</div>

<div id="content">
    <div class="page_title">
        <span><?=$title?></span>
    </div>
    <div class="bg">
        <div style="margin-left: 30px">
    <?
    if(!empty($dressups))
    foreach($dressups as $dressup){
        ?><a href="/<?=$dressup['username']?>/dressup/<?=$dressup['id']?>"><img style="max-width: 300px; margin:8px; max-height: 300px;" src='/files/users/dressup/<?=$dressup['id'].'.jpg'?>'></a><?
    }
    ?>
        <br class="clear">
        <?pagination($pages)?>
        </div>
    </div>
    <div class="footer"></div>
</div>
