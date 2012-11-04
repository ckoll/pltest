<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
</div>

<div id="content">
    <div class="page_title">
        <span><?=$title?></span>
    </div>
    <div class="bg">
        <div class="brands">
            <? $i=0;
            if(!empty($likes)){
                foreach($likes as $like){ ?>
                    <div>
                        <a href="/<?=$user['username']?>/photo/<?=$like['photo_id']?>"><img width="100" src="/files/users/uploads/<?=$user['id']?>/<?=$like['photo_id']?>.jpg"></a><br>
                        <a href="/<?=$like['username']?>"><?=$like['username']?></a>
                    </div>
                <? if((++$i%5)==0) {
                    ?> <br class="clear"> <?
                } 
                }} ?>
            <br class="clear">
            <?pagination($pages)?>
        </div>
    </div>
    <div class="footer"></div>
</div>
