<script type="text/javascript" src="js/gradualfader.js">

    /***********************************************
     * Gradual Element Fader- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
     * Visit http://www.dynamicDrive.com for hundreds of DHTML scripts
     * This notice must stay intact for legal use
     ***********************************************/

</script>






<style type="text/css">

    div.clearer { 
        clear: both;  
        height: 0px; 
    } 



    #map {position:relative; margin:0px auto; width:680px; height:553px;}
    #map ul {padding:0; margin:0; list-style:none; position:absolute; top:0; left:0;}
    #map ul li {position:absolute; list-style: none;}
    #map ul li#exchange {width:130px; height:105px; top:50px; left:60px;}
    #map ul li#specialshops {width:180px; height:130px; top:10px; left:220px;}
    #map ul li#marketplace {width:170px; height:160px; top:40px; left:430px;}
    #map ul li#popular {width:172px; height:162px; top:165px; left:9px;}
    #map ul li#hangout {width:214px; height:193px; top:200px; left:320px;}
    #map ul li#brands {width:153px; height:130px; top:210px; left:527px;}
    #map ul li#fashionfun {width:194px; height:178px; top:366px; left:20px;}
    #map ul li#search {width:75px; height:60px; top:476px; left:480px;}
    #map ul li#bugwall {width:45px; height:50px; top:490px; left:560px;}
    #map ul li#help {width:55px; height:85px; top:477px; left:610px;}


    #map ul li a {text-decoration:none;}


    #map ul li a.tl {display:block; text-indent:-9999px; }


    #map ul li a.tl:hover, #map ul li:hover {z-index:500;}

    /* hover effects for each icon */
    /*
    #map ul li#sun a.tl {width:215px; height:210px;}
    #map ul li#sun a.tl:hover, #map ul li#sun:hover {background:url(planets/sun.jpg);}
    #map ul li#mercury a.tl {width:69px; height:70px;}
    #map ul li#mercury a.tl:hover, #map ul li#mercury:hover {background:url(planets/mercury.jpg);}
    #map ul li#venus a.tl {width:117px; height:119px;}
    #map ul li#venus a.tl:hover, #map ul li#venus:hover {background:url(planets/venus.jpg);}
    #map ul li#moon a.tl {width:104px; height:104px;}
    #map ul li#moon a.tl:hover, #map ul li#moon:hover {background:url(planets/moon.jpg);}
    #map ul li#earth a.tl {width:257px; height:257px;}
    #map ul li#earth a.tl:hover, #map ul li#earth:hover {background:url(planets/earth.jpg);}
    */

    #map ul li .xsnazzy,
    #map ul li a .xsnazzy
    {visibility:hidden;}

    /* For IE6 to work */
    * html #map ul li a.tl:hover {border:0;}

    * html #map ul li a:hover .xsnazzy {visibility:visible;}

    #map ul li:hover .xsnazzy {visibility:visible;}

    /* For IE7 to keep the :hover over .xsnazzy - goodness knows why? */
    #map ul li:hover .xsnazzy {background:url(http://www.cssplay.co.uk/menu/transparent.gif);}

    .xsnazzy {display:block; position:absolute; height:auto; text-align:center;}

    /* xsnazzy is the popup */
    #popular .xsnazzy {left:0px; top:0px; width:210px;}
    #bugwall .xsnazzy {left:-50px; top:-90px; }
    #hangout .xsnazzy {left:-50px; top:-100px;}
    #search .xsnazzy {left:-50px; top:-90px;}
    #help .xsnazzy {left:-100px; top:-220px;}



    .xsnazzy h1, .xsnazzy p {margin:0 10px;}
    .xsnazzy h1 {font-size:2.5em; color:#6f9; font-family:georgia, "times new roman", serif; border-bottom:1px solid #fff;}
    .xsnazzy p {padding-bottom:0.5em; color:#bf5d70; font-size:12px; text-align:left;}
    .xsnazzy {background: transparent; margin:1em;}

    .xb1, .xb2, .xb3, .xb4, .xb5, .xb6, .xb7 {display:block; font-size:0; overflow:hidden;}
    .xb1, .xb2, .xb3, .xb4, .xb5, .xb6 {height:1px;}
    .xb4, .xb5, .xb6, .xb7 {background:#ccc; border-left:1px solid #f0e155; border-right:1px solid #f0e155;}
    .xb1 {margin:0 8px; background:#f0e155;}
    .xb2 {margin:0 6px; background:#f0e155;}
    .xb3 {margin:0 4px; background:#f0e155;}
    .xb4 {margin:0 3px; background:#fddae6; border-width:0 5px; background: url(images/map-popupbg.png);}
    .xb5 {margin:0 2px; background:#fddae6; border-width:0 4px; background: url(images/map-popupbg.png);}
    .xb6 {margin:0 2px; background:#fddae6; border-width:0 3px; background: url(images/map-popupbg.png);} 
    .xb7 {margin:0 1px; background:#fddae6; border-width:0 3px; height:2px; background: url(images/map-popupbg.png);} 

    .xboxcontent {display:block; background: url(images/map-popupbg.png); border:3px solid #f0e155; border-width:0 3px; } 
    /*.xboxcontent {display:block; background: url(images/map-popupbg.png); border:3px solid #f0e155; border-width:0 3px; } */

    .xboxcontent a, .xboxcontent a:visited {display:block; color:#ff0; font-weight:bold; font-size:12px; text-indent:0; padding:4px;}

    #map ul li a:hover .xsnazzy .xboxcontent a:hover {color:#fff;}
    #map ul li:hover .xsnazzy .xboxcontent a:hover {color:#fff;}


    .xsnazzy em.point_top {display:block; font-size:0; width:25px; height:14px; background:url(planets/point.gif) center top; position:absolute; left:50px; top:-11px;}
    .xsnazzy em.point_bottom {display:block; font-size:0; width:25px; height:14px; background:url(planets/point.gif) center bottom; position:absolute; right:50px; bottom:-11px;}

    /* for IE5.5 */
    * html .xsnazzy em.point_bottom {bottom:-12px; bo\ttom:-11px;}
    .xsnazzy em.point_left {display:block; font-size:0; width:14px; height:25px; background:url(planets/point.gif) left center; position:absolute; left:-11px; top:30px;}
    .xsnazzy em.point_right {display:block; font-size:0; width:14px; height:25px; background:url(planets/point.gif) right center; position:absolute; right:-11px; top:125px;}

    /* for IE5.5 */
    * html .xsnazzy em.point_right {right:-12px; ri\ght:-11px;}

</style>




<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>



<div id="content">

    <div class="page_title">
        <span>Explore Map</span>
    </div>
    <div class="bg">

        <div id="explore_map">


            <div id="map">
                <img src="images/map.jpg" alt="perfect look world map" title="perfect look world map" />
                <ul>
                    <li id="exchange"><a href="#" class="tl">Exchange</a>
                        <div class="xsnazzy">
                            <em class="point_left"></em>
                            <b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b><b class="xb5"></b><b class="xb6"></b><b class="xb7"></b>
                            <div class="xboxcontent" class = "iconset">
                                <!-- [icons] -->

                                <div style="width: 200px; overflow: hidden;">
                                    <div style="float: left;"><a href="/buy_jewels"><img src = "images/Icons-BuyDiamonds.png" alt = "Buy Jewels" class="gradualfader"></a></div>
                                    <div style="float: left;"><a href="/bank"><img src = "images/Icons-Exchange.png" alt = "Exchange" class="gradualfader"></a></div>
                                </div>

                            </div>
                            <b class="xb7"></b><b class="xb6"></b><b class="xb5"></b><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b>
                        </div>
                    </li>


                    <li id="specialshops"><a href="#" class="tl">Official Shops</a>
                        <div class="xsnazzy">
                            <em class="point_right"></em>
                            <b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b><b class="xb5"></b><b class="xb6"></b><b class="xb7"></b>
                            <div class="xboxcontent">
                                <!-- [icons] -->
                                <div style="width: 200px; overflow: hidden;">
                                    <div style="float: left;"><a href="/shops"><img src = "images/Icons-PLShops.png" alt = "Perfectlook shops" class="gradualfader"></a></div>
                                    <div style="float: left;"><a href="/auction"><img src = "images/Icons-PLAuctions.png" alt = "Perfectlook auctions" class="gradualfader"></a></div>
                                </div>

                            </div>
                            <b class="xb7"></b><b class="xb6"></b><b class="xb5"></b><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b>
                        </div>
                    </li>



                    <li id="marketplace"><a href="#" class="tl">Marketplace</a>
                        <div class="xsnazzy">
                            <em class="point_right"></em>
                            <b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b><b class="xb5"></b><b class="xb6"></b><b class="xb7"></b>
                            <div class="xboxcontent">
                                <!-- [icons] -->

                                <div style="width: 200px; overflow: hidden;">
                                    <div style="float: left;"><a href="/users_shop"><img src = "images/Icons-UserShops.png" alt = "User Shops" class="gradualfader"></a></div>
                                    <div style="float: left;"><a href="/userauction"><img src = "images/Icons-UserAuctions.png" alt = "User Auctions" class="gradualfader"></a></div>
                                </div>


                            </div>
                            <b class="xb7"></b><b class="xb6"></b><b class="xb5"></b><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b>
                        </div>
                    </li>




                    <li id="popular"><a href="#" class="tl">Popular</a>
                        <div class="xsnazzy">
                            <em class="point_right"></em>
                            <b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b><b class="xb5"></b><b class="xb6"></b><b class="xb7"></b>
                            <div class="xboxcontent">
                                <!-- [icons] -->

                                <div style="width: 220px; overflow: hidden;">
                                    <div style="float: left;"><a href="/latest_photos" ><img src = "images/Icons-latestpics.png" alt = "Latest uploaded photos" class="gradualfader"><br></a></div>
                                    <div style="float: left;"><a href="/latest_dressups" ><img src = "images/Icons-latestdressupsl.png" alt = "Latest dressups" class="gradualfader"><br></a></div>
                                </div>

                                <div style="width: 220px; overflow: hidden;">
                                    <div style="float: left;"><a href="/top_dressups" ><img src = "images/Icons-Top10Dressups.png" class="gradualfader"><br></a></div>
                                    <div style="float: left;"><a href="/top_photos" ><img src = "images/Icons-Top10Photos.png" class="gradualfader"><br></a></div>
                                </div>

                                <div style="width: 220px; overflow: hidden;">
                                    <div style="float: left;"><a href="/featured_photos" ><img src = "images/Icons-FeaturedPhotos.png" class="gradualfader"><br></a></div>
                                    <div style="float: left;"><a href="/featured_dressups" ><img src = "images/Icons-FeaturedDressups.png" class="gradualfader"><br></a></div>
                                </div>

                            </div>
                            <b class="xb7"></b><b class="xb6"></b><b class="xb5"></b><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b>
                        </div>
                    </li>





                    <li id="hangout"><a href="#" class="tl">Hangout</a>
                        <div class="xsnazzy">
                            <em class="point_right"></em>
                            <b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b><b class="xb5"></b><b class="xb6"></b><b class="xb7"></b>
                            <div class="xboxcontent">
                                <!-- [icons] -->
                                <div style="width: 300px; overflow: hidden;">
                                    <div style="float: left;"><a href="/find_friends" ><img src = "images/Icons-FindFriends.png" alt = "Find friends" class="gradualfader"><br></a></div>
                                    <div style="float: left;"><a href="/myfriends" ><img src = "images/Icons-YourFriends.png" class="gradualfader"><br></a></div>
                                    <div style="float: left;"><a href="/invite_friends" ><img src = "images/Icons-InviteFriends.png" alt = "Invite friends" class="gradualfader"><br></a></div>
                                </div>

                                <div style="width: 300px; overflow: hidden;">
                                    <div style="float: left;"><a href="/send_free_gifts" ><img src = "images/Icons-SendFreeGifts.png" alt = "Send free gifts" class="gradualfader"><br></a></div>
                                    <div style="float: left;"><a href="/my_gifts" ><img src = "images/Icons-ReceivedGifts.png" class="gradualfader"><br></a></div>
                                </div>

                                <div style="width: 300px; overflow: hidden;">
                                    <div style="float: left;"><a href="/chat" ><img src = "images/Icons-LiveChat.png" alt = "Live Chat" class="gradualfader"><br></a></div>
                                    <div style="float: left;"><a href="/perfectlook_boards" ><img src = "images/Icons-PLBoards.png" alt = "PerfectLook Boards" class="gradualfader"><br></a></div>
                                </div>




                            </div>
                            <b class="xb7"></b><b class="xb6"></b><b class="xb5"></b><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b>
                        </div>
                    </li>




                    <li id="brands"><a href="#" class="tl">Brands</a>
                        <div class="xsnazzy">
                            <em class="point_right"></em>
                            <b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b><b class="xb5"></b><b class="xb6"></b><b class="xb7"></b>
                            <div class="xboxcontent">
                                <!-- [icons] -->
                                <div style="width: 100px; overflow: hidden;">
                                    <div style="float: left;"><a href="/brands" ><img src = "images/Icons-BrandsBrowser.png" class="gradualfader"><br></a></div>
                                </div>
                            </div>
                            <b class="xb7"></b><b class="xb6"></b><b class="xb5"></b><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b>
                        </div>
                    </li>


                    <li id="fashionfun"><a href="#" class="tl">Fashion Fun</a>
                        <div class="xsnazzy">
                            <em class="point_right"></em>
                            <b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b><b class="xb5"></b><b class="xb6"></b><b class="xb7"></b>
                            <div class="xboxcontent">
                                <!-- [icons] -->

                                <div style="width: 100px; overflow: hidden;">
                                    <div style="float: left;"><a href="#"><img src = "images/Icons-StyleGuide.png" class="gradualfader"></a></div>
                                </div>

                            </div>
                            <b class="xb7"></b><b class="xb6"></b><b class="xb5"></b><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b>
                        </div>
                    </li>




                    <li id="search"><a href="#" class="tl">Search</a>
                        <div class="xsnazzy">
                            <em class="point_right"></em>
                            <b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b><b class="xb5"></b><b class="xb6"></b><b class="xb7"></b>
                            <div class="xboxcontent">
                                <!-- [icons] -->
                                <div style="width: 100px; overflow: hidden;">
                                    <div style="float: left;">
                                        <a href="/find_friends"><img src = "images/Icons-FindFriends.png" class="gradualfader"></a></div>
                                </div>

                                <!-- site search coming soon -->

                            </div>
                            <b class="xb7"></b><b class="xb6"></b><b class="xb5"></b><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b>
                        </div>
                    </li>






                    <li id="bugwall"><a href="#" class="tl">Bug Wall</a>
                        <div class="xsnazzy">
                            <em class="point_right"></em>
                            <b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b><b class="xb5"></b><b class="xb6"></b><b class="xb7"></b>
                            <div class="xboxcontent">
                                <!-- [icons] -->
                                <div style="width: 90px; overflow: hidden;">
                                    <div style="float: left;"><a href="/bugwall"><img src = "images/map-bugwall.png" class="gradualfader"></a></div>
                                </div>


                            </div>
                            <b class="xb7"></b><b class="xb6"></b><b class="xb5"></b><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b>
                        </div>
                    </li>



                    <li id="help"><a href="#" class="tl">Help</a>
                        <div class="xsnazzy">
                            <em class="point_right"></em>
                            <b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b><b class="xb5"></b><b class="xb6"></b><b class="xb7"></b>
                            <div class="xboxcontent">


                                <div style="width: 200px; overflow: hidden;">
                                    <div style="float: left;"><a href="#comingsoon"><img src = "images/Icons-BeginnersTut.png" class="gradualfader"><br></a></div>
                                    <div style="float: left;"><a href="#comingsoon"><img src = "images/Icons-How2EarnBs.png" class="gradualfader"><br></a></div>
                                </div>

                                <div style="width: 200px; overflow: hidden;">
                                    <div style="float: left;"><a href="#comingsoon"><img src = "images/Icons-How2GetDs.png" class="gradualfader"><br></a></div>
                                    <div style="float: left;"><a href="#comingsoon"><img src = "images/Icons-FAQ.png" class="gradualfader"><br></a></div>
                                </div>


                            </div>
                            <b class="xb7"></b><b class="xb6"></b><b class="xb5"></b><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b>
                        </div>
                    </li>





                </ul>
            </div> <!-- end map -->





        </div>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>







<script type="text/javascript">
    gradualFader.init() //activate gradual fader
</script>

