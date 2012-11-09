<a href="/admincp/links/" class="button contacts_small" style="float: right">< Back</a>
<h2>Links</h2><br>
 <?=$message?>
<form action="" method="post">
    <b>Title:</b><input type="text" name="title" value="<?=isset($link['title'])?$link['title']:''?>"><br>
    <b>Hash:</b><input type="text" name="hash" value="<?=isset($link['hash'])?$link['hash']:''?>"><br>
    <b>&nbsp;</b><input type="submit" name="save" value="Save">
</form>