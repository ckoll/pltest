<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>


<div id="content">

    <div class="page_title">
        <span>Saved dressups</span>
    </div>
    <div class="bg">
        <span class="message" style="display: none"></span>
        <?
        if (!empty($dressups)) {
            ?>
            <a target="_blank" href="/files/users/dressup-HD/<?= $dressups[0]['id'] ?>.jpg">
                <div class="outfit_preview" style="background-image: url(/files/users/dressup/<?= $dressups[0]['id'] ?>.jpg);"></div>
            </a>
            <br class="clear"><br>
            <?
            pagination($pages);
            ?>
            <div class="overview">
                <?
                foreach ($dressups as $key => $val) {
                    ?>
                    <div class="outfit <? if ($key == 0) echo'active' ?>">
                        <img src="/files/users/dressup/<?= $val['id'] ?>.jpg?v=<?= rand(1, 1000) ?>" class="preview">
                        <small class="center">
                            <a href="/dressup/dress/<?= $val['id'] ?>?edit=1">edit</a>, <a data-id="<?= $val['id'] ?>" class="duplicate_dressup">copy</a>,
                            <? if ($val['day_look'] == 0) { ?><a data-id="<?= $val['id'] ?>" class="wear_dressup">wear</a><?
                } else {
                    echo 'day look';
                }
                ?>
                        </small>
                        <? if ($val['day_look'] == 0) {
                            ?><img src="/images/del.png" class="remove_dressup" data-id="<?= $val['id'] ?>"><? }
                        ?>
                    </div>
                    <?
                }
                ?>
                <br class="clear">
            </div>
            <?
        } else {
            ?><em>You don't have saved dressups</em><?
    }
        ?>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>