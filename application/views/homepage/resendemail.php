<div class="resend_bg">
    <div class="resend_page">
        <form action="" method="post">
            <? 
              if (!empty($sended) || !empty($_GET['sended'])) {
              ?>
              <span>Activation link has been sent.</span>
              <?
              } else {
              if (!empty($err)) {
              ?><span class="err">*<?= $err ?></span><?
              }
              ?>
              <div><label <? if ($this->input->post('email')) echo'style="display:none"' ?>>Email or Username</label>
              <input type="text" autocomplete="off" name="email" value="<?= @$this->input->post('email') ?>">
              </div>
              <input type="submit" name="send" value="SEND" id="submit">
              <?
              } 
            ?>
        </form>
    </div>
</div>