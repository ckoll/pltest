<table width="610" border="0" align="center" cellpadding="2" cellspacing="0">
    <tr>
        <td bgcolor="#f6eaf1">
            <table width="600" align="center" cellpadding="0" cellspacing="0" style="font-size:11px;">
                <tr>
                    <td style="line-height:0px">
                        <img src="<?= base_url() ?>images/Email_01.jpg" alt="Perfect-look.org" width="600" height="125" border="0">
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#FFFFFF" style="padding:5px">

                        <h2 style="color:#FF66CC;">You have received a comment on your dressup.</h2>
                        <table width="80%" cellspacing="5" cellpadding="0" border="0" align="center" style="font-size:12px; font-family:Arial">
                            <tbody>
                            <tr>

                                <td colspan="2">
                                    <div style="width: 285px">
                                        <div style='background: url("<?= base_url() ?>/images/like.png") no-repeat scroll left center transparent; float: left; height: 14px; margin-bottom: 7px; margin-top: 10px; padding-left: 20px; width: 30px;'><?=(int)$dressup['like']?></div>
                                        <div style='background: url("<?= base_url() ?>/images/comment.png") no-repeat scroll left center transparent;float: left;height: 14px;margin-bottom: 7px;margin-top: 10px;padding-left: 20px;text-decoration: none;width: 30px;'><?=(int)$dressup['comments']?></div>
                                        <div style="clear: both"></div>
                                        <div style="margin: 0 auto;width: 256px;">
                                            <a href="<?= base_url() ?><?=$dressup['username']?>/dressup/<?=$dressup['id']?>">
                                                <img src="<?= base_url() ?><?=getSquareDressup($dressup)?>">
                                            </a>
                                        </div>
                                        <div style="margin: 5px auto; width: 234px;"><?=$dressup['dress_comment']?></div>
                                        <?php if (isset($dressup['last3Comments'])) : ?>
                                        <?php foreach ($dressup['last3Comments'] as $lastcomment): ?>
                                            <div style="display: block;margin: 0 auto; width: 240px;">
                                                <a style="display: inline-block; float: left; margin-right: 7px; padding: 2px; width: 30px;"><img style="height: 30px; width: 30px;" src="<?= base_url() ?><? get_user_avatarlink($lastcomment['uid']) ?>"></a>
                                                <div style="border: 1px dashed #DDDDDD; float: left; margin: 2px; padding: 3px; width: 185px;">
                                                    <a class="user-name" href="<?= base_url() ?>/<?=$lastcomment['username']?>"><?=$lastcomment['username']?></a>
                                                    wrote on picture
                                                    "<?=$lastcomment['comment']?>"
                                                </div>
                                            </div>
                                            <div style="clear: both"></div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>

                            </tr>

                            <tr>
                                <td width="60">
                                    <a href="<?= base_url() ?><?=$user['username']?>">
                                        <img width="60" height="60" style="border:1px solid #ddd; padding:2px; margin:5px" src="<?= base_url() ?><? get_user_avatarlink($user['id']) ?>">
                                    </a>
                                </td>
                                <td>
                                    <p>
                                        <span style="font-weight: bold">
                                            <a style="color:#d08ab1" href="<?= base_url() ?><?=$user['username']?>"><?=$user['username']?></a> said: "<?=$comment?>"
                                        </span>
                                    </p>
                                    <p>
                                        Please
                                        <a style="color:#d08ab1" href="<?= base_url() ?>signin">login</a>
                                        to your account to <a style="color:#d08ab1" href="<?= base_url() ?><?=$dressup['username']?>/dressup/<?=$dressup['id']?>">reply</a>.

                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <p align="center">&nbsp;</p>
                        <p align="center">
                            <a href="http://perfect-look.org/">
                                <img width="200" border="0" height="60" src="<?= base_url() ?>images/Back-to-Page.jpg">
                            </a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td height="80" background="<?= base_url() ?>images/foot.jpg">
                        <div align="center">
                            <img src="<?= base_url() ?>images/facebook.png" width="16" height="16">
                            <a href="http://facebook.com/perfectlookorg" style="color: #d08ab1;">Facebook</a>
                            <img src="<?= base_url() ?>images/twitter.png" width="16" height="16">
                            <a href="http://twitter.com/perfectlookorg" style="color: #d08ab1;">Twitter</a>
                            <img src="<?= base_url() ?>images/email.png" width="16" height="16">
                            <a href="mailto:perfectlookorg@gmail.com" style="color: #d08ab1;">Email </a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>