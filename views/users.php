<div id="content">
<h2><?php echo $title; ?></h2>
<h3 style="text-align: right"> You are loged in! <input id="logout" type="button" class="button" value="LogOut"> </h3>

<div id="table_container">
<div class="form">
    <br><br>
    <form  id="users_form" action="/user/users" class="form-horizontal">
			<label class="description" for="email">Email </label>
				<input id="email" name="email" class="email" type="email" placeholder="Please input your email"/> 
			<label class="description" for="password">Password </label>
				<input id="password" name="password" class="password" type="password" placeholder="New password"/>
			<label class="description" for="pass_confirm">Confirm passwoed </label>
			<input id="pass_confirm" name="pass_confirm" class="password" type="password" placeholder="Confirm password"/> 
                <input id="addNew" type="button" class="button" value="Add New">
                <input id="update" type="button" class="button" value="Update">
     </form>
</div>
</div>
<div id="table_container">
<div  id="tableBody" class="bs-example">
 <table class="table table-hover">
        <thead>
            <tr>
				<th></th>
                <th>ID</th>
                <th>Email</th>
              	<th>Active</th>
            </tr>
        </thead>
        <tbody>
		<?php
		foreach ($users as $user){
		?>
            <tr>
				<td style="width: 5px;"><label><input id="radio" class="radio" type="radio" name="id" form="form" value=<?php echo $user['ID'];?>></label></td>
 				<td style="text-align:center;"><?php echo $user['ID'];?></td>
                <td><?php echo $user['Email'];?></td>
                <td><?php echo $user['Active'];?></td>
				<td class="delete" style="cursor: pointer; width: 15px; text-align:center;"> X </td>
				
            </tr>
		<?php
		};	
		?>
    
        </tbody>
    </table>
</div>
</div>

 
</div>

<script type="text/javascript" src="<?php echo $usersJS;?> "></script>
