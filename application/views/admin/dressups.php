<h2>Dressups</h2><br>
<br class="clear">
<?
if (!empty($dressups)) {
    ?>
<?php if ($page > 1): ?>
    <a href="/admincp/dressups?page=<?=$page - 1?>">&lsaquo;&lsaquo;Prev</a>
    <?php endif; ?>

Page <?= $page ?>

<a href="/admincp/dressups?page=<?=$page + 1?>">Next&rsaquo;&rsaquo;</a>
<form method="post" action="/admincp/mass_refresh_square_crop_dressup?page=<?= $page ?>">
<input type="submit" value="Regenerate selected">
<table width="100%" class="table">
    <tr class="title">
        <td><input type="checkbox" id="all"></td>
        <td>ID</td>
        <td>image</td>
        <td width="100px">Actions</td>
    </tr>
    <?
    foreach ($dressups as $val) {
        ?>
        <tr>
            <td><input type="checkbox" name="dressup[<?=$val['id']?>]" value="1"></td>
            <td><?=$val['id']?></td>
            <td><img src="<?=getSquareDressup($val)?>?<?=time()?>" width="100px"></td>
            <td width="16">
                <a href="/admincp/refresh_square_crop_dressup/?id=<?=$val['id']?>&page=<?= $page ?>"><img src="/images/refresh.png"></a>
                <a href="/<?=$dressup['username']?>/dressup/<?=$dressup['id']?>"><img src="/images/goto.png"></a>
            </td>
        </tr>
        <?
    }
    ?></table>
    <input type="submit" value="Regenerate selected">
</form>
<?php if ($page > 1): ?>
    <a href="/admincp/dressups?page=<?=$page - 1?>">&lsaquo;&lsaquo;Prev</a>
    <?php endif; ?>

Page <?= $page ?>

<a href="/admincp/dressups?page=<?=$page + 1?>">Next&rsaquo;&rsaquo;</a>

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
    ?><em>*No dressups</em><?
}
?>