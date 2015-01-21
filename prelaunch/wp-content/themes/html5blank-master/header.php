<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

		<link href="//www.google-analytics.com" rel="dns-prefetch">
        <link href="<?php echo get_template_directory_uri(); ?> 
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<?php wp_head(); ?>
		<script>
        // conditionizr.com
        // configure environment tests
        conditionizr.config({
            assets: '<?php echo get_template_directory_uri(); ?>',
            tests: {}
        });
        </script>

	</head>
	<body <?php body_class(); ?>>

		<html data-wf-site="537f60d162d006ce3554ff28">
<head>
  <meta charset="utf-8">
  <title>PrettyTendr.com</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/prettytendrcom.webflow.css">
  <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js"></script>
  <script>
    WebFont.load({
      google: {
        families: ["Six Caps:regular"]
      }
    });
  </script>
<script type="text/javascript" src="//use.typekit.net/ika2xaq.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

  <script type="text/javascript" src="js/modernizr.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
 
</head>
<body class="w-clearfix">
  <header class="w-clearfix logodiv">
    <img class="logo" src="http://www.prettytendr.com/wp-content/uploads/2014/06/PrettyTendr_stacked_Tagline_mint.png" alt="5390ab96b68c9bc4117be264_PrettyTendr_stacked_Tagline_mint.png">
  </header>
  <main class="signup">
    <div class="w-form formwrapper">
      <form class="form normal" id="email-form" name="email-form" data-name="Email Form">
        <input class="w-input first-name" id="first-name-2" type="text" placeholder="First name" name="first-name" data-name="First Name">
        <input class="w-input last-name" id="last-name" type="text" placeholder="Last name" name="last-name" required="required" data-name="Last Name">
        <input class="w-input email" id="email" type="email" placeholder="Email address" name="email" data-name="Email" required="required">
        <input class="w-button submit" type="submit" value="GET NOTIFIED" data-wait="Please wait...">
      </form>
      <div class="w-form-done success">
        <p>Thank you! Your submission has been received! Get social with us below!</p>
      </div>
      <div class="w-form-fail errormessage">
        <p>Oops! Something went wrong while submitting the form. Please try again.</p>
      </div>
    </div>
  </main>
  <footer class="w-clearfix footer">
    <ul class="w-list-unstyled w-clearfix socialmedialinks">
      <li class="li lifirst"><a class="link" href="mailto:info@prettytendr.com">Contact</a>
      </li>
      <li class="li"><a class="link" href="http://blog.prettytendr.com/" target="_blank">Blog</a>
      </li>
      <li class="li"><a class="link" href="http://www.facebook.com/prettytendr" target="_blank">Facebook</a>
      </li>
      <li class="li"><a class="link" href="http://www.instagram.com/prettytendr" target="_blank">Instagram</a>
      </li>
      <li class="li"><a class="link" href="http://www.twitter.com/prettytendr" target="_blank">Twitter</a>
      </li>
      <li class="li"><a class="link" href="http://www.pinterest.com/prettytendr" target="_blank">Pinterest</a>
      </li>
    </ul>


			</header>
 
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
</body>
</html>