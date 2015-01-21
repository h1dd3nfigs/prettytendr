<?php
	require 'connect.inc.php';
?>


<!doctype html>
<html class="no-js" lang="en">
  <head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PrettyTendr | The Currency of Beauty</title>
    <script type="text/javascript" src="//use.typekit.net/tog3fnw.js"></script>
    <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
    <link href='http://fonts.googleapis.com/css?family=Six+Caps' rel='stylesheet' type='text/css'>    
    <link rel="stylesheet" href="css/normalize.css" />

    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/custom.css" />

    <!--<link rel="stylesheet" media="screen and (max-width: 40em)" href="custom.css" />-->
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
   
    <script src="js/vendor/modernizr.js"></script>
<!--Google Analytics-->
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-54604922-1', 'auto');
	  ga('send', 'pageview');

	</script>

     <link href="//vjs.zencdn.net/4.9/video-js.css" rel="stylesheet">
    <script src="//vjs.zencdn.net/4.9/video.js"></script>
   
  <style type="text/css">
.video-js {padding-top: 52%;
margin-left: -600px;}
.vjs-loading-spinner {
  display: none !important;}
}
  
 </style>
	
  </head>
<body>
<?php
	if(isset($_POST['email'])&& isset($_POST['firstname'])&&isset($_POST['lastname'])){ //check if form submitted
			$email = $_POST['email'];
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
	
		if(!empty($email)&&!empty($firstname)&&!empty($lastname)){ // check that all fields completed
			
			if(filter_var($email, FILTER_VALIDATE_EMAIL)){ //sanitize email input and check that it's a valid email address	
				$sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);
			
				if(strlen($sanitized_email)>40||strlen($sanitized_email)>40 || strlen($sanitized_email)>40){
					$tooLongErr = '*Please adhere to the max length of fields.';
					//echo '*Please adhere to the max length of fields.';
					} else {
			
							$query = "SELECT `email` FROM `signups` WHERE `email`='$sanitized_email'";
							$query_run = mysqli_query($link, $query);
							$query_num_rows = mysqli_num_rows($query_run);
	
							if($query_num_rows==1){
								$alreadySignedUp = '*The email \''.$sanitized_email. '\' is already on our mailing list.<br> No need to sign up again.';
								// echo '<p class="subtitle">*The email \''.$sanitized_email. '\' is already on our mailing list. No need to sign up again.</p>';	
							} else{					
								$query = "INSERT into `signups`(`id`, `email`, `firstname`, `lastname`) 
											VALUES ('','".mysqli_real_escape_string($link, $sanitized_email).
											"','".mysqli_real_escape_string($link, $firstname)."','".
											mysqli_real_escape_string($link, $lastname)."')";
								$query_run = mysqli_query($link, $query);
								if($query_run){
									$successMsg = 'Thanks for signing up! Still excited? 
                                    Keep scrolling to join us on social media.';
									// echo '<p class="subtitle" id="thankyou_message">Thanks for signing up!</p>';
								//	header('Location: register_success.php'); 
									//instead of header() redirecting to a new page,
									// hide form tags and show 'thanks for signing up!'
								} else {
									$cantJoinNowErr = 'Sorry we couldn\'t sign you up for our mailing list at this time. Please try again later.';
									//echo '<p class="subtitle"> Sorry we couldn\'t sign you up for our mailing list at this time. Please try again later.</p>';
								}
							}
	
				}

			} else{
				$validEmailErr = '*Please enter a valid email address.';
		//		echo '<p class="subtitle">Please enter a valid email address.</p>';
			}
	} else{
		$nameErr = '* All fields are required.';
		//echo '<p class="subtitle" > All fields are required.</p>';
	}
}	
?>

    <div class="intro-segment">
    <div class="wrapper">
          <div class="videocontent">
            <video id="hero-vid" class="video-js vjs-default-skin vjs-controls-disabled vjs-has-started vjs-user-inactive vjs-playing"
          autoplay preload="auto" width="auto" height="auto"
          poster="stylesheets/img/fallback_desktop.png" data-setup='{ "autoplay": true, "preload": "auto", "loop": "true", "fullscreen": "true"}'>
             <source src="http://www.prettytendr.com/wp-content/uploads/2014/09/PrettyTendr-Video_mute.mp4" type='video/mp4' />
             <source src="http://www.prettytendr.com/wp-content/uploads/2014/09/PrettyTendr_Video_mute.webm" type='video/webm' />
              </div>
               </div> 
            </video>

<div class="filter">         
        </div>

    <div class="fallback-img">
    </div>    


             
