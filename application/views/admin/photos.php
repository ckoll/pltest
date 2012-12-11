<h2>Photos</h2><br>
<br class="clear">
<?
if (!empty($photos)) {
    ?><table width="100%" class="table">
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
                <td><img src="<?=getSquareUpload($val)?>" width="50px"></td>
                <td width="16">
                    <a href="/admincp/refresh_square_crop_image/?id=<?=$val['id']?>"><img src="/images/refresh.png"></a>
                </td>
            </tr>
            <?
        }
        ?></table><?
}else{
    ?><em>*No Photos</em><?
}
    ?>