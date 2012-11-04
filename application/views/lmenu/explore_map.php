<?
$active = $this->uri->segment(1);
?>
<input type="hidden" name="map_active" value="<?= $active_tab ?>">

<h2 style="margin-left: 20px">Map</h2>
<div id="maps">
    <h3>Marketplace</h3>
    <div>
        <p>
            <a href="/users_shop" <? if($active =='users_shop')echo'class="active"'?>>Users shop</a><br>
            <a href="/shops" <? if($active =='shops')echo'class="active"'?>>Perfectlook shops</a><br>
            <a href="/userauction" <? if($active =='userauction')echo'class="active"'?>>Userauctions</a><br>
            <a href="/auction" <? if($active =='auction')echo'class="active"'?>>Perfectlook auctions</a><br>
            <a href="/buy_jewels" <? if($active =='buy_jewels')echo'class="active"'?>>Buy jewels</a><br>
            <a href="/bank" <? if($active =='bank')echo'class="active"'?>>Bank (exchange buttons, jewels)</a><br>
        </p>
    </div>
    <h3>Brands</h3>
    <div>
        <p>
            <a href="/brands" <? if($active =='brands')echo'class="active"'?>>Brands Browser</a><br>
        </p>
    </div>
    <h3>The hangout</h3>
    <div>
        <p>
            <a href="/find_friends" <? if($active =='find_friends')echo'class="active"'?>>Find friends</a><br>
            <a href="/myfriends" <? if($active =='myfriends')echo'class="active"'?>>Your friends</a><br>
            <a href="/invite_friends" <? if($active =='invite_friends')echo'class="active"'?>>Invite friends</a><br>
            <a href="/send_free_gifts" <? if($active =='send_free_gifts')echo'class="active"'?>>Send free gifts</a><br>
            <!--<a href="/perfectlook_boards" <? if($active =='perfectlook_boards')echo'class="active"'?>>Perfectlook boards</a><br>-->
            <!--<a href="/chat" <? if($active =='chat')echo'class="active"'?>>Live chat</a><br>-->
            <a href="/my_gifts" <? if($active =='my_gifts')echo'class="active"'?>>Received Gifts</a><br>

        </p>
    </div>
    <h3>Latest Updates</h3>
    <div>
        <p>
            <a href="/latest_photos" <? if($active =='latest_photos')echo'class="active"'?>>Latest uploaded photos</a><br>
            <a href="/latest_dressups" <? if($active =='latest_dressups')echo'class="active"'?>>Latest dressups</a><br>
        </p>
    </div>
    <h3>Popular</h3>
    <div>
        <p>
            <a href="/top_dressups" <? if($active =='top_dressups')echo'class="active"'?>>top 10 dressups</a><br>
            <a href="/top_photos" <? if($active =='top_photos')echo'class="active"'?>>top 10 photos</a><br>
        </p>
    </div>
    <h3>Featured</h3>
    <div>
        <p>
            <a href="/featured_photos" <? if($active =='featured_photos')echo'class="active"'?>>featured photos</a><br>
            <a href="/featured_dressups" <? if($active =='featured_dressups')echo'class="active"'?>>featured dressups</a><br>
        </p>
    </div>
    <h3>Fashion Fun</h3>
    <div>
        <p>
            <a href="#">style guide</a><br>
        </p>
    </div>
    <h3>Search</h3>
    <div>
        <p>
            <a href="/find_friends">Search Users</a><br>
        </p>
    </div>
    <h3>Help</h3>
    <div>
        <p>
            <a href="/bugwall">Bugs reporting</a><br>
            <a href="/tutorials">Beginner's Tutorial</a><br>
            <a href="#">How to earn more buttons</a><br>
            <a href="#">How to get jewels</a><br>
            <a href="#">FAQ</a><br>
        </p>
    </div>
    <br class="clear">
</div>
<br class="clear">