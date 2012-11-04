<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span><?=(isset($announcement))?$announcement['title']:'Announcements'?></span>
    </div>
    <div class="bg show-announcement">
        <? 
        if(!isset($announcements)) { ?>
            <div class="date"><?=$announcement['date']?></div>
            <div class="text"><?=$announcement['text']?></div>
        <?
        }else{
            if(!empty($announcements)){
                foreach($announcements as $announcement){
            ?>
            <div>
                    <h1 class="left title"><?=$announcement['title']?></h1>
                    <div class="date"><?=$announcement['date']?></div>
                     <br class="clear">
                     <div class="text"><?=(strlen($announcement['text']) > 300) ? substr($announcement['text'], 0, 300) . '...' : $announcement['text'];?><br><a href="/announcements/<?=$announcement['id']?>">read more</a></div>
                    
            </div>
            <hr>
            <? }
            }
        }
        ?>
    </div>
    <div class="footer"></div>
</div>