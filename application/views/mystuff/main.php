<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
</div>

<div id="content">
    <div class="page_title">
        <span><?=($this->user['username'] == $this->uri->segment(1) || $this->uri->segment(1)=='mystuff')?'My Stuff':'Stuff'?></span>
    </div>
    <div class="bg">
        <div style="width:400px; margin: 0 auto;">
        <p>This is a quick reference with the links to various sections on the site that 
        you might find helpful to navigate your way arround the site.</p><br>
        
        
        <img src="/files/users/dressup/<?=$day_look['id']?>.jpg" style="width:100%"><br><br>
        
        
        <p align="center"><a href="/">return home</a></p>
        </div>
    </div>
    <div class="footer"></div>
</div>