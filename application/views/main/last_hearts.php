<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div class="page_title">
        <span>Last Hearts</span>
    </div>
    <div class="bg">
        <?
        
        if (!empty($hearts)) {
            foreach ($hearts as $val) {
                ?>
                <div class="news_block">
                    <?
                    if($val['type'] == 'dressup'){
                    ?><a class="left news_img" href="/<?= $this->user['username'] ?>/dressup/<?= $val['id'] ?>">
                        <img src="/files/users/dressup/<?= $val['id'] ?>.jpg" style="width: 160px">
                    </a><?
                }else{
                    ?><a class="left news_img" href="/<?= $this->user['username'] ?>/photo/<?= $val['id'].$val['rand_num'] ?>">
                        <img src="/files/users/uploads/<?=$val['uid']?>/<?= $val['id'].$val['rand_num'] ?>.jpg" style="width: 160px">
                    </a><?
                }
                    ?>
                    
                    <div class="left"><?
        $users = explode(',',$val['like_users']); //id_username
        $users_count = count($users);
        $last = explode('_',array_pop($users)); //[0]=id, [1] = username
                ?><a href="/<?= $last[1] ?>" target="_blank"><img src="<?= get_user_avatarlink($last[0]) ?>" class="small_avatar"></a> 
                        <strong><?= time_from($val['last_like']) ?></strong><br class="clear">
                        Total hearts: <?= $users_count ?>
                    </div><br class="clear">
                </div>
                <?
                
                
            }
        } else {
            ?><em>*No hearted content</em><?
    }
        ?>
            <br class="clear">
    </div>
    <div class="footer"></div>
</div>