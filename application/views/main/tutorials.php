<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>
<script>
$(document).ready(function(){
    $('ul li a').click(function(){
        if($(this).next().css('display')=='none'){
            $('ul li div').hide(300);
            $(this).next().show(300);
        }
    });
});
</script>
<style>
    
    ul{
        margin:0; padding:0;
        margin-left:20px;
    }
    ul li{
        margin: 0;
        list-style: none;
        line-height: 20px;
    }
    ul li div{
        display:none;
    }
</style>

<div id="content">
    <div class="page_title">
        <span>Featured photos</span>
    </div>
   
    <div class="bg">
        <ul>
        <?
        if (!empty($tuts)) {
            foreach ($tuts as $val) {
                ?>
            <li>
                <a class="open_tut"><?=$val['title']?></a>
                <div><?=$val['title']?></div>
            </li>
                <?
            }
        }
        ?>
        </ul>    
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>