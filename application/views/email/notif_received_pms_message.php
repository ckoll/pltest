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

                        <h2 style="color:#FF66CC;">You have received a new private message.</h2>
                        <table width="80%" cellspacing="5" cellpadding="0" border="0" align="center" style="font-size:12px; font-family:Arial">
                            <tbody>
                            <tr>
                                <td width="60">
                                    <a href="<?= base_url() ?><?=$user['username']?>">
                                        <img width="60" height="60" style="border:1px solid #ddd; padding:2px; margin:5px" src="<?= base_url() ?><? get_user_avatarlink($user['id']) ?>">
                                    </a>
                                </td>
                                <td>
                                    <p>
                                        <span style="font-weight: bold">
                                            <a style="color:#d08ab1" href="<?= base_url() ?><?=$user['username']?>"><?=$user['username']?></a> said: "<?=$text?>"
                                        </span>
                                    </p>
                                    <p>
                                        You can view the message and reply by clicking the following link:
                                        <?= base_url() ?>pms/<?=$id?>
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