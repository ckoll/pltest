<?php
error_reporting(1);

set_time_limit(0);

session_start();





/*

''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''

'   File:	                hotmail.php

'

'   Description:            This script Login you on http://mail.hot.com website with SSL using cURl/PHP.

'

'   Written by:             ikram khalid ikram52@gmail.com

'

'   Languages:              PHP + CURL

'

'   Date Written:            April 4, 2009

'

'   Version:            	V.3.2

'

'   Platform:               Windows 2000 / IIS / Netscape 7.1

'

'   Copyright:              http://curl.phptrack.com

'

''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''

*/	



if($_POST['login'])

{





	$php_userid = rawurlencode($_POST['login']);

	$php_password = rawurlencode($_POST['passwd']);

	

	//$cookie_file_path = "C:\Inetpub\wwwroot\sept2005\phptrack\curl\forum_help_codes\cookie.txt"; // Please set your Cookie File path
	
	//  cookieFolder should be created in your server where you want to run this applicaiton wtih 777 permission
	$BASE_DIR = "cookieFolder/";
	$folder  = time() ;               // folder name
	$dirPath = $BASE_DIR . $folder.'cookie.txt';   // folder path
	
	// print results





    $fp = fopen($dirPath,'w');	

	fclose($fp);
	
	
	
	
	  $cookie_file_path = $dirPath ; // Please set your Cookie File path
	
	
	
	

	

	

	$fp = fopen($cookie_file_path,'wb');	

	fclose($fp);

	

	$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7";

    $reffer = "http://mail.hotmail.com/";

		

//1. Get first login page to parse hash_u,hash_challenge



	$LOGINURL = "http://login.live.com/login.srf?id=2&vv=400&lc=1033";	

    $ch = curl_init(); 

    curl_setopt($ch, CURLOPT_URL,$LOGINURL);

	curl_setopt($ch, CURLOPT_USERAGENT, $agent);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);

	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

    $result = curl_exec ($ch);

    curl_close ($ch);			

 //	echo "<textarea rows=30 cols=130>".$result."</textarea>"; 	

   // echo $result;



	// 2- Post Login Data to Page https://login.hot.com/config/login?

	

	$LOGINURL = "http://login.live.com/login.srf?id=2&vv=400&lc=1033";

	$POSTFIELDS = 'PPStateXfer=1';

	

	$ch = curl_init(); 

    curl_setopt($ch, CURLOPT_URL,$LOGINURL);

	curl_setopt($ch, CURLOPT_USERAGENT, $agent);

    curl_setopt($ch, CURLOPT_POST, 1); 

    curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS); 

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

	curl_setopt($ch, CURLOPT_REFERER, $reffer);

	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);

	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

    $result = curl_exec ($ch);

    curl_close ($ch); 

	//echo "<textarea rows=30 cols=130>".$result."</textarea>"; 	

      // echo $result;

	preg_match_all("/action=\"(.*?)\"/", $result, $arr_post);	

	$url_login = $arr_post[1][0];

	

    preg_match_all("/name=\"PPFT\" id=\"(.*?)\"/",$result,$arr_post);

	$PPFT = urlencode($arr_post[1][0]);

	preg_match_all("/id=\"$PPFT\" value=\"(.*?)\"/",$result,$arr_post);

	$PPFT = urlencode($arr_post[1][0]);

	





// 2- Post Login Data to Page https://login.hot.com/config/login?

	$LOGINURL = $url_login;

	$POSTFIELDS='PPSX=PassportR&PwdPad=IfYouAreReadingThisYouHaveTooMu&login='.$php_userid.'&passwd='.$php_password.'&LoginOptions=3&PPFT='.$PPFT;

	

	$ch = curl_init(); 

    curl_setopt($ch, CURLOPT_URL,$LOGINURL);

	curl_setopt($ch, CURLOPT_USERAGENT, $agent);

    curl_setopt($ch, CURLOPT_POST, 1); 

    curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS); 

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

	curl_setopt($ch, CURLOPT_REFERER, $reffer);

	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);

	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

    $result = curl_exec ($ch);

    curl_close ($ch); 

	//echo "<textarea rows=30 cols=130>".$result."</textarea>"; 	

	preg_match_all("/replace\(\"(.*?)\"/", $result, $arr_post);	

	$url_login = $arr_post[1][0];

 //   print "\r\n<br> url:".$url_login;



	$ch = curl_init(); 

    curl_setopt($ch, CURLOPT_URL,$url_login);

	curl_setopt($ch, CURLOPT_USERAGENT, $agent);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

	curl_setopt($ch, CURLOPT_REFERER, $reffer);

	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);

	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

     $result = curl_exec ($ch);

    curl_close ($ch); 

	//echo "<textarea rows=30 cols=130>".$result."</textarea>"; 	
	preg_match_all("/src=\"(.*?)\"/", $result, $arr_postL);	
  $nextURL = $arr_postL[1][0];

		$nextURL = str_replace('&#58;',':',$nextURL);
		$nextURL = str_replace('&#63;','?',$nextURL);
		$nextURL = str_replace('&#38;','&',$nextURL);
		$nextURL = str_replace('&#61;','=',$nextURL);
  	$nextURL = str_replace('&#47;','/',$nextURL);
	
	$basUrlget = explode('/mail/',$nextURL);
	 $basUrl = $basUrlget[0];
	
   $reffer = $url_login;