<div class="row">
    <div class="small-12 columns">
    <div class="intro-content"> 

     <div class="row">
        <div class="small-10 small-centered medium-8 large-6 large-centered columns">

            <img class="logo" src="http://www.prettytendr.com/wp-content/uploads/2014/08/ptlogo-01.png">
        </div> 
    </div>
    <div class="row">
        <div class="small-12 small-centered columns">
            <p class="headline">WELCOME TO YOUR UNLIMITED MAKEUP BAG</p>
            <p class="subtitle" id="description">simply write reviews to earn the most buzzworthy products, <span style="font-weight: bold;">for free!</span>
            <br>signup below to get notified when we launch</br>
            </p>
        </div>
    </div>

    <div class="hidef">
     
    <form id="target" action="index.php" method="POST">
        <div class="row">
            <!--<div class="small-5 <small-offset-1 large-offset-1 large-3 columns">!-->

            <div class="small-10 small-centered large-uncentered large-offset-1 large-3 columns">
                <input type="text" class="first-name" placeholder="First Name" name="firstname" maxlength="40" value="<?php if(isset($_POST['firstname'])){echo $firstname;}?>">
            
            </div>
            <div class="small-10 small-centered large-uncentered large-3 columns">
                <input type="text" class="last-name" placeholder="Last Name" name="lastname" maxlength="40" value="<?php if(isset($_POST['lastname'])){echo $lastname;}?>">
            
            </div>
            <div class=" small-10 small-centered large-4 large-uncentered columns">
                <input type="text" class="email" placeholder="Email Address" name="email" maxlength="40" value="<?php if(isset($_POST['email'])){echo $email;}?>" >
            </div>
        </div>    

    <div class="row">
        <div class="small-10 small-centered large-6 large-centered columns">
        

			<p style="font-family:ltc-bodoni-175, serif; font-style: italic ;" ><?php echo $cantJoinNowErr;?></p>
            <p style="text-align: center; font-family:ltc-bodoni-175, serif; font-style: italic ;" ><?php echo $nameErr;?></p>
            <p style="text-align: center; font-family:ltc-bodoni-175, serif; font-style: italic ;" ><?php echo $validEmailErr;?></p>
            <p style="font-family:ltc-bodoni-175, serif; font-style: italic ;" ><?php echo $tooLongErr;?></p>
          
            <input type="submit" class="button" value="GET NOTIFIED">
        
        </div>  
    </div>
  </form>
  <div class="row">
        <div class="small-10 small-centered columns">
   <div class="row myNewClass subtitle">
   </div>
   </div>
</div>
<?php if(isset($successMsg)||isset($alreadySignedUp)){ //run javascript if form's submitted successfully
?>
<script type="text/javascript">


$('form').hide(); 
$('.myNewClass').text( "Thanks for signing up! Still excited? Keep scrolling to join us on social media." ).show(); 
 
</script> 
<?php }?>
	
</div>
  

</div>

</div>
</div>
</div>
<!--   <div class="mySpan" ></div>-->
<!--</div>!-->
    <div class="howitworks-segment">    
        <div class="row">
            <div class="small-12 small-centered large-4  columns">
                <p class="headline" id="howitworks">HOW IT WORKS</p>
            </div>
        </div>
        <div class="row">
            <div class="small-10 small-offset-1 medium-4 medium-offset-0 large-4 large-offset-0 columns">
                <div class="eframe">
                    <h2 class="eheading">EARN</h2>
                    <div class="row">
                        <div class="small-12 small-centered columns">
                            <p class="etext">Earn PT by taking a quiz, writing a product review or referring a friend
                            </p>
                        </div>
                    </div>      
                    <div class="row">
                        <div class="small-6 small-centered medium-6 columns">
                            <div class="ebottom">
                                <img src="http://www.prettytendr.com/wp-content/uploads/2014/08/coin-slice_03.png">
                            </div>
                        </div>  
                    </div>  
                </div>
     
                <div class="arrow">
                    <img src="http://www.prettytendr.com/wp-content/uploads/2014/08/arrow.png">
                </div>
            </div>
            <div class="small-10 small-offset-1 medium-4 medium-offset-0 large-4 large-offset-0 columns">
                <div class="eframe">
                    <h2 class="eheading">EXCHANGE</h2>
                    <div class="row">
                        <div class="small-12 small-centered columns">
                            <p class="etext">Trade your PT for real beauty products of your choice from our marketplace</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-6 small-centered medium-6 columns">    
                            <div class="ebottom">
                                <img src="http://www.prettytendr.com/wp-content/uploads/2014/08/exchange-slice_05.png">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="arrow">
                    <img src="http://www.prettytendr.com/wp-content/uploads/2014/08/arrow.png">
                </div> 
            </div>
            <div class="small-10 small-offset-1 medium-4 medium-offset-0 large-4 large-offset-0 columns">
                <div class="eframe">
                    <h2 class="eheading">ENJOY</h2>
                    <div class="row">
                        <div class="small-12 small-centered columns">  
                            <p class="etext">Delight in trying <br>the latest products at no cost to you!</br></p>
                        </div>
                    </div>
                <div class="row">
                    <div class="small-6 small-centered medium-6 columns">       
                        <div class="ebottom">
                            <img src="http://www.prettytendr.com/wp-content/uploads/2014/08/enjoy-slice_07.png">
                        </div>
                    </div>
                </div>      
            </div>
            <div class="arrow"><img src="http://www.prettytendr.com/wp-content/uploads/2014/08/arrow.png">
            </div>
        </div>
        <div class="row">
            <div class="small-10 small-centered large-6 large-centered columns">
                <a href="#description"><div class="button" id="button2"> GET NOTIFIED</div></a>
            </div>  
        </div>
    </div>
