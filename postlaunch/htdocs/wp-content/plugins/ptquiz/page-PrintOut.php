

<html>
<head>
<title>Your Answers</title>
<script  src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<?php 

/**
 * The template for displaying the beauty quiz
 */



get_header(); 

$url = home_url( '/' );

//echo 'Can you see me?';
?>
<body>
<?php 
/*
require 'connect.quiz.php';

	$the_questions = array(
	'q01' => "What is your ethnicity?",
	'q02' => "Select the closest match to your skin tone.",
	'q03' => "What are your skin care concerns?",
	'q04' => "Select the closest match to your hair texture.",
	'q05' => "What is your hair color?",
	'q06' => "Do your prefer to avoid hair care products containing any of the following ingredients?",
	'q07' => "What is your hair condition?",
	'q08' => "What are your hair care concerns?",
	'q09' => "How do you style your hair?",
	'q10' => "What is your hair length?",
	'q11' => "Which types of fragrances do you prefer?",
	'q12' => "I get my beauty knowledge from:",
	'q13' => "I'm signing up because:",
	'q14' => "What is your age range?",
	'q15' => "What is your income?",
	'q16' => "How much do you spend on beauty products each month?",
	'q17' => "Which most describes your personal style?",
	'q18' => "What other special interests apply to you?"
	);
	
	$the_answers = array(

	// QUESTION ONE
	'q01a01' => "BLACK / AFRICAN AMERICAN",
	'q01a02' => "HISPANIC",
	'q01a03' => "ASIAN",
	'q01a04' => "MIDDLE EASTERN",
	'q01a05' => "WHITE / CAUCASIAN",
	'q01a06' => "NATIVE AMERICAN",
	'q01a07' => "INDIAN / SOUTH ASIAN",
	'q01a08' => "MULTIETHNIC",

	// QUESTION TWO
	'q02a01' => "sahshe_website_q2_clr1.jpg",
	'q02a02' => "sahshe_website_q2_clr2.jpg",
	'q02a03' => "sahshe_website_q2_clr3.jpg",
	'q02a04' => "sahshe_website_q2_clr4.jpg",
	'q02a05' => "sahshe_website_q2_clr5.jpg",
	'q02a06' => "sahshe_website_q2_clr6.jpg",
	'q02a07' => "sahshe_website_q2_clr7.jpg",
	'q02a08' => "sahshe_website_q2_clr8.jpg",

	// QUESTION THREE
	'q03a01' => "ACNE / BLEMISHES",
	'q03a02' => "DRY SKIN",
	'q03a03' => "OILY SKIN / LARGE PORES",
	'q03a04' => "WRINKLES / AGING",
	'q03a05' => "SENSATIVE SKIN / IRRITATION",
	'q03a06' => "DARK SPOTS / HYPERPIGMENTATION",
	'q03a07' => "UNDER-EYE CIRCLES",

	// QUESTION FOUR
	'q04a01' => "sahshe_website_q4_1straight.jpg",
	'q04a02' => "sahshe_website_q4_2openwave.jpg",
	'q04a03' => "sahshe_website_q4_3wavy.jpg",
	'q04a04' => "sahshe_website_q4_4curly.jpg",
	'q04a05' => "sahshe_website_q4_5verycurly.jpg",
	'q04a06' => "sahshe_website_q4_6coiled.jpg",
	'q04a07' => "sahshe_website_q4_7verycoiled.jpg",
	'q04a08' => "sahshe_website_q4_8zigzag.jpg",

	// QUESTION FIVE
	'q05a01' => "BLACK",
	'q05a02' => "DARK BROWN",
	'q05a03' => "LIGHT BROWN",
	'q05a04' => "RED",
	'q05a05' => "BLONDE",
	'q05a06' => "GREY",
	'q05a07' => "OTHER",

	// QUESTION SIX
	'q06a01' => "PARABENS",
	'q06a02' => "SULFATES",
	'q06a03' => "MINERAL OIL",
	'q06a04' => "SILICONES",

	// QUESTION SEVEN
	'q07a01' => "CHEMICALLY RELAXED",
	'q07a02' => "COLOR-TREATED",
	'q07a03' => "TRANSITIONING",
	'q07a04' => "NATURAL",

	// QUESTION EIGHT
	'q08a01' => "THINNING",
	'q08a02' => "SCALP IRRITATION",
	'q08a03' => "DRYNESS / BREAKAGE",
	'q08a04' => "TANGLING",
	'q08a05' => "HAIR GROWTH",

	// QUESTION NINE
	'q09a01' => "BRAID-OUTS / TWIST-OUTS",
	'q09a02' => "ROLLER SETTING",
	'q09a03' => "\"WASH AND GO\"",
	'q09a04' => "HEAT STYLING",
	'q09a05' => "WEAVES / WIGS",
	'q09a06' => "BRAIDS / BRAID EXTENTIONS",
	'q09a07' => "LOCKS",
	'q09a08' => "HENNA DYES",

	// QUESTION TEN
	'q10a01' => "EAR LENGTH",
	'q10a02' => "CHIN LENGTH",
	'q10a03' => "NECK LENGTH",
	'q10a04' => "SHOULDER LENGTH",
	'q10a05' => "ARMPIT LENGTH",
	'q10a06' => "MID-BACK LENGTH",
	'q10a07' => "WAIST LENGTH AND BEYOND",

	// QUESTION ELEVEN
	'q11a01' => "FLORAL",
	'q11a02' => "SPICY",
	'q11a03' => "EARTHY",
	'q11a04' => "OCEANIC",
	'q11a05' => "FRUITY",

	// QUESTION TWELVE
	'q12a01' => "MAGAZINES",
	'q12a02' => "FRIENDS",
	'q12a03' => "MAKEUP COUNTERS",
	'q12a04' => "BEAUTY SUPPLY STORES",
	'q12a05' => "BLOGGERS",

	// QUESTION THIRTEEN
	'q13a01' => "I LOVE LUXURY SAMPLES!",
	'q13a02' => "I DON'T HAVE TIME TO SHOP.",
	'q13a03' => "I WANT TO CHANGE MY ROUTINE.",
	'q13a04' => "I'M SEEKING TUTORIALS AND HOW-TO INFORMATION.",

	// QUESTION FOURTEEN
	'q14a01' => "18-24 YEARS",
	'q14a02' => "25-30 YEARS",
	'q14a03' => "31-36 YEARS",
	'q14a04' => "37-42 YEARS",
	'q14a05' => "43-48 YEARS",
	'q14a06' => "49-54 YEARS",
	'q14a07' => "55+ YEARS",

	// QUESTION FIFTEEN
	'q15a01' => "LESS THAN $36,000",
	'q15a02' => "$36,000 to $69,999",
	'q15a03' => "$70,000 to $85,999",
	'q15a04' => "$86,000 to $110,999",
	'q15a05' => "$111,000 to $134,999",
	'q15a06' => "$135,000 and up",

	// QUESTION SIXTEEN
	'q16a01' => "$25 - $50",
	'q16a02' => "$51 - $100",
	'q16a03' => "$101 - $200",
	'q16a04' => "$200+",

	// QUESTION SEVENTEEN
	'q17a01' => "HIP / EDGY",
	'q17a02' => "CLASSIC",
	'q17a03' => "PROFESSIONAL",
	'q17a04' => "CLUBBING / NIGHTLIFE",
	'q17a05' => "VINTAGE",
	'q17a06' => "SPORTY",
	'q17a07' => "SULTRY",
	'q17a08' => "MINIMAL",

	// QUESTION EIGHTEEN
	'q18a01' => "FAMILY",
	'q18a02' => "ACTIVE LIFESTYLE",
	'q18a03' => "NATURAL",
	'q18a04' => "COLLEGE",

	);

if(isset($_POST['q01a01'])){

	//echo 'Number of elements in the quiz answers array is '.sizeof($the_answers).'<br>';
	$quiz_keys = array_keys($the_answers);

	//for now create an associative array whose keys are the quiz_ids and whose values are all '0' 
	$quiz_responses = array_fill_keys($quiz_keys,'0');
	
	//as we loop through each quiz response, we replace the '0' value placeholders with the appropriate 1 or -1
	foreach($quiz_keys as $i){
		$quiz_responses[$i] = $_POST[$i];
	
	}

	$quiz_responses_comma_separated = implode(', ',$quiz_responses);		 

	echo $quiz_responses_comma_separated;	
	
	if($mysqli_select_db){

	//add quiz submission to db	
	$query = "INSERT INTO `quiz`(`id`, ` q01a01`, ` q01a02`, ` q01a03`, ` q01a04`, ` q01a05`, ` q01a06`, ` q01a07`, ` q01a08`, ` q02a01`, ` q02a02`, ` q02a03`, ` q02a04`, ` q02a05`, ` q02a06`, ` q02a07`, ` q02a08`, ` q03a01`, ` q03a02`, ` q03a03`, ` q03a04`, ` q03a05`, ` q03a06`, ` q03a07`, ` q04a01`, ` q04a02`, ` q04a03`, ` q04a04`, ` q04a05`, ` q04a06`, ` q04a07`, ` q04a08`, ` q05a01`, ` q05a02`, ` q05a03`, ` q05a04`, ` q05a05`, ` q05a06`, ` q05a07`, ` q06a01`, ` q06a02`, ` q06a03`, ` q06a04`, ` q07a01`, ` q07a02`, ` q07a03`, ` q07a04`, ` q08a01`, ` q08a02`, ` q08a03`, ` q08a04`, ` q08a05`, ` q09a01`, ` q09a02`, ` q09a03`, ` q09a04`, ` q09a05`, ` q09a06`, ` q09a07`, ` q09a08`, ` q10a01`, ` q10a02`, ` q10a03`, ` q10a04`, ` q10a05`, ` q10a06`, ` q10a07`, ` q11a01`, ` q11a02`, ` q11a03`, ` q11a04`, ` q11a05`, ` q12a01`, ` q12a02`, ` q12a03`, ` q12a04`, ` q12a05`, ` q13a01`, ` q13a02`, ` q13a03`, ` q13a04`, ` q14a01`, ` q14a02`, ` q14a03`, ` q14a04`, ` q14a05`, ` q14a06`, ` q14a07`, ` q15a01`, ` q15a02`, ` q15a03`, ` q15a04`, ` q15a05`, ` q15a06`, ` q16a01`, ` q16a02`, ` q16a03`, ` q16a04`, ` q17a01`, ` q17a02`, ` q17a03`, ` q17a04`, ` q17a05`, ` q17a06`, ` q17a07`, ` q17a08`, ` q18a01`, ` q18a02`, ` q18a03`, ` q18a04`, `date_submitted`) 
			VALUES('',".$quiz_responses_comma_separated.",CURRENT_TIMESTAMP)";	
	
		/*
		//Create the MySQL table that will house all quiz submissions 
		//This block will only be used once (not during runtime)
		$query = "CREATE TABLE `quiz` (`id` INT NOT NULL AUTO_INCREMENT,
				 PRIMARY KEY (`id`)";
				 
		foreach($quiz_keys as $col_name){
			$query .=",` ".$col_name."` VARCHAR(2) NOT NULL";
		}
		
		$query .=")";
		*/	   
/*	   
	    $query_run = mysqli_query($link, $query);
		if($query_run){
			echo 'Success!';
		} else{
			echo 'Failed.';
		}

	} else{
		echo $select_db_error;
	}


} else{
	die('<br> Make sure you recently submitted the quiz form. If you didn\'t, the $_POST variables aren\'t set??');
}
*/
?>
</body>
</html>
<?php //get_sidebar('sidebar-home'); ?>
	


<?php get_footer();?>