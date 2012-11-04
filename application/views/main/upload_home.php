<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span>Upload and share fashion photos!</span>
    </div>
    <div class="bg">
        <p class="message">Get 10 buttons for every photo that you share, and 1 button for every like or comment that you receive on any of your photos!</p>
        <form action="" method="post" enctype="multipart/form-data">
            <strong>Upload from computer</strong><br>
            <input type="file" name="photo"><br>
            <input type="submit" value="Send!">
        </form>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>