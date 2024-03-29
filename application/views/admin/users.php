<?
if (!empty($users)) {
    echo '<b>Total users: ' . count($users) . '</b>';

    ?>
<table width="100%" class="table">
    <tr class="title">
        <td>ID</td>
        <td>Username</td>
        <td>Email</td>
        <td>Registered</td>
        <td>Reg. IP</td>
        <td>Last Action</td>
        <td>L.A. IP</td>
        <td>Confirmed</td>
        <td>Referring</td>
        <td>Buttons/Jewels</td>
        <td></td>
    </tr>
    <? foreach ($users as $val) : ?>
    <?php
    $ref = $val['rusername'] ? $val['rusername'] : ($val['hash'] ? '<a href="'.base_url().'register?hash='.$val['hash'].'">#'.$val['hash'].'</a>' : '');
    ?>
    <tr>
        <td><?=$val['id']?></td>
        <td><?=$val['username']?></td>
        <td><?=(empty($val['email'])) ? '-' : $val['email']?></td>
        <td><?=$val['reg_date']?></td>
        <td><?=$val['reg_ip']?></td>
        <td><?=$val['last_action']?></td>
        <td><?=$val['last_action_ip']?></td>
        <td><?=$val['active'] ? 'Yes' : 'No'?></td>
        <td><?=$ref?></td>
        <td><?=$val['buttons']?>/<?=$val['jewels']?></td>
        <td width="16"><a href="/admincp/users/?del=<?=$val['id']?>"><img src="/images/del.gif"></a></td>
    </tr>
    <? endforeach; ?>
</table>
<?
}
?>