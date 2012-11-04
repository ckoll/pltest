<h2>Gifts Management</h2><br>
<?
if (!empty($gifts)) {
    ?><table width="600" class="left gifts_table"><?
    $x = 0;
    foreach ($gifts as $val) {
        ?>
            <tr <? if ($x % 2 == 0) echo 'class="odd"' ?>>
                <td width="60"><img src="/files/gifts/<?= $val['id'] ?>.jpg" width="50"></td>
                <td><?= $val['name'] ?></td>
                <td><?= $val['price'] ?> buttons</td>
                <td width="20"><a href="/admin/gifts/?del=<?=$val['id']?>"><img src="/images/del.gif"></a></td>
            </tr>
            <?
            $x++;
        }
        ?></table><?
} else {
        ?><em>*No gifts</em><?
}
    ?>
<div class="right create">
    <form action="" method="post" enctype="multipart/form-data">
        <h3>Add Gift</h3>
        <span class="err" style="display: none"></span>
        <label>Image:</label>
        <input type="file" name="img"><br>
        <label>Name:</label>
        <input type="text" name="name"><br>
        <label>Price:</label><br>
        <input type="text" name="price" style="width: 140px"> buttons<br>
        <input type="submit" name="add_gift" value="Add">
    </form>
</div>