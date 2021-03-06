<?php
$from = $_POST["email"];
$sendTo = 'devcssupport@coderschool.com';
// if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 'On');
// subject of the email
$subject = 'Developer Facebook register';
// Comment out the above line if not using Composer
require("sendgrid-php.php");

$email = new \SendGrid\Mail\Mail(); 
$cvsData = "";
// form field names and their translations.
// array variable name => Text to appear in the email
$fields = array(
    'firstName' => 'firstName',
    'lastName' => 'lastName',
    'gender' => 'gender',
    'phone' => 'Phone',
    'email' => 'Email',
    'facebook' => 'facebook',
    'github' => 'github',
    'radio' => 'radio',
    'track' => 'track',
    'another_track' => 'another_track',
    'interested' => 'interested',
    'batch' => 'batch',
    'impresive' => 'impresive',
    'completed' => 'completed',
    'available' => 'available',
    'developer_circles' => 'developer_circles',
    'city_developer_circles' => 'city_developer_circles',
    'how_did_you_hear' => 'how_did_you_hear',
); 

// message that will be displayed when everything is OK :)
$okMessage = 'Contact form successfully submitted. Thank you, I will get back to you soon!';

// If something goes wrong, we will display this message.
$errorMessage = 'There was an error while submitting the form. Please try again later';

/*
 *  LET'S DO THE SENDING
 */



try
{

    if(count($_POST) == 0) throw new \Exception('Form is empty');
            
    $emailText = "You have a new message from Facebook developer register <br/>============================= <br/>";

    foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, include it in the email 
        if (isset($fields[$key])) {
            $emailText .= "<strong>$fields[$key]</strong>: $value\n" . "<br/>";
            $cvsData .=  $value . ",";
        }
    }

    // All the neccessary headers for the email.
    $headers = array('Content-Type: text/plain; charset="UTF-8";',
        'From: ' . $from,
        'Reply-To: ' . $from,
        'Return-Path: ' . $from,
    );
    
    // Send email
  //  mail($sendTo, $subject, $emailText, implode("\n", $headers));
    $names  = $_POST["firstName"] . " " . $_POST["lastName"];
    $email->setFrom($_POST["email"], $names);
    $email->setSubject("fill in your information developer facebook");
    $email->addTo("huycuong0610@gmail.com", "Example User");
    $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
    $email->addContent(
        "text/html", $emailText
    );
  //save to csv
  $fp = fopen("formTest.csv","a"); // $fp is now the file pointer to file $filename


    if($fp){
        fwrite($fp,$cvsData . "\r\n"); // Write information to the file
        fclose($fp); // Close the file
    }
  $sendgrid = new \SendGrid('');
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}
    $responseArray = array('type' => 'success', 'message' => $okMessage);
}
catch (\Exception $e)
{
     echo 'Caught exception: '. $e->getMessage() ."\n";
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}


// if requested by AJAX request return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);

    header('Content-Type: application/json');

    echo $encoded;
}
// else just display the message
else {
    echo $responseArray['message'];
}