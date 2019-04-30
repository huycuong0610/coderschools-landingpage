<?php
$from = $_POST["email"];
$sendTo = 'devcssupport@coderschool.com';

// subject of the email
$subject = 'Developer Facebook register';
// Comment out the above line if not using Composer
require("sendgrid-php.php");

$email = new \SendGrid\Mail\Mail(); 




try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}
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

// if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
error_reporting(E_ALL & ~E_NOTICE);

try
{

    if(count($_POST) == 0) throw new \Exception('Form is empty');
            
    $emailText = "You have a new message from your contact form\n=============================\n";

    foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, include it in the email 
        if (isset($fields[$key])) {
            $emailText .= "$fields[$key]: $value\n";
        }
    }

    // All the neccessary headers for the email.
    $headers = array('Content-Type: text/plain; charset="UTF-8";',
        'From: ' . $from,
        'Reply-To: ' . $from,
        'Return-Path: ' . $from,
    );
    
    // Send email
    mail($sendTo, $subject, $emailText, implode("\n", $headers));

    $email->setFrom($_POST["email"], "Example User");
    $email->setSubject("fill in your information developer facebook");
    $email->addTo("huycuong0610@gmail.com", "Example User");
    $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
    $email->addContent(
        "text/html", $emailText
    );
    $sendgrid = new \SendGrid(getenv('SG.LJGm6ROHSwCpSOb7vGYTkg.32N7zm8IKP6G2R_D7ubWqnm44z0-ie4VQsxSo2uon3'));

    $responseArray = array('type' => 'success', 'message' => $okMessage);
}
catch (\Exception $e)
{
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