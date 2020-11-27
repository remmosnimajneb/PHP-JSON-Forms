<?php
/********************************
* Project: PHP/JSON Form Handler
* By: Benjamin Sommer - smmr.dev (@remmosnimajneb)
* Code Version: 1.0
***************************************************************************************/

    /*
    * Configure your forms from Forms.json
    */


    /* Set these variables... */

		DEFINE( 'DB_HOST', '' );							// HostName
		DEFINE( 'DB_NAME', '' );							// Database Name
		DEFINE( 'DB_USER', '' );							// Database Username
		DEFINE( 'DB_PASSWORD', '' );							// Database Password
		DEFINE( 'FORMS_CONFIG_LOC', 'Forms.json');					// Forms Config (If unsure, leave as is)
		DEFINE( 'INSERT_FORM_TO_DB' TRUE);						// Should this backup submissions to DB?
		DEFINE( 'SHOULD_SEND_EMAILED_SUBMISSIONS', TRUE);				// Enable disable sending ALL emails

    /* That's it, stop editing! */


    /* Load Configuration */
    $Forms = json_decode(file_get_contents(FORMS_CONFIG_LOC), true);


    /* Now let's see if our submitted form is a form */
    $FieldsForEmail = "";
    if($Forms[$_POST['FormName']] != NULL){

        $Form = $Forms[$_POST['FormName']];

        /* Step 1, make a JSON Encoded Array and Knock into the Database */
        $Submission = new stdClass();

        /* Loop through fields */
        foreach ($Form['Fields'] as $Field) {
            $Submission->$Field = $_POST[$Field];

            $FieldsForEmail .= $Field . " - " . $_POST[$Field] . "\n";
        }

        /* Insert into DB */
        if(INSERT_FORM_TO_DB){
        	/* Pull the Database Connection */
    		$DatabaseConnection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . "", "" . DB_USER . "", "" . DB_PASSWORD . "");
    		$SQL = "INSERT INTO `Submissions` (FormName, SubmissionContent, IPAddress) VALUES ('" . EscapeSQLEntry($_POST['FormName']) . "', '" . json_encode($Submission) . "', '" . GetUserIP() . "')";
	        $STM = $DatabaseConnection->prepare($SQL);
	        $STM->execute();
        }

        /* Send Email */
        if(SHOULD_SEND_EMAILED_SUBMISSIONS){

        	/* Send Email */

	            $From = $Form['EmailFrom'];
	            $Subject = $Form['EmailSubject'];

	            /* To */
	            $SendTo = $Form['EmailTo'];

	                /* If enabled Conditions */
	                if($Form['ConditionalEmail'] == "True"){
	                    /* Get Conditional Value */
	                    $ConditionalFieldValue = $_POST[$Form["ConditionalField"]];
	                    /* Conditions */
	                    $Conditions = $Form["Conditions"];
	                    /* Sent To */
	                    $SendTo = $Conditions[$ConditionalFieldValue];
	                }

	            /* Generate Message Body */
	            $MessageBody = "A new form submission - " . $Form['FormNiceName'] . " - has been submitted to your site! See the form details below!\n\n" . $FieldsForEmail;

	        /* Mail! */
	        $Headers = "From: " . $From;
	        mail($SendTo, $Subject, $MessageBody, $Headers);

        }

        /* Go back to referring page */
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?SubmissionStatus=1');
    }


    /* Helper Functions */
    
    /*
    * Get Client IP 
    */
    function GetUserIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    /*
    * Safe input to MySQL
    */
    function EscapeSQLEntry($data) {
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlspecialchars($data);
       $data = addslashes($data);
       return $data;
    }
