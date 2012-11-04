<a href="/admin/brands/?add=1" class="button" style="float: right; width:200px">Add Brand</a>
<h2>Brands</h2><br>
<br class="clear">
<?
if (!empty($brands)) {
    ?><table width="100%" class="table">
        <tr class="title">
            <td width="60">Photo</td>
            <td>Title</td>
            <td width="40"></td>
        </tr>
        <?
        foreach ($brands as $val) {
            ?>
            <tr>
                <td><img width="60" src="/files/brands/<?=$val['imagename']?>"></td>
                <td style="vertical-align: middle;"><?=$val['title']?></td>
                <td style="vertical-align: middle;" width="16">
                    <a href="/admincp/brands/?edit=<?=$val['id']?>"><img src="/images/edit.png"></a>
                    <a href="/admincp/brands/?del=<?=$val['id']?>"><img src="/images/del.gif"></a>
                </td>
            </tr>
            <?
        }
        
        if($pages>1){
        ?>
            <tr><td colspan="3"><? pagination($pages) ?></td></tr>
        <? } ?>
        </table><?
}else{
    ?><em>*No Brands</em><?
}
    ?>