</div> 
<div class="featured-segment">
    <div class="row">
        <div class="small-12 small-centered large-6 columns">
            <p class="headline" id="featured-headline">FEATURED BRANDS</p>
        </div>    
    </div>
<div class="brand-module">
    <div class="row">
        <div class="small-12  columns">
            <ul class="brands-list">
                <div class="small-6  medium-3 large-3 columns">        
                    <li><img src="http://www.prettytendr.com/wp-content/uploads/2014/08/nars_logo_black.png"></li>
                </div>
                <div class="small-6 medium-3 large-3 columns">    
                    <li><img src="http://www.prettytendr.com/wp-content/uploads/2014/08/benefit.png"></li>
                </div>
                <div class="small-6 medium-3  large-3 columns"> 
                    <li><img src="http://www.prettytendr.com/wp-content/uploads/2014/08/bumble.png"></li>
                </div>   
                <div class="small-6 medium-3 large-3 columns">    
                    <li><img src="http://www.prettytendr.com/wp-content/uploads/2014/08/urban_decay_logo_black_16.png">
                    </li>   
                </div>
                <div class="small-6 medium-3 large-3 columns">        
                    <li><img src="http://www.prettytendr.com/wp-content/uploads/2014/08/kiehls_logo_black_20.png">
                    </li>
                </div>
                <div class="small-6 medium-3 large-3 columns">    
                    <li><img src="http://www.prettytendr.com/wp-content/uploads/2014/08/carols_daughter_logo_black_18.png">
                    </li>
                </div>
                <div class="small-6 medium-3  large-3 columns"> 
                      <li><img src="http://www.prettytendr.com/wp-content/uploads/2014/08/fresh_logo_black_22.png"></li>
                </div>   
                <div class="small-6 medium-3 large-3 columns">    
                    <li><img src="http://www.prettytendr.com/wp-content/uploads/2014/08/phyto.png"></li>   
                </div>
            </ul>
        </div>
    </div>
</div>    
</div>
<div id="connect-segment">
    <div class="row">
        <div class="small-12 columns">
            <ul class="social-links">
                <div class="small-12 medium-2 large-2 columns">
                    <li><a href="mailto:info@prettytendr.com">contact</a>
                    </li>
                 </div>
                <div class="small-12 medium-2 large-2 columns"> 
                    <li><a href="http://wwww.facebook.com/prettytendr" target="_blank">facebook</a>
                    </li>
                </div>
                <div class="small-12 medium-2 large-2 columns">  
                    <li><a href="http://www.twitter.com/prettytendr" target="_blank">twitter</a>
                    </li>
                </div> 
                <div class="small-12 medium-2 large-2 columns"> 
                    <li><a href="http://www.instagram.com/prettytendr" target="_blank">instagram</a>
                    </li>
                </div>  
                <div class="small-12 medium-2 large-2 columns">  
                    <li><a href="http://www.pinterest.com/prettytendr" target="_blank">pinterest</a>
                    </li>
                 </div>
                 <div class="small-12 medium-2 large-2 columns"> 
                    <li><a href="http://blog.prettytendr.com" target="_blank">blog</a>
                    </li>
                </div>        
            </ul>
        </div>
    </div>          
</div>
</div> 
  


            
        
  
  

        

    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
