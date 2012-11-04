<p class="center">
    <strong>Items used in this dressup</strong>
</p><br>
<div class="used_items">
    <?
    if (!empty($items)) {
        foreach ($items as $val) {
            ?>
            <div class="dressup_used_items">
                <?
                if($val['profileimage_dir']=='[default]'){
                    $val['profileimage_dir'] = 'profilepics';
                }
                ?>
                <img src="/files/items/<?= $val['directory'] ?>/<?= $val['profileimage_dir'] ?>/<?= $val['profileimage'] ?>"><br>
                <a href="/item/<?=$val['shortname']?>"><?= $val['item_name'] ?></a>
            </div>
            <?
        }
    }
    ?>
</div>
<? 
$uri = $this->uri->segment_array();
if(is_my_page($uri[1])){ ?>
<br>
<p class="center">
<a href="/dressup/dress/<?=$uri[3]?>">Open this dressup</a>
</p><br>
<? }?>
