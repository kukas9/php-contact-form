<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo template_header(); ?>
	<link rel="stylesheet" href="<?php echo $template_path; ?>structure.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $template_path; ?>form.css" type="text/css" />
	<script type="text/javascript" src="<?php echo $template_path; ?>basic.js"></script>
</head>

<body id="public">
	<div id="langBox">
		<?php /*
			if($language == 'pl')
				echo '<a href="/en"><img src="images/en.png"/></a>';
			else
				echo '<a href="/pl"><img src="images/pl.png"/></a>';
		*/ ?>
	</div>

	<div id="container">
		<div id="logo">
			<a href="http://<?php echo $host; ?>">
				<!--img src="images/mail_write_48.png"/-->
				<!-- ?php echo $host; ?-->
			</a>
		</div>

		<form class="wufoo topLabel page" autocomplete="off" method="post">

		<div class="info">
			<h2><?php echo $host; ?></h2>
			<div><?php
				echo $lang['header'];
				echo $status;
			?></div>
		</div>

		<ul>
<li id="foli1"
		class="    ">


	<label class="desc" id="title1" for="message">
		<?php echo $lang['message']; ?>
				<span id="req_1" class="req">*</span>
			</label>

	<div>
				<textarea id="message"
			name="message"
			class="field textarea medium"
			rows="10" cols="50"
			tabindex="1"
			onkeyup="handleInput(this);"
			onchange="handleInput(this);"
						 ><?php
							if(isset($_POST['message']))
								echo $_POST['message'];
							?></textarea>

			</div>

		<p class="instruct" id="instruct1"><small><?php echo $lang['label_message']; ?></small></p>


	</li>


<li id="foli2" 		class="    ">
	<label class="desc" id="title2" for="email_from">
		<?php echo $lang['email']; ?>
				<span id="req_2" class="req">*</span>
			</label>
	<div>
		<input id="email_from" 			name="email_from" 			type="text" 			class="field text large" 			value="<?php
												if(isset($_POST['email_from']))
													echo $_POST['email_from'];
			?>" 			maxlength="255" 			tabindex="2" 			onkeyup="handleInput(this);" 			onchange="handleInput(this);" 			/>
	</div>
		<p class="instruct" id="instruct2"><small><?php echo $lang['label_email']; ?></small></p>
	</li>

<?php if ($config['recaptcha_enabled']): ?>
<li id="fo1licaptcha" class="captcha ">

<div class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_site_key']; ?>" data-theme="<?php echo $config['recaptcha_theme']; ?>"></div>

</li>
<?php endif; ?>

	<li class="buttons ">
			<input id="saveForm" name="submit" class="btTxt submit" type="submit" value="<?php echo $lang['submit']; ?>" />
			</li>
</ul>
</form>
</div>
<img id="bottom" src="images/bottom.png" alt="" />
<?php echo template_ga_code(); ?>
</body>
</html>
