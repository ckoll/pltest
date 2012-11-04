<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
</div>

<div id="content">
    <div class="page_title">
        <span><?=$title?></span>
    </div>
    <div class="bg mystuff-list-friend">
        <?
        if(!empty($friends)){ 
            foreach($friends as $friend){
            ?>
            <div class="mystuff-friend">
                <div style="margin-right: 10px">
                    <a class="avatar100" style="padding:1px 1px" href="/<?=$friend['username']?>">
                        <div class="border"></div>
                        <img src='/files/users/avatars100/<? if(file_exists(APPPATH.'files/users/avatars100/'.$friend['id'].'.jpg')) { ?><?=$friend['id']?>.jpg <? }else {?>default.png<?}?>'>
                    </a>
                </div>
                <div><p><a href="/<?=$friend['username']?>"><?=$friend['username']?></a> / on: <?=$friend['adding']?></p></div>
            </div>      
            <br class="clear">


                <?


            } 
        }
        ?>
            <?        pagination($pages) ?>
    </div>
    <div class="footer"></div>
</div>
