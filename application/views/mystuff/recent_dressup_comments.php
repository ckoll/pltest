<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
</div>

<div id="content">
    <div class="page_title">
        <span><?=$title?></span>
    </div>
    <div class="bg">
        <?
        if(!empty($comments)){
            foreach($comments as $comment){
                ?>
            <div>
                <div style="float:left; width:300px; margin:0 8px; text-align: center; height: 250px;"><a href="/<?=$user['username']?>/dressup/<?=$comment['dressup_id']?>"><img style="max-width: 300px; max-height: 300px;" src='/files/users/dressup/<?=$comment['dressup_id'].'.jpg'?>'></a></div>
                <div><p><?=$comment['comment']?><br>comment by: <a href="/<?=$comment['username']?>"><?=$comment['username']?></a> / on: <?=$comment['date_comment']?></p></div>
            </div>      
            <br class="clear">
                <?}}?>
            <br class="clear">
            <? pagination($pages);?>
    </div>
    <div class="footer"></div>
</div>
