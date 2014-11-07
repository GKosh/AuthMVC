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
