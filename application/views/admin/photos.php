<h2>Photos</h2><br>
<br class="clear">
<?
if (!empty($photos)) {
    ?>
<?php if ($page > 1): ?>
    <a href="/admincp/photos?page=<?=$page - 1?>">&lsaquo;&lsaquo;Prev</a>
    <?php endif; ?>

Page <?= $page ?>

<a href="/admincp/photos?page=<?=$page + 1?>">Next&rsaquo;&rsaquo;</a>
<form method="post" action="/admincp/mass_refresh_square_crop_image?page=<?= $page ?>">
<input type="submit" value="Regenerate selected">
<table width="100%" class="table">
    <tr class="title">
        <td><input type="checkbox" id="all"></td>
        <td>ID</td>
        <td>image</td>
        <td width="100px">Actions</td>
    </tr>
    <?
    foreach ($photos as $val) {
        ?>
        <tr>
            <td><input type="checkbox" name="photo[<?=$val['id']?>]" value="1"></td>
            <td><?=$val['id']?></td>
            <td><img src="<?=getSquareUpload($val)?>?<?=time()?>" width="100px"></td>
            <td width="16">
                <a href="/admincp/refresh_square_crop_image/?id=<?=$val['id']?>&page=<?= $page ?>"><img src="/images/refresh.png"></a>
            </td>
        </tr>
        <?
    }
    ?></table>
    <input type="submit" value="Regenerate selected">
</form>
<?php if ($page > 1): ?>
    <a href="/admincp/photos?page=<?=$page - 1?>">&lsaquo;&lsaquo;Prev</a>
    <?php endif; ?>

Page <?= $page ?>

<a href="/admincp/photos?page=<?=$page + 1?>">Next&rsaquo;&rsaquo;</a>

<script type="text/javascript">
    $('#all').click(function(){
        if ($(this).is(':checked')) {
            $('input[type=checkbox]').attr('checked', 'checked');
        } else {
            $('input[type=checkbox]').attr('checked', false);
        }
    });
</script>


<?
} else {
    ?><em>*No Photos</em><?
}
?>