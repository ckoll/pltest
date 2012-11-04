<a href="/admincp/announcements/" class="button contacts_small" style="float: right">< Back</a>
<h2>Announcements</h2><br>
<form action="" method="post">
    <b>Title:</b><input type="text" name="title" value="<?=$news['title']?>"><br>
    <b>Date:</b><input type="text" class="datepiker" name="date" value="<?=$news['date']?>"><br>
    <b>Text:</b>
    <textarea name="text" class="tinymce"><?=$news['text']?></textarea><br>
    <b>&nbsp;</b><input type="submit" name="save" value="Save">
</form>