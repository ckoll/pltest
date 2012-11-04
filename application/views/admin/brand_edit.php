<a href="/admin/brands/" class="button contacts_small" style="float: right">< Back</a>
<h2>Brands</h2><br>
<form action="" enctype="multipart/form-data" method="post">
    <b>Title:</b><input type="text" name="title" value="<?=$brands['title']?>"><br>
    <b>Description:</b>
    <textarea name="description" class="tinymce"><?=$brands['description']?></textarea><br>
    <?
        if(file_exists(APPPATH.'/files/brands/'.$brands['id'].'.jpg')){
            ?>
    <b>&nbsp;</b>&nbsp;<img width="60" src="/files/brands/<?=$brands['id']?>.jpg"><br><br>
            <?
        }
    ?>
    <b>Photo</b><input type="file" name="photo"><br>
    
    <b>&nbsp;</b><input type="submit" name="save" value="Save">
</form>