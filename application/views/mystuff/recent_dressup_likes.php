<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
</div>

<div id="content">
    <div class="page_title">
        <span><?=$title?></span>
    </div>
    <div class="bg">
        <div class="dressup_likes">
            <? $i=0;
            if(!empty($likes)){
                foreach($likes as $like){ ?>
                    <div>
                        <a href="/<?=$user['username']?>/dressup/<?=$like['dressup_id']?>"><img width="200" src="/files/users/dressup/<?=$like['dressup_id']?>.jpg"></a><br>
                        <a href="/<?=$like['username']?>"><?=$like['username']?></a>
                    </div>
                <? if((++$i%3)==0) {
                    ?> <br class="clear"> <?
                } 
                }} ?>
            <br class="clear">
            <?pagination($pages)?>
        </div>
    </div>
    <div class="footer"></div>
</div>
