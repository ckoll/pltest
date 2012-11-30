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

                        <table width="80%" border="0" align="center" cellpadding="0" cellspacing="5" style="font-size:12px; font-family:Arial">
                            <tr>
                                <td width="60"><a href="<?= base_url() ?><?=$user['username']?>"><img src="<?= base_url() ?><? get_user_avatarlink($user['id']) ?>" width="60" height="60" style="border:1px solid #ddd; padding:2px; margin:5px" /></a></td>
                                <td><p><span style="font-weight: bold"><a href="<?= base_url() ?><?=$user['username']?>"style="color:#d08ab1"><?=$user['username']?></a> has invited you to Join Perfect-Look.org </span></p>
                                    <p><a href="<?= base_url() ?>register/?invite=<?=$user['id']?>" style="color:#d08ab1">Perfect-look.org</a> is
                                        a fun fashion site where you can share and tag fashion photos and
                                        create perfect look dressup dolls. Collect buttons by sharing photos
                                        and dressing up, <a href="<?= base_url() ?>register/?invite=<?=$user['id']?>" style="color:#d08ab1">join and start now!</a></p></td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <?=isset($add_message)?$add_message:''?>
                                </td>
                            </tr>

                        </table>
                        <p align="center">&nbsp;</p>
                        <p align="center"><a href="http://perfect-look.org/"><img src="<?= base_url() ?>images/Back-to-Page.jpg" width="200" height="60" border="0" /></a></p>


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