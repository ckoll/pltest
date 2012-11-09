<a href="/admincp/links/" class="button contacts_small" style="float: right">< Back</a>
<h2>Link Users</h2><br>
<br class="clear">
<?
if (!empty($users)) {
    ?><table width="100%" class="table">
        <tr class="title">
            <td>Link Title</td>
            <td>User</td>
            <td>Date</td>
        </tr>
        <?
        foreach ($users as $val) {
            ?>
            <tr>
                <td><?=$val['title']?></td>
                <td><?=$val['username']?></td>
                <td><?=$val['date']?></td>
            </tr>
            <?
        }
        ?></table><?
}else{
    ?><em>*No Links</em><?
}
    ?>