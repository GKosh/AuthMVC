<div id="content">
	<div id="form_container">
	
		<form id="registration_form" class="form"  method="post" action="/user/registration">
			<h2><?php echo $title; ?></h2>
								
			<ul >
			
		<li id="li_1" >
			<div class="form-group">
			<label class="description" for="email">Email </label>
			<div>
				<input id="email" name="email" class="email" type="email" placeholder="Please input your email"/> 
			</div><p class="required" id="required1"></p> 
		</li>		
		<li id="li_2" >
			<div class="form-group">
			<label class="description" for="password">Password </label>
			<div>
				<input id="password" name="password" class="password" type="password" placeholder="New password"/> 
			</div><p class="required" id="required2"></p> 
		</li>
		<li id="li_3" >
			<div class="form-group">
			<label class="description" for="pass_confirm">Confirm passwoed </label>
			<div>
				<input id="pass_confirm" name="pass_confirm" class="password" type="password" placeholder="Confirm password"/> 
			</div><p class="required" id="required3"></p> 
		</li>	
		<li class="buttons">
			<input id="saveForm" class="button" type="submit" name="submit" value="Register me please" />
		</li>
			</ul>
		</form>	
		
	</div>
	<p class="error" id="error"></p> 
</div id="content">

<script type="text/javascript" src="<?php echo $regJS; ?> "></script>
