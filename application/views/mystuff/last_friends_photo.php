<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
</div>

<div id="content">
    <div class="page_title">
        <span><?=$title?></span>
    </div>
    <div class="bg">
        <div>
            <? $i=0;
            if(!empty($photos)){
                foreach($photos as $photo){ ?>
                    <div style="float:left; text-align: center; margin:0 5px;">
                        <div style="height: 110px; width: 110px;"><a style="display:block;" href="/<?=$photo['username']?>/photo/<?=$photo['photo_id']?>"><img style="max-width: 110px;  max-height: 110px;" src="/files/users/uploads/<?=$photo['id']?>/<?=$photo['photo_id']?>.jpg"></a></div>
                        <a href="/<?=$photo['username']?>"><?=$photo['username']?></a>
                    </div>
                <? if((++$i%5)==0) {
                    ?> <br class="clear"> <?
                } 
                }?>


            <br class="clear">
            <?pagination($pages)?>
            <? } ?>
        </div>
    </div>
    <div class="footer"></div>
</div>
