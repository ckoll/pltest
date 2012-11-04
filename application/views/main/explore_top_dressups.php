<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span>Top 10 dressups</span>
    </div>
    <div class="bg">

        <?
        if (!empty($dressup)) {
            foreach ($dressup as $val) {
                ?>
                <div style="margin-bottom: 20px;">
                    <a href="/<?= $val['username'] ?>/dressup/<?= $val['id'] ?>"><img src="/files/users/dressup/<?= $val['id'] ?>.jpg" style="width: 350px; margin-right: 20px" class="left"></a>
                    <div class="left">
                        <a href="/<?= $val['username'] ?>"><?= $val['username'] ?></a><br>
                        <span class="likes" style="margin: 4px" data-id="<?=$val['id']?>" data-mode="dressup"><?= $val['like'] ?></span>
                        <span class="comments" style="margin: 4px"><?= intval($val['comments']) ?></span>
                    </div>
                    <br class="clear">
                </div>
                <?
            }
        }
        ?>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>