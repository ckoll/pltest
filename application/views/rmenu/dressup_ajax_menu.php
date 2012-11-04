<div class="accordion">
    <?
    foreach (array('categories' => $categories, 'body_parts' => $body_parts) as $key => $cat) {
        foreach ($cat[0] as $val) {
            ?>

            <div class="load_items">
                <?
                $summ = 0;
                $line = '';
                if (!empty($cat[$val['id']])) {
                    foreach ($cat[$val['id']] as $v) {
                        $count = ($key == 'body_parts') ? $parts_count[$v['id']] : $items[$v['id']];
                        $summ += $count;
                        $line .= '<a data-category="' . $v['shortname'] . '" class="subcat">' . $v['name'] . ' (' . intval($count) . ')</a>';
                    }
                    ?>
                    <a class="slide" data-category="<?=$val['shortname'] ?>" href="javascript: void(0)"><?= $val['name'] ?> (<?= $summ ?>) <small></small></a>
                    <p class="hide" style="padding-left: 20px">
                        <?
                        echo $line;
                        ?></p><?
        } else {
            $count = ($key == 'body_parts') ? $parts_count[$val['id']] : $items[$val['id']];
                        ?>
                    <a data-category="<?= $val['shortname'] ?>"><?= $val['name'] ?> (<?= intval($count) ?>)</a>
                    <?
                }
                ?>
            </div>
            <?
        }
    }
    ?>
    <input type="hidden" name="menu_active" value="<?= $parent_active ?>">
</div><br>
<a href="/dressup/edit_dressup" class="button">Saved dressups</a>
<a href="/dressup/outfits" class="button">Saved outfits</a>