////////////////  new hotmail /////////

$ch = curl_init(); 

    curl_setopt($ch, CURLOPT_URL,$nextURL);

	curl_setopt($ch, CURLOPT_USERAGENT, $agent);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

	curl_setopt($ch, CURLOPT_REFERER, $reffer);

	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);

	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

     $result = curl_exec ($ch);

    curl_close ($ch); 
  //echo "<textarea rows=30 cols=130>".$result."</textarea>"; 	
	//preg_match_all("/<a href=\"ContactMainLight.aspx\?n=(.*?)\" title/",$result,$valueofN);	
	preg_match_all("/<a href=\"ContactMainLight.aspx&#63;n&#61;(.*?)\" title/",$result,$valueofN);	

	
	
	 $url_login = $valueofN[1][0];

	

	//print $result;

	//print "\r\n<br> contact:".$url_login;





	



// 4- Get Address Book.

	//$addressURL = 'http://hotmail.msn.com/cgi-bin/addresses?'.$url_login;

	//$addressURL = 'http://mail.live.com/mail/ContactMainLight.aspx?n='.$url_login;
   $addressURL = $basUrl.'/mail/ContactMainLight.aspx?n='.$url_login;
	$ch = curl_init(); 

    curl_setopt($ch, CURLOPT_URL,$addressURL);

	curl_setopt($ch, CURLOPT_USERAGENT, $agent);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

	curl_setopt($ch, CURLOPT_REFERER, $reffer);

	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);

	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

    $result = curl_exec ($ch);


	

	$str =$result;
	$my_total_pages = 0;

	

	$contents1 = explode('<div id="MainContent">',$str);
	//echo "<textarea rows=30 cols=130>".$contents1[1]."</textarea>"; 	
	$contents2 =  explode('<table class="ItemListContentTable ContactList" cellspacing="0" cellpadding="0">',$contents1[1]);
	
	$contents3 =  explode('<div id="cxp_ic_usertiles" style="display:none">',$contents2[1]);
	
	$PageNavigation = explode('<div class="PageNavigation">',$contents3[1]);
	$PageNavigation1 = explode('<div id="RadAd_Skyscraper" class="BorderBox SkyscraperContainer"',$PageNavigation[1]);
	$PageNavigation1[0];
	
	$pgNumberdata =  ereg_replace("/\n\r|\r\n|\n|\r/", "", $PageNavigation1[0]);
	$pgNumberdata = preg_replace("/\t/", "", $pgNumberdata);
	$pgNumberdata = str_replace(" ", "", $pgNumberdata);
	
	preg_match_all("/>(.*?)<\/a><\/li>/", $pgNumberdata, $pgTotall);
	
	$my_total_pages =  strip_tags($pgTotall[1][1]);
	
	 preg_match_all("/class=\"cxp_ic_name cxp_ic_name_m\" target=\"_self\" href=\"ContactViewLight.aspx\?ContactID=(.*?)\" title=\"(.*?)\"/",$contents3[0], $arr_post2);

		
	$reffer = 'http://mail.live.com/mail/ContactMainLight.aspx?n='.$url_login;
	
	
		$isEmail = array();
		$counter =0;
		   foreach($arr_post2[2] as $key)
	       {
		   
		    $data1 = str_replace('&#64;','@',$key);
	        $data2 = str_replace('&#8203;','',$data1);

			
		    preg_match_all("/@/",$data2,$isEmail);
			
			 if(empty($isEmail[0]))
				{
				$returnMail = get_Email($arr_post2[1][$counter],$agent,$cookie_file_path,$reffer,$basUrl);
				if($returnMail == '')
				echo $data2.'@hotmail.com';
				else
				echo $returnMail;
				
				echo '<br/>';
				
				}else
				{
				
				echo $data2.'<br/>';
					
				}
			
			$counter++;
			
			} // for each end
			
			
			
			$reffer = 'http://mail.live.com/mail/ContactMainLight.aspx?n='.$url_login;
	
	for($i=2;$i<=$my_total_pages;$i++)
	{
	
	////////////////////  start navigation of rest of pages
	
		$addressURL =  $basUrl.'/mail/ContactMainLight.aspx?ContactsSortBy=FileAs&Page='.$i.'&n='.$url_login;
	
		$ch = curl_init(); 
	
		curl_setopt($ch, CURLOPT_URL,$addressURL);
	
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	
		curl_setopt($ch, CURLOPT_REFERER, $reffer);
	
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
	
		$result = curl_exec ($ch);
	
		curl_close ($ch); 
	
		
	
		$str =$result;
		
	
		//echo "<textarea rows=30 cols=130>".$result."</textarea>"; 	
	
		$contents1 = explode('<div id="MainContent">',$str);
		//echo "<textarea rows=30 cols=130>".$contents1[1]."</textarea>"; 	
		$contents2 =  explode('<table class="ItemListContentTable ContactList" cellspacing="0" cellpadding="0">',$contents1[1]);
		
		$contents3 =  explode('<div id="cxp_ic_usertiles" style="display:none">',$contents2[1]);
					
		preg_match_all("/http:\/\/shared.live.com\/Live.Mail';\">(.*?)<\/a>/",$contents3[0], $arr_post);
		
		 preg_match_all("/class=\"cxp_ic_name cxp_ic_name_m\" target=\"_self\" href=\"ContactViewLight.aspx\?ContactID=(.*?)\" title=\"(.*?)\"/",$contents3[0], $arr_post2);
		$counter =0;
		$isEmail = array();
		foreach($arr_post[1] as $key){
		
		$data1 = str_replace('&#64;','@',$key);
		$data2 = str_replace('&#8203;','',$data1);
		
		 preg_match_all("/@/",$data2,$isEmail);
			
		 if(empty($isEmail[0]))
			{
			$returnMail = get_Email($arr_post2[1][$counter],$agent,$cookie_file_path,$reffer,$basUrl);
			if($returnMail == '')
			echo $data2.'@hotmail.com';
			else
			echo $returnMail;
			
			echo '<br/>';
			
			}else
			{
			
			echo $data2.'<br/>';
				
			}
		
		
		
		
		$counter++;
			
		}
	
	
	
	
	} ///// end of for 

	
	     
	


	
				

	
		unlink($dirPath);




		

				

			

			   

				

		
		
		

		} // extra code 2 if

		

		else

		{

			login_form();

		}	
		
