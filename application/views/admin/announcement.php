<a href="/admincp/announcements/?add=1" class="button" style="float: right; width:200px">Add Announcement</a>
<h2>Announcements</h2><br>
<br class="clear">
<?
if (!empty($news)) {
    ?><table width="100%" class="table">
        <tr class="title">
            <td>Title</td>
            <td width="140">Date</td>
            <td width="40"></td>
        </tr>
        <?
        foreach ($news as $val) {
            ?>
            <tr>
                <td><?=$val['title']?></td>
                <td><?=$val['date']?></td>
                <td width="16">
                    <a href="/admincp/announcements/?edit=<?=$val['id']?>"><img src="/images/edit.png"></a>
                    <a href="/admincp/announcements/?del=<?=$val['id']?>"><img src="/images/del.gif"></a>
                </td>
            </tr>
            <?
        }
        ?></table><?
}else{
    ?><em>*No Announcements</em><?
}
    ?>