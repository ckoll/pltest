<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
</div>

<div id="content">
    <div class="page_title">
        <span><?=$title?></span>
    </div>
    <div class="bg">
        <div style="margin-left: 50px">
    <?
    if(!empty($photos))
    foreach($photos as $photo){
        ?><a href="/<?=$photo['username']?>/photo/<?=$photo['photo_id']?>"><img style="max-width: 130px; margin:8px; max-height: 130px;" src='/files/users/uploads/<?=$photo['id'].'/'.$photo['photo_id'].'.jpg'?>'></a><?
    }
    ?>
        <br class="clear">
        <?pagination($pages)?>
        </div>
    </div>
    <div class="footer"></div>
</div>
