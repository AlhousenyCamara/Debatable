<?php
// connecting to Database
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'debatable';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	// If Database fail to connect display
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['username'], $_POST['password'], $_POST['firstName'],$_POST['lastName'])) {
	// Did not get the data that should have been sent.
	exit('Please complete the registration form!');
}
// Making sure the submitted values are not empty.
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['firstName'])|| empty($_POST['lastName'])) {
	// if One or more values are empty.
	exit('Please complete the registration form');
}


//  need to see if username already exists.
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
    if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
        exit('Username is not valid!');
    }
    if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
        exit('Password must be between 5 and 20 characters long!');
    }
	
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	// Storing  the result to check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// Username already exists
		echo 'Username exists, please choose another!';
	} else {
// Username doesnt exists, insert new account
if ($stmt = $con->prepare('INSERT INTO register (username, password, firstName, lastName) VALUES (?, ?, ?,?)')) {
	// We do not want to expose passwords in our database.
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$stmt->bind_param('sss', $_POST['username'], $password, $_POST['firstName'],$_POST['lastName']);
	$stmt->execute();
	echo 'You have successfully registered, you can now login!';
} else {
	
	echo 'Could not prepare statement!';
}	}
	$stmt->close();
} else {

	echo 'Could not prepare statement!';
}
$con->close();


?>