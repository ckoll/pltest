<h2>Photos</h2><br>
<br class="clear">
<?
if (!empty($photos)) {
    ?>
<?php if ($page - 1): ?>
    <a href="/admincp/photos?page=<?=$pag - 1?>">&lsaquo;&lsaquo;Prev</a>
    <?php endif; ?>

Page <?= $page ?>

<a href="/admincp/photos?page=<?=$page + 1?>">Next&rsaquo;&rsaquo;</a>

<table width="100%" class="table">
    <tr class="title">
        <td>ID</td>
        <td>image</td>
        <td width="100px">Actions</td>
    </tr>
    <?
    foreach ($photos as $val) {
        ?>
        <tr>
            <td><?=$val['id']?></td>
            <td><img src="<?=getSquareUpload($val)?>?<?=time()?>" width="100px"></td>
            <td width="16">
                <a href="/admincp/refresh_square_crop_image/?id=<?=$val['id']?>&page=<?= $page ?>"><img src="/images/refresh.png"></a>
            </td>
        </tr>
        <?
    }
    ?></table>
<?php if ($page > 1): ?>
    <a href="/admincp/photos?page=<?=$page - 1?>">&lsaquo;&lsaquo;Prev</a>
    <?php endif; ?>

Page <?= $page ?>

<a href="/admincp/photos?page=<?=$page + 1?>">Next&rsaquo;&rsaquo;</a>


<?
} else {
    ?><em>*No Photos</em><?
}
?>