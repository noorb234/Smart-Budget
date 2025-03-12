<html>

<head>
  <title>Process Registration</title>
</head>

<fieldset>
</fieldset>
<body id = signupBody>
<?php
require_once 'config.php';
	
	try
	{
		$pdo = new PDO($attr, $user, $pass, $opts);
	}
	catch (PDOException $e)
	{
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}

$firstName ="";
$email = "";
$lastName = "";
$phone = "";
$username = "";
$password = "";
$confirmPassword = "";
$security_question_1 = "";
$security_answer_1 = "";
$security_question_2 = "";
$security_answer_2 = "";

//testing
$preference = "dark mode";

//Checks if fields are set and sanitizes input from user
if (isset($_POST['firstName']))
    $firstName = htmlspecialchars($_POST["firstName"]);
if (isset($_POST['email']))
    $email = htmlspecialchars($_POST["email"]);
if (isset($_POST['lastName']))
    $lastName = htmlspecialchars($_POST["lastName"]);
if (isset($_POST['phone']))
    $phone = htmlspecialchars($_POST["phone"]);
if (isset($_POST['username']))
    $username = htmlspecialchars($_POST["username"]);
if (isset($_POST['password']))
    $password = htmlspecialchars($_POST["password"]);
if (isset($_POST['confirmPassword']))
    $confirmPassword = htmlspecialchars($_POST["confirmPassword"]);
if (isset($_POST['security_question_1']))
    $security_question_1 = htmlspecialchars($_POST["security_question_1"]);
if (isset($_POST['security_answer_1']))
    $security_answer_1 = htmlspecialchars($_POST["security_answer_1"]);
if (isset($_POST['security_question_2']))
    $security_question_2 = htmlspecialchars($_POST["security_question_2"]);
if (isset($_POST['security_answer_2']))
    $security_answer_2 = htmlspecialchars($_POST["security_answer_2"]);

//Calls validation functions to validate information
$fail  = validate_firstname($firstName);
$fail .= validate_email($pdo, $email);
$fail .= validate_lastName($lastName);
$fail .= validate_phone($phone);
$fail .= validate_username($pdo, $username);
$fail .= validate_password($password, $confirmPassword);
$fail .= validate_security_questions($security_question_1, $security_question_2);
$fail .= validate_security_answers($security_answer_1, $security_answer_2);

//If no errors hash the password and call add_user function to add user to database
if ($fail == "")
  {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
	add_user($pdo, $firstName, $lastName, $email, $phone, $username, $password_hash, $security_question_1, $security_answer_1, $security_question_2, $security_answer_2, $preference);
	header('Location: Login.php');
	exit;
  }
else{ //Else return error message and provide link to return to registration page
	echo "<label><br><br>$fail<br><br>Please <a href='signup.php'>click here</a> to return to signup page and try again.</label>";
}

//Validates first name entry
function validate_firstName($fn){
	if ($fn == ""){
		return "No first name was entered<br>";
	}
}

//Validates email entry
function validate_email($pdo, $em)
  {
	$stmt = $pdo->prepare('SELECT COUNT(email) AS count FROM users WHERE email = ?');
	
	$stmt->bindParam(1, $em, PDO::PARAM_STR, 128);
	
	$stmt->execute();
	
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	
    if ($em == ""){
		return "No Email was entered<br>";
	}
	else if (!((strpos($em, ".") > 0) &&
                 (strpos($em, "@") > 0)) ||
                  preg_match("/[^a-zA-Z0-9.@_-]/", $em)){
					  return "The Email address is invalid<br>";
				  }
	else if($result['count'] == 1){
		return "This email is already registered with an account";
	}
    return "";
  }
  
//Validates first name entry
function validate_lastName($ln){
	if ($ln == ""){
		return "No last name was entered<br>";
	}
}

//Validates phone number entry
function validate_phone($pn){
	if ($pn == ""){
		return "No phone was entered<br>";
	}
	//else if (!((strpos($pn, "-") > 0)) || preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $pn)){
	//	return "The phone number is invalid<br>";
	//}
	return "";
}

//Validates username entry and checks to see if user exists in database already
function validate_username($pdo, $un){
	$stmt = $pdo->prepare('SELECT COUNT(username) AS count FROM users WHERE username = ?');
	
	$stmt->bindParam(1, $un, PDO::PARAM_STR, 128);
	
	$stmt->execute();
	
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if ($un == ""){
		return "No Username was entered<br>";
	}
    else if (strlen($un) < 5){
		return "Usernames must be at least 5 characters<br>";
	}
    else if (preg_match("/[^a-zA-Z0-9_-]/", $un)){
		return "Only letters, numbers, - and _ in usernames<br>";
	}
	else if($result['count'] == 1){
		return "Username is already taken.";
	}
    return "";
}

//Validates password and confirm password entries
function validate_password($pwd, $con_pwd)
  {
    if ($pwd == ""){
		return "No Password was entered<br>";
	}
    else if (strlen($pwd) < 6){
      return "Passwords must be at least 6 characters<br>";
	}
    else if (!preg_match("/[a-z]/", $pwd) ||
             !preg_match("/[A-Z]/", $pwd) ||
             !preg_match("/[0-9]/", $pwd)){
				 return "Passwords require 1 each of a-z, A-Z and 0-9<br>";
			 }
	else if ($pwd !== $con_pwd)
		return "Passwords do not match";
    return "";
  }
  
//Validates security question entries
function validate_security_questions($sq1, $sq2){
	if ($sq1 == ""){
		return "No security question 1 was chosen<br>";
	}
	else if ($sq2 == ""){
		return "No security question 2 was chosen<br>";
	}
}

//Validates security answer entries
function validate_security_answers($sa1, $sa2){
	if ($sa1 == ""){
		return "No security answer 1 was chosen<br>";
	}
	else if ($sa2 == ""){
		return "No security answer 2 was chosen<br>";
	}
}

//Adds user to the database using a prepared statement and provides link for user to login
function add_user($pdo, $fn, $ln, $em, $pn, $un, $pw, $sq1, $sa1, $sq2, $sa2, $prf)
{
    $stmt = $pdo->prepare('INSERT INTO users (firstName, lastName, email, phone, username, password, security_question_1, security_answer_1, security_question_2, security_answer_2, preferences) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

    $stmt->bindParam(1, $fn, PDO::PARAM_STR, 128);
	  $stmt->bindParam(2, $ln, PDO::PARAM_STR, 128);
    $stmt->bindParam(3, $em, PDO::PARAM_STR, 255);
	  $stmt->bindParam(4, $pn, PDO::PARAM_STR, 12);
	  $stmt->bindParam(5, $un, PDO::PARAM_STR, 128);
    $stmt->bindParam(6, $pw, PDO::PARAM_STR, 255);
	  $stmt->bindParam(7, $sq1, PDO::PARAM_STR, 255);
	  $stmt->bindParam(8, $sa1, PDO::PARAM_STR, 255);
	  $stmt->bindParam(9, $sq2, PDO::PARAM_STR, 255);
	  $stmt->bindParam(10, $sa2, PDO::PARAM_STR, 255);
	  $stmt->bindParam(11, $prf, PDO::PARAM_STR, 255);

    $stmt->execute([$fn, $ln, $em, $pn, $un, $pw, $sq1, $sa1, $sq2, $sa2, $prf]);
	
	echo "<br><br><label>Registration successful. Please <a href='login.php'>click here</a> to login.</label>";
}

?>


</body>
</html>