function get_Email($emailUrl,$agent,$cookie_file_path,$reffer,$basUrl)
{

////////////////////  start navigation of rest of pages
	
  	$addressURL = $basUrl.'/mail/ContactViewLight.aspx?ContactID='.$emailUrl;
	
		$ch = curl_init(); 
	
		curl_setopt($ch, CURLOPT_URL,$addressURL);
	
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	
		curl_setopt($ch, CURLOPT_REFERER, $reffer);
	
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
	
		$result = curl_exec ($ch);
	
		 
	
		// "<textarea rows=30 cols=130>".$result."</textarea>"; 	
	
	    $contents16 = explode('<div class="cCol1"><div class="cColInner">',$result);
		$contents7 = explode('</div></div>',$contents16[1]);
		 preg_match_all("/a href=\"EditMessageLight.aspx\?MailTo=(.*?)>(.*?)<\/a>/",$contents7[0],$newEmail);
		$retMail = $newEmail[2][0]; 
		
		if($retMail == '')
		{
		preg_match_all("/<td class=\"Value\" >(.*?)<\/td>/",$contents7[0],$newEmail2);	
		
		$retMail =  $newEmail2[1][0];
		
		}
		return $retMail;


}










////////////////////////////////////////////////////////////////////////////////////////////////

function login_form()

{



?>

<html>

<head>

<title>hot Mail</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">





</head>



<body>

Get Hotmail Email Address Contact List

<form method="post" action="hotmail.php" autocomplete=off name="login_form" >

<table id="yreglgtb" summary="form: login information">

					<tbody><tr>

						<th><label for="username">hotmail Email:</label></th>

						<td><input name="login" id="login" value="@hotmail.com" size="17" class="yreg_ipt" type="text"></td>

					</tr>

					<tr>

						<th><label for="passwd">Password:</label></th>

						<td><input name="passwd" id="passwd" value="" size="17" class="yreg_ipt" type="password"></td>

					</tr>	



    

				

				</tbody></table>

<input value="Sign In" type="submit">

</form>

<p>Curl.phptrack.com also offering offshore curl scripting services. Now you can contact us for any commercial, non commercial php curl scripts based on your demand, our offshore outsource curl php developer are highly skilled and providing best services round the world.</p>

                                    <p>You can send you costume curl scripts requirements at any time and get response within 12 hours. </p>

                                    <p>Please call us or drop us email on <a href="http://www.phptrack.com/contactus.php">contactus</a> 

                                      for detail inquiry about our offshore web 

                                      development services, tech expertise, professional 

                                      reference and portfolio</p>

                                    



</body>

</html>

<?

}

?>
