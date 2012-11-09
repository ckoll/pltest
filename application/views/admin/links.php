<a href="/admincp/add_partner_link" class="button" style="float: right; width:200px">Add Partner Link</a>
<h2>Links</h2><br>
<br class="clear">
<?
if (!empty($links)) {
    ?><table width="100%" class="table">
        <tr class="title">
            <td>ID</td>
            <td>Title</td>
            <td>Link</td>
            <td width="100px">Actions</td>
        </tr>
        <?
        foreach ($links as $val) {
            ?>
            <tr>
                <td><?=$val['id']?></td>
                <td><?=$val['title']?></td>
                <td><?= base_url() ?>register?hash=<?=$val['hash']?></td>
                <td width="16">
                    <a href="/admincp/partner_link_users/?id=<?=$val['id']?>"><img src="/images/info.png"></a>
                    <a href="/admincp/edit_partner_link/?id=<?=$val['id']?>"><img src="/images/edit.png"></a>
                    <a href="/admincp/delete_partner_link/?id=<?=$val['id']?>"><img src="/images/del.gif"></a>
                </td>
            </tr>
            <?
        }
        ?></table><?
}else{
    ?><em>*No Links</em><?
}
    ?>