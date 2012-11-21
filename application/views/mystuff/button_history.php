<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
</div>

<div id="content">
    <div class="page_title">
        <span>Mystuff -> <?= ($this->uri->segment(2) == 'button_history' || $this->uri->segment(3) == 'button_history') ? 'Buttons' : 'Diamonds' ?> History</span>
    </div>
    <div class="bg" style="text-align: center;">

        <?
        if (!empty($history)) {
            if ($this->uri->segment(2) == 'button_history' || $this->uri->segment(3) == 'button_history') {

                foreach ($history as $hist) {
                    switch ($hist['action']) {
                        case 'exchange_to_buttons':
                            $line .= "<tr><td>+ " . ($hist['now_buttons'] - $hist['buttons']) . " buttons</td><td>" . $hist['now_buttons'] . "</td><td>exchange from diamonds</td><td>" . $hist['date'] . "</td></tr>";
                            break;
                        case 'exchange_to_jewels':
                            $line .= "<tr><td>- " . ($hist['buttons'] - $hist['now_buttons']) . " buttons</td><td>" . $hist['now_buttons'] . "</td><td>exchange to diamonds</td><td>" . $hist['date'] . "</td></tr>";
                            break;
                        case 'invite_friends': case 'add_bug': case 'first_bonus': case 'sold_item': case 'sell_option1': case 'upload_bonus': case 'photo_like': case 'new_dressup':
                            $line .= "<tr><td>+ " . ($hist['now_buttons'] - $hist['buttons']) . " buttons</td><td>" . $hist['now_buttons'] . "</td><td>" . $hist['description'] . "</td><td>" . $hist['date'] . "</td></tr>";
                            break;
                        case 'buy_user_item':
                            $line .= "<tr><td>- " . ($hist['buttons'] - $hist['now_buttons']) . " buttons</td><td>" . $hist['now_buttons'] . "</td><td>" . $hist['description'] . "</td><td>" . $hist['date'] . "</td></tr>";
                            break;
                        case 'buy_item': case 'buy_auction':
                            if ($hist['buttons'] != $hist['now_buttons']) {
                                $line .= "<tr><td>- " . ($hist['buttons'] - $hist['now_buttons']) . " buttons</td><td>" . $hist['now_buttons'] . "</td><td>" . $hist['description'] . "</td><td>" . $hist['date'] . "</td></tr>";
                            }
                            break;
                        case 'sold_auction':
                            if ($hist['buttons'] != $hist['now_buttons']) {
                                $line .= "<tr><td>+ " . ($hist['now_buttons'] - $hist['buttons']) . " buttons</td><td>" . $hist['now_buttons'] . "</td><td>" . $hist['description'] . "</td><td>" . $hist['date'] . "</td></tr>";
                            }
                            break;
                        case 'photo_comment':
                            if ($hist['buttons'] != $hist['now_buttons']) {
                                $line .= "<tr><td>+ " . ($hist['now_buttons'] - $hist['buttons']) . " buttons</td><td>" . $hist['now_buttons'] . "</td><td>" . $hist['description'] . "</td><td>" . $hist['date'] . "</td></tr>";
                            }
                            break;
                        case 'friend_add':
                            if ($hist['buttons'] != $hist['now_buttons']) {
                                $line .= "<tr><td>+ " . ($hist['now_buttons'] - $hist['buttons']) . " buttons</td><td>" . $hist['now_buttons'] . "</td><td>" . $hist['description'] . "</td><td>" . $hist['date'] . "</td></tr>";
                            }
                            break;
                    }
                }
            } elseif ($this->uri->segment(2) == 'diamonds_history' || $this->uri->segment(3) == 'diamonds_history') {

                foreach ($history as $hist) {
                    switch ($hist['action']) {
                        case 'exchange_to_buttons':
                            $line .= "<tr><td>- " . ($hist['jewels'] - $hist['now_jewels']) . " diamonds</td><td>" . $hist['now_jewels'] . "</td><td>exchange to buttons</td><td>" . $hist['date'] . "</td></tr>";
                            break;
                        case 'exchange_to_jewels':
                            $line .= "<tr><td>+ " . ($hist['now_jewels'] - $hist['jewels']) . " diamonds</td><td>" . $hist['now_jewels'] . "</td><td>exchange from buttons</td><td>" . $hist['date'] . "</td></tr>";
                            break;
                        case 'order_jewels':
                            $line .= "<tr><td>+ " . ($hist['now_jewels'] - $hist['jewels']) . " diamonds</td><td>" . $hist['now_jewels'] . "</td><td>" . $hist['description'] . "</td><td>" . $hist['date'] . "</td></tr>";
                            break;
                        case 'buy_item': case 'buy_auction':
                            if ($hist['jewels'] != $hist['now_jewels']) {
                                $line .= "<tr><td>- " . ($hist['jewels'] - $hist['now_jewels']) . " diamonds</td><td>" . $hist['now_jewels'] . "</td><td>" . $hist['description'] . "</td><td>" . $hist['date'] . "</td></tr>";
                            }
                            break;
                        case 'sold_auction':
                            if ($hist['jewels'] != $hist['now_jewels']) {
                                $line .= "<tr><td>+ " . ($hist['now_jewels'] - $hist['jewels']) . " buttons</td><td>" . $hist['now_jewels'] . "</td><td>" . $hist['description'] . "</td><td>" . $hist['date'] . "</td></tr>";
                            }
                            break;
                    }
                }
            }
        }

        if (!empty($line)) {
            ?>
            <table class="history">
                <tr class="title">
                    <td></td>
                    <td>New count</td>
                    <td>Action</td>
                    <td>Date</td>
                </tr>

                <?= $line ?>

                <tr><td colspan="4"><? pagination($pages) ?></td></tr>
            </table>
            <?
        } else {
            ?><p><?
        echo ($this->uri->segment(2) == 'button_history' || $this->uri->segment(3) == 'button_history') ? 'Buttons' : 'Diamonds';
        echo ' history is empty.';
            ?></p><?
        }
        ?>
    </div>
    <div class="footer"></div>
</div>
