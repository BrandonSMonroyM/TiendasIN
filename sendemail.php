<?php
	header('Content-type: application/json');
	$status = array(
		'type'=>'success',
		'message'=>'Email sent!'
	);

    $name = @trim(stripslashes($_POST['name'])); 
    $email = @trim(stripslashes($_POST['email']));
    $message = @trim(stripslashes($_POST['message'])); 

    $email_from = $email;
    $email_to = 'web@webpuro.net';
    
    $body = 'Name: ' . $name . "\n\n" . 'Email: ' . $email . "\n\n" . 'Message: ' . $message;

    $success = @mail($email_to, $body, 'From: <'.$email_from.'>');
    echo json_encode($status);
    die; 