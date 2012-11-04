<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
</div>

<div id="content">
    <div class="page_title">
        <span><?=$title?></span>
    </div>
    <div class="bg">
        <div class="brands" style="margin-left: 20px">
            <? $i=0;
            if(!empty($likes)){
                foreach($likes as $like){ ?>
                    <div>
                        <a href="/<?=$like['username']?>/photo/<?=$like['photo_id']?>"><img style="max-width: 100px; max-height: 110px;" src="/files/users/uploads/<?=$like['uid']?>/<?=$like['photo_id']?>.jpg"></a><br>
                        <a href="/<?=$like['username']?>"><?=$like['username']?></a>
                    </div>
                <? if((++$i%6)==0) {
                    ?> <br class="clear"> <?
                } 
                }} ?>

            <br class="clear">
            <?pagination($pages)?>
        </div>
    </div>
    <div class="footer"></div>
</div>
