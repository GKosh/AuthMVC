<?php
if(!defined("BASE_URL")) die;  
/*

Методы:
addUser
deleteUser
editUser
userList
*/

class UserModel 
{
		
	public function addUser($user){
		$db = new Database ;
		$db->query("INSERT INTO users(email , password, active) VALUES(:email,:password,:active);");
		$db->bind(':email', $user['email']);
		$db->bind(':password', $user['password']);
		$db->bind(':active', true);
		return $db->execute();
	}
	public function deleteUser($user){
		$db = new Database ;
		$db->query("DELETE FROM users WHERE id = :id");
		$db->bind(':id', $user['id']);
		return $db->execute();
	}
		
	public function usersList(){
		$db = new Database ;
		$db->query("SELECT id as ID, email as Email, active as Active FROM users");
		return $db->resultset();
	}
 
	public function getUser($user){
		$db = new Database ;
		$db->query("SELECT id as ID, email as Email, password as Password, active as Active  FROM users WHERE email = :email AND password = :password");
		$db->bind(':email', $user['email']);
		$db->bind(':password', $user['password']);
		return $db->resultset();
	}

	public function editUser($user){
		$db = new Database ;
		$db->query("UPDATE users SET email = :email, password = :password, active = :active WHERE id = :id");
		$db->bind(':id', $user['id']);
		$db->bind(':email', $user['email']);
		$db->bind(':password', $user['password']);
		$db->bind(':active', true);
		return $db->execute();
	}
	
}

