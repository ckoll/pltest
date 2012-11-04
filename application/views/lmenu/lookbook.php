<? $active = $this->uri->segment(3); ?>
<style>
    ul{
        margin: 0;
        padding:0;
    }
    ul li{
        list-style-type: none;
        margin:0;
        padding:0;
        
    }
    ul > li > ul > li{
        margin-left: 20px;
    }
    a.active{
        text-decoration: underline !important;
    }
</style>
<section class="lmenu_block">
    <p>My PerfectLook Book</p>
    <ul>
        <li>My photos</a>
            <ul>
                <li><a href="photos" <?if($active == "photos") echo 'class="active"'?>>All my photos</a></li>
                <li>My looks of the day</li>
            </ul>
        </li>
        <li>My dressups
            <ul>
                <li>Dressup Calendar</li>
            </ul>
        </li>
        <li>Favorites
            <ul>
                <li><a href="favorite_photos" <?if($active == "favorite_photos") echo 'class="active"'?>>Favorite Photos</a></li>
                <li><a href="favorite_dressups" <?if($active == "favorite_dressups") echo 'class="active"'?>>Favorite dressups</a></li>
                <li><a href="my_dressups_favorite" <?if($active == "my_dressups_favorite") echo 'class="active"'?>>My dressups that have been favorites</a></li>
                <li><a href="my_photos_favorite" <?if($active == "my_photos_favorite") echo 'class="active"'?>>My photos that have been favorites</a></li>
            </ul>
        </li>
        <li>Brands
            <ul>
                <li><a href="brands" <?if($active == "brands") echo 'class="active"'?>>My favorite brands</a></li>
            </ul>
        </li>
        <li>My Most hearted
            <ul>
                <li><a href="most_hearted_photos" <?if($active == "most_hearted_photos") echo 'class="active"'?>>My most hearted photos</a></li>
                <li><a href="most_hearted_dressups" <?if($active == "most_hearted_dressups") echo 'class="active"'?>>My most hearted dressups</a></li>
                <li><a href="most_commented_photos" <?if($active == "most_commented_photos") echo 'class="active"'?>>My most commented photos</a></li>
                <li><a href="most_commented_dressups" <?if($active == "most_commented_dressups") echo 'class="active"'?>>My most commented dressups</a></li>
                <li><a href="recent_photo_comments" <?if($active == "recent_photo_comments") echo 'class="active"'?>>Recent photo comments I've received</a></li>
                <li><a href="recent_dressup_comments" <?if($active == "recent_dressup_comments") echo 'class="active"'?>>Recent dressup comments I've received</a></li>
                <li><a href="recent_photo_likes" <?if($active == "recent_photo_likes") echo 'class="active"'?>>Recent photo hearts I've received</a></li>
                <li><a href="recent_dressup_likes" <?if($active == "recent_dressup_likes") echo 'class="active"'?>>Recent dressup hearts I've received</a></li>
            </ul>
        </li>
        <li>Friends
            <ul>
                <? if($this->user['id']==$this->data['user']['id']){ ?>
                    <li><a href="req_to_you_friends" <?if($active == "req_to_you_friends") echo 'class="active"'?>>Users requesting to become your friend <?=($count_req_to_you_friends>0)?'('.$count_req_to_you_friends.')':''?></a></li>
                    <li><a href="req_you_to_add_friend" <?if($active == "req_you_to_add_friend") echo 'class="active"'?>>Pending users you have requested to become friends with <?=($count_req_you_to_add_friend>0)?'('.$count_req_you_to_add_friend.')':''?></a></li>
                <? } ?>
                <li><a href="my_recently_added_friends" <?if($active == "my_recently_added_friends") echo 'class="active"'?>>My recently added friends</a></li>
                <li><a href="last_friends_dressups" <?if($active == "last_friends_dressups") echo 'class="active"'?>>My friend's latest dressups</a></li>
                <li><a href="last_friends_photos" <?if($active == "last_friends_photos") echo 'class="active"'?>>My friend's latest photos</a></li>
                <li>My friend's latest looks of the day</li>
                <li><a href="recent_photos_i_likes" <?if($active == "recent_photos_i_likes") echo 'class="active"'?>>Recent photos I've hearted</a></li>
                <li><a href="recent_photos_i_commented" <?if($active == "recent_photos_i_commented") echo 'class="active"'?>>Recent photos I've commented on</a></li>
                <li><a href="recent_dressup_i_likes" <?if($active == "recent_dressup_i_likes") echo 'class="active"'?>>Recent dressups I've hearted</a></li>
                <li><a href="recent_dressup_i_commented" <?if($active == "recent_dressup_i_commented") echo 'class="active"'?>>Recent dressups I've commented on</a></li>
            </ul>
        </li>
        <? if($this->user['id']==$this->data['user']['id']){ ?>
        <li>My Buttons and Diamonds
            <ul>
                <li><a href="button_history" <?if($active == "button_history") echo 'class="active"'?>>Button history</a></li>
                <li><a href="diamonds_history" <?if($active == "diamonds_history") echo 'class="active"'?>>Diamonds history</a></li>
            </ul>
        </li>   
         <? } ?>
        <li>My items
            <ul>
                <li><a href="my_inventory">My item inventory</a></li>
                <li><a href="/users_shop/<?=$this->data['user']['username']?>">Items in my shop</a></li>
                <li><a href="/userauction/<?=$this->data['user']['username']?>">Items in my auction</a></li>
            </ul>
        </li>   
        <? if($this->user['id']==$this->data['user']['id']){ ?>
        <li>Gifts
            <ul>
                <li><a href="gifts_i_received" <?if($active == "gifts_i_received") echo 'class="active"'?>>Recent gifts I've received</a></li>
                <li><a href="gifts_i_sent" <?if($active == "gifts_i_sent") echo 'class="active"'?>>Recent gifts I've sent</a></li>
            </ul>
        </li>
         <? } ?>
    </ul>
</section>
