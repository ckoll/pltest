<a href="/admincp/help/?add=1" class="button" style="float: right; width:200px">Add Help</a>
<h2>Help</h2><br>
<br class="clear">
<?
if (!empty($helps)) {
    ?><table width="100%" class="table">
        <tr class="title">
            <td width="200">Title</td>
            <td>Text</td>
            <td width="40"></td>
        </tr>
        <?
        foreach ($helps as $val) {
            ?>
            <tr>
                <td style="vertical-align: middle;"><?=$val['title']?></td>
                <td style="vertical-align: middle;"><?=$val['text']?></td>
                <td style="vertical-align: middle;" width="16">
                    <a href="/admincp/help/?edit=<?=$val['id']?>"><img src="/images/edit.png"></a>
                    <a href="/admincp/help/?del=<?=$val['id']?>"><img src="/images/del.gif"></a>
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
    ?><em>*No Helps</em><?
}
    ?>