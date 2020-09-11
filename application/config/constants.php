<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

// Used for phpass_helper
define('PHPASS_HASH_STRENGTH', 8);
define('PHPASS_HASH_PORTABLE', FALSE);

// User profile images
define('USER_PROFILE_IMAGES_FOLDER',FCPATH .'uploads/user_profile_images/');

// Lead attachments
define('LEAD_ATTACHMENTS_FOLDER',FCPATH . 'uploads/leads/');

//Customer attachments
define('CUSTOMER_ATTACHMENTS_FOLDER',FCPATH . 'uploads/customers/');

//Customer profile images
define('CUSTOMER_PROFILE_IMAGES_FOLDER',FCPATH . 'uploads/customer_profile_images/');

//Quotation attachments
define('QUOTATION_ATTACHMENTS_FOLDER',FCPATH . 'uploads/quotations/');

//Assignment attachments
define('ASSIGNMENT_ATTACHMENTS_FOLDER',FCPATH . 'uploads/assignments/');

//Assignment legitimations
define('ASSIGNMENT_LEGITIMATIONS_FOLDER',FCPATH . 'uploads/legitimations/');

//Lead Quotation attachments
define('LEADQUOTATION_ATTACHMENTS_FOLDER',FCPATH . 'uploads/leadquotations/');

//Ticket attachments
define('TICKET_ATTACHMENTS_FOLDER',FCPATH . 'uploads/tickets/');

//User Document attachments
define('USERDOCUMENT_ATTACHMENTS_FOLDER',FCPATH . 'uploads/user_documents/');

//Customer Document attachments
define('CUSTOMERDOCUMENT_ATTACHMENTS_FOLDER',FCPATH . 'uploads/customer_documents/');

//Customer Internal Document attachments
define('CUSTOMERINTERNALDOCUMENT_ATTACHMENTS_FOLDER',FCPATH . 'uploads/customer_internal_documents/');

//Hardware Assignment attachments
define('HARDWARE_ASSIGNMENT_ATTACHMENTS_FOLDER',FCPATH . 'uploads/hardware_assignments/');

//Info Document attachments
define('INFODOCUMENT_ATTACHMENTS_FOLDER',FCPATH . 'uploads/infodocuments/');

//Hardware Assignment Position Document attachments
define('HARDWARE_ASSIGNMENT_POSITION_DOCUMENTS_FOLDER',FCPATH . 'uploads/hardware_assignment_position_documents/');

// Employee Commissions for Salesman
define('ECOMMISSION_SALESMAN_FIRST_POINTS', 1200);
define('ECOMMISSION_SALESMAN_FIRST_CREDITS', 1500);

define('ECOMMISSION_SALESMAN_SECOND_POINTS', 800);
define('ECOMMISSION_SALESMAN_SECOND_CREDITS', 1000);

define('ECOMMISSION_SALESMAN_REST_POINTS', 400);
define('ECOMMISSION_SALESMAN_REST_CREDITS', 400);
define('ECOMMISSION_SALESMAN_EXTRA_CONDITION_POINTS', 1200);
define('ECOMMISSION_SALESMAN_EXTRA_CONDITION_CREDITS', 1.15);

// Employee Commissions for Pos
define('ECOMMISSION_POS_FIRST_POINTS', 1200);
define('ECOMMISSION_POS_FIRST_CREDITS', 1500);

define('ECOMMISSION_POS_SECOND_POINTS', 800);
define('ECOMMISSION_POS_SECOND_CREDITS', 1000);

define('ECOMMISSION_POS_REST_POINTS', 400);
define('ECOMMISSION_POS_REST_CREDITS', 400);
define('ECOMMISSION_POS_EXTRA_CONDITION_POINTS', 1200);
define('ECOMMISSION_POS_EXTRA_CONDITION_CREDITS', 1.15);

// Quotation PDF
define('QUOTATION_PROVIDER','Vodafone');
define('QUOTATION_PROVIDER_COMPANY','Fritz Becker GmbH & Co KG');
define('QUOTATION_PROVIDER_STREET','Am Königsfeld 15');
define('QUOTATION_PROVIDER_ZIPCODE','33034');
define('QUOTATION_PROVIDER_CITY','Brakel');
define('QUOTATION_TOTAL_PRICE3_MONTHS','24');
define('COMPANY_VAT','19');





// print_r(ENVIRONMENT); exit(0);
/*if (ENVIRONMENT == 'production') {
	define('BASE_URL', 'https://www.xics.de/');
} else {
	define('BASE_URL', 'http://localhost:8080/xics/');
}*/
define('BASE_URL', 'https://www.xics.de/');
// define('BASE_URL', 'http://localhost:8080/xics/'); // comment for live
define('URL_PROJECT_FILES_FOLDER', BASE_URL .'uploads/');
