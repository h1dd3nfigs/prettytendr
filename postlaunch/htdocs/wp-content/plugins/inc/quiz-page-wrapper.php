

<html>
<head>
<title>Quiz</title>
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
<div id="primary">
	<div id="content" role="main">
		<div id="quiznav"></div>
		<div class="quiz" height="100px">
			<form action="<?php echo $url; ?>printout" method="POST">
				<!-- THE LEFT TAB -->
				<div id="tab0" style=" float:left; background: #060709; width:360px; padding:20px; height: 500px;">
					<p style="color: #aaa; font-family: Josefin Slab !important; text-align: center; font-size:15px; margin-top:80px; letter-spacing: 4px; ">BEAUTY QUIZ</p>
					<div style="border-radius: 999px; border: 2px solid white; width: 180px; height: 180px; text-align: center; padding:50px; margin:40px auto !important;">
						<span style="font-family: Josefin Slab !important; font-size:18px !important; color:white; display:block; margin-top:-11px;">NO.</span>
						<h1 id="questionNumber" style=" display:block; margin-left:-4px; font-size:80px !important; color:white; font-family: 'Cardo' !important;">1</h1>
					</div>
					<p style="font-family: Josefin Slab !important;  color:#C4A494; width:80%; margin:0 auto !important; text-align:left; font-size:12px !important; line-height:1.5;">Take the <span style="color:white; font-size:16px !important;">SAHSHÉ BEAUTY QUIZ!</span> Your answers to these 19 questions will help us personalize your experience on sahshe.com.</p>
				</div>
				<style>
					.tab {
						width:600px;
						margin:0 0 0 0 !important;
						background: #E0BBA8;
						float:right;
						padding:50px;
						padding-top:50px;
						height: 500px;
						position: relative;
						display: none; /*ruth added 9/30/14 to see if we can avoid seeing all questions initially before they're hidden*/
					}
					
					.FF {
						display:none;
						
						position: fixed;
						top:0px;
						right:0px;
						width:225px;
						height:300px;
						margin-top:50px;
						font-size:14px;
						font-family: Arial !important;
					}
					
					.FF input {
						font-size:14px;
						font-family: Arial !important;
					}
					
					#quiznav{
						
						position: fixed;
						
						left:400px;
						top:35px;
						font-size:24px;
						text-align:center;
					}
					
					#quiznav ul li {
						cursor:pointer;
					}
					
					#quiznav ul li:hover{
						color:pink;
						font-weight: 900;
					}
					
					.tab .left_col h1{
						display:none;
					}
					
					.tab .left_col h2{
						color:black;
						font-size:26px;
							font-size:24px;
						text-transform: uppercase;
						font-weight:bold;
					}
					
					.tab .left_col h3{
						color:#856F67;
						font-style:italic;
						font-size:16px;
						text-transform: uppercase;
						margin-top:5px;
						margin-bottom:20px;
					}
					
					.tab div{
						cursor:pointer;
					}
					
					.tab div:not(.left_col) h3 {
						color:#856F67;
							color:#555F57;
						font-size:22px;
						text-transform: uppercase;
						margin:8px 0px;
					}
					
					.tab div:not(.left_col):hover h3 {
						font-weight: 900 !important;
						color:black !important;
					}
					
					.tab div.active:not(.left_col) h3{
						font-weight: 900 !important;
						color: white !important;
					}

					
					.next{
						margin-top:10px;
						padding:10px 15px 5px 15px;
						font-size:24px;
						float:left;
						background:black;
						color:white;
						display:none;
						position: absolute;
						bottom:50px;
						right:50px;
						font-family: Josefin Slab !important;
					}
					
					
					#tab2 div:not(.left_col):not(.FF):not(.next),
					#tab4 div:not(.left_col):not(.FF):not(.next){
						width:115px;
						height: 115px;
						margin-right:10px;
						margin-bottom:10px;
						float: left;
						border:3px solid transparent;
					}
					
					#tab2 .radio_answer,
					#tab4 .radio_answer {
						overflow:hidden !important;
					}
					
					#tab2 div:not(.left_col):not(.FF):not(.next):hover,
					#tab4 div:not(.left_col):not(.FF):not(.next):hover{
						border:3px solid black;
					}
					
					#tab2 div.active:not(.left_col):not(.FF):not(.next),
					#tab4 div.active:not(.left_col):not(.FF):not(.next){
						border:3px solid white;
					}
											
					#tab2 div.active:not(.left_col):not(.next),
					#tab4 div.active:not(.left_col):not(.next){
						background: white;
					}
					
					
					#tab4 div:not(.left_col):not(.FF):not(.next){
						position: relative;
					}
					
					#tab4 div:not(.left_col):not(.FF):not(.next) img{
						position: absolute;
						bottom: 0px;
					}
					
					
					#tab6 .next {
						display: block !important;
					}
					
												
					#tab18 .next {
						/*
						display: block !important;
						*/
					}
					
					#form_submit, .finish{
						border: 0px solid black;
						cursor:pointer;
					}
					
					#tab18 input,
					#tab19 input{
						font-family: Arial !important;
						font-size:16px;
						margin-top:-5px;
						margin-bottom:5px;
					}
					
					
					#tab18 input {
						margin-top:10px;
					}
				</style>
				<script>
					$(function(){

						
							$('#tab1').show();
						//});  //ruth commented this out 9/30/14 to prevent all questions from initially showing

						
						$('#quiznav').append("<ul></ul>");
						
						var count = 1;
						
						$('.tab').each(function(){
							var el = $("<li>"+count+"</li>");
							$('#quiznav ul').append(el);
							count++;
						});
						
						
						$('#quiznav ul li').click(function(){
							var digit = parseInt($(this).html());
							$('.tab').hide();
							$('#tab'+digit).show();	
							$('#questionNumber').html('' + digit);
						});
						
						/*
							$('#tab2 div:not(.left_col):not(.FF):not(.next)').each(function(){
								var randomNESS = '#'+Math.floor(Math.random()*16777215).toString(16);
								$(this).css('background', randomNESS);
							});
						*/
								
						$('.tab div.check_answer').click(function(){
							$(this).toggleClass('active');
						});
						
						
						$('.tab div.radio_answer').click(function(){
							$(this).closest('.tab').find('.radio_answer').removeClass('active');
							$(this).toggleClass('active');
						});
						
						
						$('.next:not(#form_submit)').click(function(){
							var number = parseInt($('#questionNumber').html());
							number++;
							//alert(number);
							$('#questionNumber').html('' + number);
							
							$('.tab').hide();
							$('#tab'+number).show();
						});		
					
						// old logic
						
						//college button functionality
						$("#college").hide();
						$(".college_button").click( function(){
							$("#college").toggle();
						});
						
						//clear all of the inputs
						$("input[type='text']").val("-1");
						$(".college").val("");
						$("#q19 input").val("");
						
						
						//assign name attributes to each input field
						$("input[type='text']").each( function(){
								var x = $(this).attr("id");
								$(this).attr('name', x);	
							}
						);
						
						
						// for radio buttons (1 choice only)
						$(".tab .radio_answer").click(function(){
							var id = $(this).attr('rel');
							$(this).closest(".tab").children(".radio_answer").removeClass("selected");
							$(this).closest(".tab").children(".choose_one").children("input").val("-1");
							$(this).addClass("selected");
							$("#"+id).val("1");
							
							var next_rel = $(this).closest(".tab").find(".next").attr('rel');
							var this_guy = $(this);
							if ( hasSelected(this_guy) ) {
								$(this).closest(".tab").find(".next").show();
								$('#tn'+(next_rel-1)).addClass('complete');
							} else {
								$(this).closest(".tab").find(".next").hide();
								//$('#tn'+(next_rel-1)).removeClass('complete');
							}
						});
							
						
						// for check boxes (choose as many as you like)
						$(".tab .check_answer").click(function(){

							var id = $(this).attr('rel');
							
							if ( !$(this).hasClass('college_button') ){
							
								if ( $(this).hasClass("selected") ){
									$(this).removeClass("selected");
									$("#"+id).val("-1");
								} else {
									$(this).addClass("selected");
									$("#"+id).val("1");
								}
																		
							} else {
								$(this).removeClass("selected");
								$("#q18a04").val("-1");
								$('.college').val('');
							}

							
							if ( $(this).closest(".tab").attr("id") != "tab18" && $(this).closest(".tab").attr("id") != "tab6" ){
								
								var next_rel = $(this).closest(".tab").find(".next").attr('rel');
								var this_guy = $(this);
								if ( hasSelected(this_guy) ) {
									$(this).closest(".tab").find(".next").show();
									$('#tn'+(next_rel-1)).addClass('complete');
								} else {
									$(this).closest(".tab").find(".next").hide();
									//$('#tn'+(next_rel-1)).removeClass('complete');
								}
							
							}
						});
						
					
						$('.college').change(function(){
							$("#q18a04").val($('.college').val());
						
							if ( validate18() ){
								$(this).closest('.tab').find('.next').show();
							} else {
								$(this).closest('.tab').find('.next').hide();
							}
						
						});
						
						$('.college').keyup(function(){
							
							
							if ( $("#q18a04").val().length > 0 ){
								$(this).closest('.tab').find('.next').show();
							} else {
								$(this).closest('.tab').find('.next').hide();
							}
							
						});
						
						
						//tab 18 validation
						$('#tab18 .check_answer').click(function(){
							
							if ( $('.college_button').hasClass('active') ){
								
								$(this).closest('.tab').find('.next').hide();
								
								if ( $('.college').val().length > 0 ){
									$(this).closest('.tab').find('.next').show();
								} else {
									$(this).closest('.tab').find('.next').hide();
								}
								
							} else {
								
								if ( $("#tab18 .check_answer:not(.college_button)").hasClass('active') ){
									$(this).closest('.tab').find('.next').show();
								} else {
									$(this).closest('.tab').find('.next').hide();
								}
								
							}
						});
							
						function hasSelected(x){
							var i = 0;
							
							x.closest(".tab").children(".selected").each( function(){
								i++;
							});
							
							if (i > 0){
								return true;
							} else {
								return false;
							}
						}	


						function IsEmail(email) {
							var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
							return regex.test(email);
						}

						
						$("#email").keyup(function(){
							var input = $(this).val();
							if (input != "" & IsEmail(input)){
								$("#email").removeClass("error");
								$("#email").addClass("valid");
								$("#emailerror").hide();
							}else{
								$("#email").addClass("required");
								$("#email").removeClass("valid");
								$("#emailerror").show();				
							}
						});
						
						$("#email").blur(function(){
							var input = $(this).val();
							if (input != "" & IsEmail(input)){
								$("#email").removeClass("error");
								$("#email").addClass("valid");
								$("#emailerror").hide();
							}else{
								$("#email").addClass("error");
								$("#email").removeClass("valid");
								$("#emailerror").show();
							}
						});


						var x, y, z;
						
						x = Math.floor((Math.random()*10)+1);
						y = Math.floor((Math.random()*10)+1);
						z = x + y;
						
						$('#x').html(x);
						$('#y').html(y);
						
						
						$('input#captcha').keyup(function(){
							if( $(this).val() == z ){
								$('#form_submit').show();
							} else {
								$('#form_submit').hide();
							}
						});
						
						$('input#captcha').change(function(){
							if( $(this).val() == z ){
								$('#form_submit').show();
							} else {
								$('#form_submit').hide();
							}
						});
						
						
						
						  $(window).keydown(function(event){
							if(event.keyCode == 13) {
							  event.preventDefault();
							  return false;
							}
						  });

						
					});
				</script>

				<!-- SKIN QUESTIONS -->
				<div id="tab1" class="tab">

					<div class="left_col">
						<h2>What is your ethnicity?</h2>
						<h3>(Choose as many as you like)</h3>
					</div>

					<div class="check_answer" rel="q01a01" >
						<div class="table">
							<div class="cell">
								<h3>BLACK / AFRICAN AMERICAN</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q01a02" >
						<div class="table">
							<div class="cell">
								<h3>HISPANIC</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q01a03" >
						<div class="table">
							<div class="cell">
								<h3>ASIAN</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q01a04" >
						<div class="table">
							<div class="cell">
								<h3>MIDDLE EASTERN</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q01a05" >
						<div class="table">
							<div class="cell">
								<h3>WHITE / CAUCASIAN</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q01a06" >
						<div class="table">
							<div class="cell">
								<h3>NATIVE AMERICAN</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q01a07" >
						<div class="table">
							<div class="cell">
								<h3>INDIAN / SOUTH ASIAN</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q01a08" >
						<div class="table">
							<div class="cell">
								<h3 >MULTIETHNIC</h3>
							</div>
						</div>
					</div>

					<div class="next" rel="2">
						NEXT
					</div>

					<div id="q01" class="FF">
						<p>
							Ethnicity
						</p>
						q01a01
						<input type="text" id="q01a01">
						<br/>
						q01a02
						<input type="text" id="q01a02">
						<br/>
						q01a03
						<input type="text" id="q01a03">
						<br/>
						q01a04
						<input type="text" id="q01a04">
						<br/>
						q01a05
						<input type="text" id="q01a05">
						<br/>
						q01a06
						<input type="text" id="q01a06">
						<br/>
						q01a07
						<input type="text" id="q01a07">
						<br/>
						q01a08
						<input type="text" id="q01a08">
						<br/>
					</div>
				</div>

				<div id="tab2" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>Select the closest match to your skin tone.</h2>
						<h3>(Please choose one)</h3>
					</div>

					<!--
					<div class="radio_answer" rel="q02a01" ></div>
					<div class="radio_answer" rel="q02a02" ></div>
					<div class="radio_answer" rel="q02a03" ></div>
					<div class="radio_answer" rel="q02a04" ></div>
					<div class="radio_answer" rel="q02a05" ></div>
					<div class="radio_answer" rel="q02a06" ></div>
					<div class="radio_answer" rel="q02a07" ></div>
					<div class="radio_answer" rel="q02a08" ></div>
					-->
					
					<div class="radio_answer" rel="q02a01"><img src="monkey.jpg" alt="" /></div>
					<div class="radio_answer" rel="q02a02"><img src="monkey.jpg" alt="" /></div>
					<div class="radio_answer" rel="q02a03"><img src="monkey.jpg" alt="" /></div>
					<div class="radio_answer" rel="q02a04"><img src="monkey.jpg" alt="" /></div>
					<div class="radio_answer" rel="q02a05"><img src="monkey.jpg" alt="" /></div>
					<div class="radio_answer" rel="q02a06"><img src="monkey.jpg" alt="" /></div>
					<div class="radio_answer" rel="q02a07"><img src="monkey.jpg" alt="" /></div>
					<div class="radio_answer" rel="q02a08"><img src="monkey.jpg" alt="" /></div>


					<div class="next" rel="3">
						NEXT
					</div>

					<div id="q02" class="FF choose_one">
						<p>
							Skin Tone
						</p>
						q02a01
						<input type="text" id="q02a01">
						<br/>
						q02a02
						<input type="text" id="q02a02">
						<br/>
						q02a03
						<input type="text" id="q02a03">
						<br/>
						q02a04
						<input type="text" id="q02a04">
						<br/>
						q02a05
						<input type="text" id="q02a05">
						<br/>
						q02a06
						<input type="text" id="q02a06">
						<br/>
						q02a07
						<input type="text" id="q02a07">
						<br/>
						q02a08
						<input type="text" id="q02a08">
						<br/>
					</div>
				</div>

				<div id="tab3" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>What are your skin care concerns?</h2>
						<h3>(Choose all that apply.)</h3>
					</div>

					<div class="check_answer" rel="q03a01" >
						<div class="table">
							<div class="cell">
								<h3>ACNE / BLEMISHES</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q03a02" >
						<div class="table">
							<div class="cell">
								<h3>DRY SKIN</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q03a03" >
						<div class="table">
							<div class="cell">
								<h3>OILY SKIN / LARGE PORES</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q03a04" >
						<div class="table">
							<div class="cell">
								<h3>WRINKLES / AGING</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q03a05" >
						<div class="table">
							<div class="cell">
								<h3>SENSITIVE SKIN / IRRITATION</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q03a06" >
						<div class="table">
							<div class="cell">
								<h3>DARK SPOTS / HYPERPIGMENTATION</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q03a07" >
						<div class="table">
							<div class="cell">
								<h3>UNDER-EYE CIRCLES</h3>
							</div>
						</div>
					</div>

					<div class="next" rel="4">
						NEXT
					</div>

					<div id="q03" class="FF">
						<p>
							Skin Tone
						</p>
						q03a01
						<input type="text" id="q03a01">
						<br/>
						q03a02
						<input type="text" id="q03a02">
						<br/>
						q03a03
						<input type="text" id="q03a03">
						<br/>
						q03a04
						<input type="text" id="q03a04">
						<br/>
						q03a05
						<input type="text" id="q03a05">
						<br/>
						q03a06
						<input type="text" id="q03a06">
						<br/>
						q03a07
						<input type="text" id="q03a07">
						<br/>
					</div>
				</div>

				<!--HAIR QUESTIONS-->
				<div id="tab4" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>Select the closest match to your hair texture.</h2>
						<h3>(Please choose one)</h3>
					</div>
					
					<div class="radio_answer" rel="q04a01"><img src="sahshe-logo-on-brown-background.png" alt="" /></div>
					<div class="radio_answer" rel="q04a02"><img src="sahshe-logo-on-brown-background.png" alt="" /></div>
					<div class="radio_answer" rel="q04a03"><img src="sahshe-logo-on-brown-background.png" alt="" /></div>
					<div class="radio_answer" rel="q04a04"><img src="sahshe-logo-on-brown-background.png" alt="" /></div>
					<div class="radio_answer" rel="q04a05"><img src="sahshe-logo-on-brown-background.png" alt="" /></div>
					<div class="radio_answer" rel="q04a06"><img src="sahshe-logo-on-brown-background.png" alt="" /></div>
					<div class="radio_answer" rel="q04a07"><img src="sahshe-logo-on-brown-background.png" alt="" /></div>
					<div class="radio_answer" rel="q04a08"><img src="sahshe-logo-on-brown-background.png" alt="" /></div>
					
					
					<div class="next" rel="5">
						NEXT
					</div>

					<div id="q04" class="FF choose_one">
						<p>
							Hair Color
						</p>
						q04a01
						<input type="text" id="q04a01">
						<br/>
						q04a02
						<input type="text" id="q04a02">
						<br/>
						q04a03
						<input type="text" id="q04a03">
						<br/>
						q04a04
						<input type="text" id="q04a04">
						<br/>
						q04a05
						<input type="text" id="q04a05">
						<br/>
						q04a06
						<input type="text" id="q04a06">
						<br/>
						q04a07
						<input type="text" id="q04a07">
						<br/>
						q04a08
						<input type="text" id="q04a08">
						<br/>
					</div>

				</div>

				<div id="tab5" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>What is your hair color?</h2>
						<h3>(Please choose one)</h3>
					</div>

					<div class="radio_answer" rel="q05a01" >
						<div class="table">
							<div class="cell">
								<h3>BLACK</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q05a02" >
						<div class="table">
							<div class="cell">
								<h3>DARK BROWN</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q05a03" >
						<div class="table">
							<div class="cell">
								<h3>LIGHT BROWN</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q05a04" >
						<div class="table">
							<div class="cell">
								<h3>RED</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q05a05" >
						<div class="table">
							<div class="cell">
								<h3>BLONDE</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q05a06" >
						<div class="table">
							<div class="cell">
								<h3>GREY</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q05a07" >
						<div class="table">
							<div class="cell">
								<h3>OTHER</h3>
							</div>
						</div>
					</div>

					<div class="next" rel="6">
						NEXT
					</div>

					<div id="q05" class="FF choose_one">
						<p>
							Hair Color
						</p>
						q05a01
						<input type="text" id="q05a01">
						<br/>
						q05a02
						<input type="text" id="q05a02">
						<br/>
						q05a03
						<input type="text" id="q05a03">
						<br/>
						q05a04
						<input type="text" id="q05a04">
						<br/>
						q05a05
						<input type="text" id="q05a05">
						<br/>
						q05a06
						<input type="text" id="q05a06">
						<br/>
						q05a07
						<input type="text" id="q05a07">
						<br/>
					</div>

				</div>

				<div id="tab6" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>Do your prefer to avoid hair care products containing any of the following ingredients?</h2>
						<h3>(Choose all that apply.)</h3>
					</div>

					<div class="check_answer" rel="q06a01" >
						<div class="table">
							<div class="cell">
								<h3>PARABENS</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q06a02" >
						<div class="table">
							<div class="cell">
								<h3>SULFATES</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q06a03" >
						<div class="table">
							<div class="cell">
								<h3>MINERAL OIL</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q06a04" >
						<div class="table">
							<div class="cell">
								<h3>SILICONES</h3>
							</div>
						</div>
					</div>

					<div class="next" rel="7">
						NEXT
					</div>

					<div id="q06" class="FF">
						<p>
							Hair Ingredient Avoidance
						</p>
						q06a01
						<input type="text" id="q06a01">
						<br/>
						q06a02
						<input type="text" id="q06a02">
						<br/>
						q06a03
						<input type="text" id="q06a03">
						<br/>
						q06a04
						<input type="text" id="q06a04">
						<br/>
					</div>

				</div>

				<div id="tab7" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>What is your hair condition?</h2>
						<h3>(Choose all that apply.)</h3>
					</div>

					<div class="check_answer" rel="q07a01" >
						<div class="table">
							<div class="cell">
								<h3>CHEMICALLY RELAXED</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q07a02" >
						<div class="table">
							<div class="cell">
								<h3>COLOR-TREATED</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q07a03" >
						<div class="table">
							<div class="cell">
								<h3>TRANSITIONING</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q07a04" >
						<div class="table">
							<div class="cell">
								<h3>NATURAL</h3>
							</div>
						</div>
					</div>

					<div class="next" rel="8">
						NEXT
					</div>

					<div id="q07" class="FF">
						<p>
							Hair Condition
						</p>

						q07a01
						<input type="text" id="q07a01">
						<br/>
						q07a02
						<input type="text" id="q07a02">
						<br/>
						q07a03
						<input type="text" id="q07a03">
						<br/>
						q07a04
						<input type="text" id="q07a04">

					</div>

				</div>

				<div id="tab8" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>What are your hair care concerns?</h2>
						<h3>(Choose all that apply.)</h3>
					</div>

					<div class="check_answer" rel="q08a01" >
						<div class="table">
							<div class="cell">
								<h3>THINNING</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q08a02" >
						<div class="table">
							<div class="cell">
								<h3>SCALP IRRITATION</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q08a03" >
						<div class="table">
							<div class="cell">
								<h3>DRYNESS / BREAKAGE</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q08a04" >
						<div class="table">
							<div class="cell">
								<h3>TANGLING</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q08a05" >
						<div class="table">
							<div class="cell">
								<h3>HAIR GROWTH</h3>
							</div>
						</div>
					</div>

					<div class="next" rel="9">
						NEXT
					</div>

					<div id="q08" class="FF">
						<p>
							Hair Care Concerns
						</p>
						q08a01
						<input type="text" id="q08a01">
						<br/>
						q08a02
						<input type="text" id="q08a02">
						<br/>
						q08a03
						<input type="text" id="q08a03">
						<br/>
						q08a04
						<input type="text" id="q08a04">
						<br/>
						q08a05
						<input type="text" id="q08a05">
						<br/>
					</div>

				</div>

				<div id="tab9" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>How do you style your hair?</h2>
						<h3>(Choose all that apply.)</h3>
					</div>

					<div class="check_answer" rel="q09a01" >
						<div class="table">
							<div class="cell">
								<h3>BRAID-OUTS / TWIST-OUTS</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q09a02" >
						<div class="table">
							<div class="cell">
								<h3>ROLLER SETTING</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q09a03" >
						<div class="table">
							<div class="cell">
								<h3>"WASH AND GO"</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q09a04" >
						<div class="table">
							<div class="cell">
								<h3>HEAT STYLING</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q09a05" >
						<div class="table">
							<div class="cell">
								<h3>WEAVES / WIGS</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q09a06" >
						<div class="table">
							<div class="cell">
								<h3>BRAIDS / BRAID EXTENTIONS</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q09a07" >
						<div class="table">
							<div class="cell">
								<h3 >LOCKS</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q09a08" >
						<div class="table">
							<div class="cell">
								<h3>HENNA DYES</h3>
							</div>
						</div>
					</div>

					<div class="next" rel="10">
						NEXT
					</div>

					<div id="q09" class="FF">
						<p>
							Hair Style
						</p>
						q09a01
						<input type="text" id="q09a01">
						<br/>
						q09a02
						<input type="text" id="q09a02">
						<br/>
						q09a03
						<input type="text" id="q09a03">
						<br/>
						q09a04
						<input type="text" id="q09a04">
						<br/>
						q09a05
						<input type="text" id="q09a05">
						<br/>
						q09a03
						<input type="text" id="q09a06">
						<br/>
						q09a04
						<input type="text" id="q09a07">
						<br/>
						q09a05
						<input type="text" id="q09a08">
						<br/>
					</div>

				</div>
				
				
				
				
										
				<div id="tab10" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>What is your hair length?</h2>
						<h3>(Please choose one.)</h3>
					</div>

					<div class="radio_answer" rel="q10a01" >
						<div class="table">
							<div class="cell">
								<h3>EAR LENGTH</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q10a02" >
						<div class="table">
							<div class="cell">
								<h3>CHIN LENGTH</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q10a03" >
						<div class="table">
							<div class="cell">
								<h3>NECK LENGTH</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q10a04" >
						<div class="table">
							<div class="cell">
								<h3>SHOULDER LENGTH</h3>
							</div>
						</div>
					</div>
					
					<div class="radio_answer" rel="q10a05" >
						<div class="table">
							<div class="cell">
								<h3>ARMPIT LENGTH</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q10a06" >
						<div class="table">
							<div class="cell">
								<h3>MID-BACK LENGTH</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q10a07" >
						<div class="table">
							<div class="cell">
								<h3>WAIST LENGTH AND BEYOND</h3>
							</div>
						</div>
					</div>

					<div class="next" rel="10">
						NEXT
					</div>

					<div id="q10" class="FF choose_one">
						<p>
							Hair Length
						</p>
						q10a01
						<input type="text" id="q10a01">
						<br/>
						q10a02
						<input type="text" id="q10a02">
						<br/>
						q10a03
						<input type="text" id="q10a03">
						<br/>
						q10a04
						<input type="text" id="q10a04">
						<br/>
						q10a05
						<input type="text" id="q10a05">
						<br/>
						q10a06
						<input type="text" id="q10a06">
						<br/>
						q10a07
						<input type="text" id="q10a07">
						<br/>								
					</div>

				</div>
				
				
				
				
				
				<div id="tab11" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>Which types of fragrances do you prefer?</h2>
						<h3>(Choose all that apply.)</h3>
					</div>

					<div class="check_answer" rel="q11a01" >
						<div class="table">
							<div class="cell">
								<h3>FLORAL</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q11a02" >
						<div class="table">
							<div class="cell">
								<h3>SPICY</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q11a03" >
						<div class="table">
							<div class="cell">
								<h3>EARTHY</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q11a04" >
						<div class="table">
							<div class="cell">
								<h3>OCEANIC</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q11a05" >
						<div class="table">
							<div class="cell">
								<h3>FRUITY</h3>
							</div>
						</div>
					</div>

					<div class="next" rel="10">
						NEXT
					</div>

					<div id="q11" class="FF">
						<p>
							Fragrances
						</p>
						q11a01
						<input type="text" id="q11a01">
						<br/>
						q11a02
						<input type="text" id="q11a02">
						<br/>
						q11a03
						<input type="text" id="q11a03">
						<br/>
						q11a04
						<input type="text" id="q11a04">
						<br/>
						q11a05
						<input type="text" id="q11a05">
						<br/>
					</div>

				</div>
				

				<!-- OTHER QUESTIONS -->
				<div id="tab12" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>I get my beauty knowledge from:</h2>
						<h3>(Choose all that apply.)</h3>
					</div>

					<div class="check_answer" rel="q12a01" >
						<div class="table">
							<div class="cell">
								<h3>MAGAZINES</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q12a02" >
						<div class="table">
							<div class="cell">
								<h3>FRIENDS</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q12a03" >
						<div class="table">
							<div class="cell">
								<h3>MAKEUP COUNTERS</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q12a04" >
						<div class="table">
							<div class="cell">
								<h3>BEAUTY SUPPLY STORES</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q12a05" >
						<div class="table">
							<div class="cell">
								<h3>BLOGGERS</h3>
							</div>
						</div>
					</div>

					<div class="next" rel="11">
						NEXT
					</div>

					<div id="q12" class="FF">
						<p>
							Hair Style
						</p>
						q12a01
						<input type="text" id="q12a01">
						<br/>
						q12a02
						<input type="text" id="q12a02">
						<br/>
						q12a03
						<input type="text" id="q12a03">
						<br/>
						q12a04
						<input type="text" id="q12a04">
						<br/>
						q12a05
						<input type="text" id="q12a05">
						<br/>
					</div>

				</div>

				<div id="tab13" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>I'm signing up because:</h2>
						<h3>(Choose all that apply.)</h3>
					</div>

					<div class="check_answer" rel="q13a01" >
						<div class="table">
							<div class="cell">
								<h3>I LOVE LUXURY SAMPLES!</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q13a02" >
						<div class="table">
							<div class="cell">
								<h3>I DON'T HAVE TIME TO SHOP.</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q13a03" >
						<div class="table">
							<div class="cell">
								<h3>I WANT TO CHANGE MY ROUTINE.</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q13a04" >
						<div class="table">
							<div class="cell">
								<h3>I'M SEEKING TUTORIALS AND HOW-TO INFORMATION.</h3>
							</div>
						</div>
					</div>

					<div class="next" rel="12">
						NEXT
					</div>

					<div id="q13" class="FF">
						<p>
							Hair Style
						</p>
						q13a01
						<input type="text" id="q13a01">
						<br/>
						q13a02
						<input type="text" id="q13a02">
						<br/>
						q13a03
						<input type="text" id="q13a03">
						<br/>
						q13a04
						<input type="text" id="q13a04">
						<br/>
					</div>

				</div>

				<div id="tab14" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>What is your age range?</h2>
						<h3>(Please choose one.)</h3>
					</div>

					<div class="radio_answer" rel="q14a01" >
						<div class="table">
							<div class="cell">
								<h3>18-24 YEARS</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q14a02" >
						<div class="table">
							<div class="cell">
								<h3>25-30 YEARS</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q14a03" >
						<div class="table">
							<div class="cell">
								<h3>31-36 YEARS</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q14a04" >
						<div class="table">
							<div class="cell">
								<h3>37-42 YEARS</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q14a05" >
						<div class="table">
							<div class="cell">
								<h3>43-48 YEARS</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q14a06" >
						<div class="table">
							<div class="cell">
								<h3>49-54 YEARS</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q14a07" >
						<div class="table">
							<div class="cell">
								<h3>55+ YEARS</h3>
							</div>
						</div>
					</div>

					<div class="next" rel="13">
						NEXT
					</div>

					<div id="q14" class="FF choose_one">
						<p>
							Hair Style
						</p>
						q14a01
						<input type="text" id="q14a01">
						<br/>
						q14a02
						<input type="text" id="q14a02">
						<br/>
						q14a03
						<input type="text" id="q14a03">
						<br/>
						q14a04
						<input type="text" id="q14a04">
						<br/>
						q14a05
						<input type="text" id="q14a05">
						<br/>
						q14a06
						<input type="text" id="q14a06">
						<br/>
						q14a07
						<input type="text" id="q14a07">
						<br/>
					</div>

				</div>

				<div id="tab15" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>What is your income?</h2>
						<h3>(Please choose one.)</h3>
					</div>

					<div class="radio_answer" rel="q15a01" >
						<div class="table">
							<div class="cell">
								<h3>LESS THAN $36,000</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q15a02" >
						<div class="table">
							<div class="cell">
								<h3>$36,000 to $69,999</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q15a03" >
						<div class="table">
							<div class="cell">
								<h3>$70,000 to $85,999</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q15a04" >
						<div class="table">
							<div class="cell">
								<h3>$86,000 to $110,999</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q15a05" >
						<div class="table">
							<div class="cell">
								<h3>$111,000 to $134,999</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q15a06" >
						<div class="table">
							<div class="cell">
								<h3>$135,000 and up</h3>
							</div>
						</div>
					</div>

					<div class="next" rel="14">
						NEXT
					</div>

					<div id="q15" class="FF choose_one">
						<p>
							Hair Style
						</p>
						q15a01
						<input type="text" id="q15a01">
						<br/>
						q15a02
						<input type="text" id="q15a02">
						<br/>
						q15a03
						<input type="text" id="q15a03">
						<br/>
						q15a04
						<input type="text" id="q15a04">
						<br/>
						q15a05
						<input type="text" id="q15a05">
						<br/>
						q15a06
						<input type="text" id="q15a06">
						<br/>
					</div>

				</div>
				
				
				
				
				<div id="tab16" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>How much do you spend on beauty products each month?</h2>
						<h3>(Please choose one.)</h3>
					</div>

					<div class="radio_answer" rel="q16a01" >
						<div class="table">
							<div class="cell">
								<h3>$25 - $50</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q16a02" >
						<div class="table">
							<div class="cell">
								<h3>$51 - $100</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q16a03" >
						<div class="table">
							<div class="cell">
								<h3>$101 - $200</h3>
							</div>
						</div>
					</div>

					<div class="radio_answer" rel="q16a04" >
						<div class="table">
							<div class="cell">
								<h3>$200+</h3>
							</div>
						</div>
					</div>
					
					<div class="next" rel="10">
						NEXT
					</div>
					
					<div id="q16" class="FF choose_one">
						<p>
							Beauty Purchases / Month
						</p>
						q16a01
						<input type="text" id="q16a01">
						<br/>
						q16a02
						<input type="text" id="q16a02">
						<br/>
						q16a03
						<input type="text" id="q16a03">
						<br/>
						q16a04
						<input type="text" id="q16a04">
						<br/>
					</div>
					
				</div>
													
				

				<div id="tab17" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>Which most describes your personal style?</h2>
						<h3>(Choose as many as you like.)</h3>
					</div>

					<div class="check_answer" rel="q17a01" >
						<div class="table">
							<div class="cell">
								<h3>HIP / EDGY </h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q17a02" >
						<div class="table">
							<div class="cell">
								<h3>CLASSIC </h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q17a03" >
						<div class="table">
							<div class="cell">
								<h3 >PROFESSIONAL</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q17a04" >
						<div class="table">
							<div class="cell">
								<h3>CLUBBING / NIGHTLIFE</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q17a05" >
						<div class="table">
							<div class="cell">
								<h3>VINTAGE </h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q17a06" >
						<div class="table">
							<div class="cell">
								<h3>SPORTY </h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q17a07" >
						<div class="table">
							<div class="cell">
								<h3>SULTRY</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q17a08" >
						<div class="table">
							<div class="cell">
								<h3>MINIMAL</h3>
							</div>
						</div>
					</div>

					<div class="next" rel="15">
						NEXT
					</div>

					<div id="q17" class="FF">
						<p>
							Personal Style
						</p>
						q17a01
						<input type="text" id="q17a01">
						<br/>
						q17a02
						<input type="text" id="q17a02">
						<br/>
						q17a03
						<input type="text" id="q17a03">
						<br/>
						q17a04
						<input type="text" id="q17a04">
						<br/>
						q17a05
						<input type="text" id="q17a05">
						<br/>
						q17a06
						<input type="text" id="q17a06">
						<br/>
						q17a07
						<input type="text" id="q17a07">
						<br/>
						q17a08
						<input type="text" id="q17a08">
						<br/>
					</div>

				</div>

				<div id="tab18" class="tab">
					<div class="left_col">
						<h1>YOUR
						<br/>
						<span class="lightpink">UNIQUE BEAUTY</span></h1>
						<h2>What other special interests apply to you?</h2>
						<h3>(Choose all that apply.)</h3>
					</div>

					<div class="check_answer" rel="q18a01" >
						<div class="table">
							<div class="cell">
								<h3>FAMILY</h3>
								<p>
									I'm a mother of children aged 0-10 years.</h3>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q18a02" >
						<div class="table">
							<div class="cell">
								<h3>ACTIVE LIFESTYLE</h3>
								<p>
									I'm always on the go!
								</p>
							</div>
						</div>
					</div>

					<div class="check_answer" rel="q18a03" >
						<div class="table">
							<div class="cell">
								<h3>NATURAL</h3>
								<p>
									I'm interested in anything natural, organic or eco-friendly.
								</p>
							</div>
						</div>
					</div>

					<div class="check_answer college_button" rel="" >
						<div class="table">
							<div class="cell">
								<h3>COLLEGE</h3>
								<p>
									I'm currently attending a college/university.
								</p>
							</div>
						</div>
					</div>

					<div id="college">
						Enter the name of the college you are currently attending below:
						<br/>
						<input type="text" class="college" value=""/>
					</div>

					<div class="next eighteen" rel="16" style="display:none;">
						NEXT
					</div>

					<div id="q18" class="FF">
						<p>
							Personal Style
						</p>
						q18a01
						<input type="text" id="q18a01">
						<br/>
						q18a02
						<input type="text" id="q18a02">
						<br/>
						q18a03
						<input type="text" id="q18a03">
						q18a04
						<input type="text" id="q18a04">

						<!--(college?)
						<input type="text" id="college">
						<br/>-->

						<!-- q18a04 <input type="text" id="q18a04"><br/> -->
					</div>

				</div>

				<div id="tab19" class="tab">
					<div class="left_col">
						<h1><span class="lightpink">FINAL STEP!</span></h1>
						<h2>Fill out the form to complete your beauty profile.</h2>
						<h3 >* <span class="lightpink">=required.</span></h3>
					</div>

					<div id="q19">
						<div class="question19" style="width:480px; font-family: 'Josefin Slab' !important; text-transform: uppercase;">
							
							<style>
								.question19 > div {
									font-family: Josefin Slab !important;
								}
							</style>
							
							<div>
								<span class="dark-pink">* </span>First Name <input type="text" id="first_name">
							</div>
							<br style="clear:both; display:block; margin-bottom:10px !important; "/>

							<div>
								<span class="dark-pink">* </span>Last Name <input type="text" id="last_name">
							</div>
							<br style="clear:both; display:block; margin-bottom:10px !important; "/>

							<div>
								<span class="dark-pink">* </span>Email <input type="text" id="email">
							</div>
							
							<div style="position:relative; margin-top:5px; ">
								<span id="emailerror" class="lightpink" style=" text-transform: none !important; display:none; font-weight:100 !important;" >The email address you entered is not valid.</span>
							</div>
							<br style="clear:both; display:block; margin-bottom:10px !important; "/>

							<div>
								<span class="dark-pink">* </span>What is the sum of?  <input type="text" id="captcha"> <em style="float:right; margin-right: 5px;"><span id="x">X</span> + <span id="y">Y</span> = </em>
							</div>
							<br style="clear:both; display:block; margin-bottom:10px !important; "/>

						</div>
						<span class="lightpink" > By proceeding, you agree to receiving e-mail from <strong>sahshe.com</strong> (you can unsubscribe any time). <!-- Read our <a href=" http://sahshe.webnetdev.com/privacy-policy/">privacy policy</a> for more information.--></span> 

					</div>

					<style>
					
						.question19 input {
						
							float:right;
						
						}
						
						.dark-pink {
							clear:both;
						}
					
					</style>
					

					<input class="next finish" id="form_submit" type="submit" value="FINISH">
				</div>

			</form>
			
		</div>


	</div><!-- #content -->
</div><!-- #primary -->
</body>
</html>
<?php //get_sidebar('sidebar-home'); ?>
	
<? //php get_footer(); ?>


<?php get_footer();?>