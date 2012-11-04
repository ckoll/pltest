<form action="" method="post" enctype="multipart/form-data">
    <?
    if(!empty($updated)){
        ?>
    <div class="message">Dressup items updated</div>    
        <?
    }
    ?>
    
    Select a file: <br>
    <input type="file" name="file"> <input type="submit" value="Update data">
</form>