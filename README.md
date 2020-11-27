# Simple JSON Form Handler for PHP

- Code Version: 1.0
- Author: Benjamin Sommer (Smmr.Dev)
- GitHub: https://github.com/remmosnimajneb

## Overview
This is built as a simple forms handler allowing forms to be made on a site in HTML and this will handle submissions to a MySQL database and then send them via emails. It also supports Conditional emailing (select Sales dept send to sales@example.com, select financing email to financing@example.com).

## Setup
### Configure your SubmitForms.php

	    	DEFINE( 'DB_HOST', '' );				// HostName
		DEFINE( 'DB_NAME', '' );				// Database Name
		DEFINE( 'DB_USER', '' );				// Database Username
		DEFINE( 'DB_PASSWORD', '' );				// Database Password
		DEFINE( 'FORMS_CONFIG_LOC', 'Forms.json');		// Forms Config (If unsure, leave as is)
		DEFINE( 'INSERT_FORM_TO_DB' TRUE);			// Should this backup submissions to DB?
		DEFINE( 'SHOULD_SEND_EMAILED_SUBMISSIONS', TRUE);	// Enable disable sending ALL emails


#### (Optional) Database Setup
If you want to save submissions to the database, import the CreateTable.sql to your Database (Note, this doesn't make a new DB - you can use an existing DB or make a new one.
(Submissions are encoded into JSON)

### Forms Setup
The forms are built with JSON, so make sure your JSON is valid! (Use jsonlint.com to confirm valid JSON)

#### Sample form without conditional emailing

    {
		"ContactSample1": {
			"FormNiceName": "Contact Us",
			"Fields": ["Name", "Email", "Department", "Subject", "Message"],
			"ConditionalEmail": "False",
			"ConditionalField": "Department",
			"Conditions": {
				"": ""
			},
			"EmailTo": "info@example.com",
			"EmailFrom": "info@example.com",
			"EmailSubject": "New Submission - Contact from Example.com!"
		}
	}

#### Sample form WITH conditional emailing

    {
		"ContactSample2": {
			"FormNiceName": "Contact Us",
			"Fields": ["Name", "Email", "Department", "Subject", "Message"],
			"ConditionalEmail": "True",
			"ConditionalField": "Department",
			"Conditions": {
				"Sales": "sales@example.com",
				"Office": "office@example.com",
				"Tech Support": "techsupport@example.com"
			},
			"EmailTo": "",
			"EmailFrom": "info@example.com",
			"EmailSubject": "New Submission - Contact from Example.com!"
		}
	}
	
#### Sample HTML for form

    <form action="Forms/SubmitForm.php" method="POST">
	    <input type="hidden" name="FormName" value="ContactUs"><!-- Value is FormName from JSON -->
	    <input type="text" name="Name" placeholder="Your Name" required="required"><br>
	    <input type="text" name="Email" placeholder=" Your Email" required="required"><br>
	    <select class="select" name="Department" required="required">
	        <option value="0" selected="selected" disabled="disabled">I want to speak to...</option>
	        <option value="Sales">Sales</option>
	        <option value="Office">Office</option>
	        <option value="Tech Support">Tech Support</option>
	    </select><br>
	    <input type="text" placeholder="Subject" name="Subject" required="required"><br>
	    <textarea placeholder="Message" required="required" name="Message"></textarea><br>
	    <button  type="submit" class="site-btn">Send</button>
	</form>



