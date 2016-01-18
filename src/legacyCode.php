<?php

function NewUser ($db, $username, $password)
{
	if (strlen($password) < 6) {
		error_log("Yarri YOOOOZZZZ...>!!!");
		throw new \Exception("Password Less then 6 char");
	}
	$db->insert ($username, md5($password));
}

function DeleteUser ($db, $username)
{
	if (UserExists($db, $username))
	{
		$db->delete ($username);
	}
}

function ChangePassword ($db, $username, $password)
{
	if (strlen($password) < 6) {
		throw new \Exception("Password Less then 6 char");
	}

	if (UserExists($db, $username)) {
		$db->update ($username, md5($password));
	}
}

function UserExists ($db, $username)
{
	$user = $db->get($username);
	return !empty($user);
}
