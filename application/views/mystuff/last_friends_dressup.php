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
            if(!empty($dressups)){
                foreach($dressups as $dressup){ ?>
                    <div style="float:left; text-align: center; margin:5px 5px;">
                        <div style="height: 150px; width: 210px;"><a style="display:block;" href="/<?=$dressup['username']?>/dressup/<?=$dressup['dressup_id']?>"><img style="max-width: 210px;  max-height: 210px;" src="/files/users/dressup/<?=$dressup['dressup_id']?>.jpg"></a></div>
                        <a href="/<?=$dressup['username']?>"><?=$dressup['username']?></a>
                    </div>
                <? if((++$i%3)==0) {
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
