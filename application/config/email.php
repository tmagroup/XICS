<?php
/*$config = array(
    'useragent' => 'CodeIgniter',
    'protocol' => 'smtp',
    'smtp_crypto' => 'ssl',
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => '465',
    'smtp_user' => 'connectusdemo12@gmail.com',
    'smtp_pass' => 'infoway@connectus',
    'smtp_timeout' => '10',
    'mailtype'  => 'html'
);*/

//Working v1
/*$config = array(
    'useragent' => 'CodeIgniter',
    'protocol' => 'smtp',
    'smtp_host' => 'h2810582.stratoserver.net',
    'smtp_port' => '25',
    'smtp_user' => 'optimus@h2810582.stratoserver.net',
    'smtp_pass' => 'Tx?8d1s1',
    'smtp_timeout' => '10',
    'mailtype'  => 'html'
);*/

//Working v2
$config = array(
    'useragent' => 'CodeIgniter',
    'protocol' => 'smtp',
    'smtp_crypto' => 'ssl',
    'smtp_host' => 'sslout.df.eu',
    'smtp_port' => '465',
    'smtp_user' => 'xics@xics.de',
    // 'smtp_pass' => 'xics2019',
    'smtp_pass' => 'a3Gi8Efu',
    'smtp_timeout' => '10',
    'mailtype'  => 'html'
);



/*$config = array(
    'useragent' => 'CodeIgniter',
    'protocol' => 'smtp',
    'smtp_crypto' => 'ssl',
    'smtp_host' => 'girirajjewellers.co.in',
    'smtp_port' => '465',
    'smtp_user' => 'optimus@girirajjewellers.co.in',
    'smtp_pass' => 'Admin@321!',
    'smtp_timeout' => '10',
    'mailtype'  => 'html'
);*/

/*$config = array(
    'useragent' => 'CodeIgniter',
    'protocol' => 'smtp',
    'smtp_crypto' => 'ssl',
    'smtp_host' => 'girirajjewellers.co.in',
    'smtp_port' => '465',
    'smtp_user' => 'optimus@girirajjewellers.co.in',
    'smtp_pass' => 'Admin@321!',
    'smtp_timeout' => '10',
    'mailtype'  => 'html'
);*/


/*$config['useragent'] = "CodeIgniter";
$config['mailpath'] = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
$config['wordwrap'] = true;
$config['mailtype'] = 'html';
$charset = strtoupper(get_option('smtp_email_charset'));
$charset = trim($charset);
if ($charset == '' || strcasecmp($charset, 'utf8') == 'utf8') {
    $charset = 'utf-8';
}
$config['charset'] = $charset;
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";
$config['protocol'] = get_option('email_protocol');
$config['smtp_host'] = trim(get_option('smtp_host'));
$config['smtp_port'] = trim(get_option('smtp_port'));
$config['smtp_timeout'] = '30';
if (get_option('smtp_username') == '') {
    $config['smtp_user'] = trim(get_option('smtp_email'));
} else {
    $config['smtp_user'] = trim(get_option('smtp_username'));
}
$config['smtp_pass'] = get_instance()->encryption->decrypt(get_option('smtp_password'));
$config['smtp_crypto'] = get_option('smtp_encryption');*/



/*$config['useragent'] = "CodeIgniter";
$config['mailpath'] = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
$config['wordwrap'] = true;
$config['mailtype'] = 'html';
$charset = strtoupper(get_option('smtp_email_charset'));
$charset = trim($charset);
if ($charset == '' || strcasecmp($charset, 'utf8') == 'utf8') {
    $charset = 'utf-8';
}
$config['charset'] = $charset;
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";
$config['protocol'] = get_option('email_protocol');
$config['smtp_host'] = trim(get_option('smtp_host'));
$config['smtp_port'] = trim(get_option('smtp_port'));
$config['smtp_timeout'] = '30';
if (get_option('smtp_username') == '') {
    $config['smtp_user'] = trim(get_option('smtp_email'));
} else {
    $config['smtp_user'] = trim(get_option('smtp_username'));
}
$config['smtp_pass'] = get_instance()->encryption->decrypt(get_option('smtp_password'));
$config['smtp_crypto'] = get_option('smtp_encryption');

if (file_exists(APPPATH . 'config/my_email.php')) {
    include_once(APPPATH . 'config/my_email.php');
}*/