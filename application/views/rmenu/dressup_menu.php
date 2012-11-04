<div class="accordion">
    <?
    $active = $this->uri->segment(2);
    foreach ($categories[0] as $val) {
        ?>
        <div class="load_items">
            <?
            if (!empty($categories[$val['id']])) {
                $summ = 0;
                $line = '';
                if (trim($val['shortname']) != 'face') {
                    foreach ($categories[$val['id']] as $v) {
                        $summ += intval($items[$v['id']]);
                        $sel = ($active == $v['shortname']) ? 'selected' : '';
                        $line .= '<a href="/dressup/' . $v['shortname'] . '#scroll" class="subcat ' . $sel . '">' . $v['name'] . ' (' . intval($items[$v['id']]) . ')</a>';
                    }
                    ?>
                    <a class="slide <? if ($active == $val['shortname']) echo 'selected'?>" href="/dressup/<?= $val['shortname'] ?>#scroll"><?= $val['name'] ?> (<?= $summ ?>) <small></small></a>
                    <p <? if ($active != $val['shortname']){
                        echo'class="hide"';
                    } ?> style="padding-left: 20px">
                        <?
                        echo $line;
                        ?>
                    </p><?
        }
    } else {
        if (trim($val['shortname']) != 'skin') {
                        ?>
                    <a href="/dressup/<?= $val['shortname'] ?>#scroll" <? if ($active == $val['shortname']) echo'class="selected"' ?>><?= $val['name'] ?> (<?= intval($items[$val['id']]) ?>)</a>
                    <?
                }
            }
            ?>
        </div>
        <?
    }
    ?>
    <input type="hidden" name="menu_active" value="<?= $parent_active ?>">
</div>

<a href="/dressup/edit_dressup" class="button">Saved dressups</a>
<a href="/dressup/outfits" class="button">Saved outfits</a>
<script>
    $(function(){
        if($('a.selected').length > 0){
            if($('a.selected').parent().is('p')){
                $('a.selected').parent().show()
                $('a.selected').parent().prev().find('small').addClass('opened')
            }
        }
    })
</script>