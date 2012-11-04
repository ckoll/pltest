<a href="/admincp/help/" class="button contacts_small" style="float: right">< Back</a>
<h2>Helps</h2><br>
<form action="" enctype="multipart/form-data" method="post">
    <b>Title:</b><input type="text" name="title" value="<?=$helps['title']?>"><br>
    <b>Text:</b>
    <textarea name="text" class="tinymce"><?=$helps['text']?></textarea><br>
    
    
    <b>&nbsp;</b><input type="submit" name="save" value="Save">
</form>