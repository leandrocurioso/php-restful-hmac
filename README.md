# PHP Restful HMAC Webservice
A simple restful webservice based on Amazon HMAC authentication.

# Installation & Configuration
1 - First of all download the whole content and unzip into your web root diretory.

2 - Open .htaccess file and change the default charset [AddDefaultCharset utf-8] if you want.

3 - Edit the Config.xml file with your options.

- general->company_name: Set the company name;
- general->application_name: Set the name of the application;
- general->application_state: Set if the application is in Development (D) or Production (P) this enable or disable errors;
- general->session_enabled: Set if the $_SESSION vars are enable disabled. Use true or false;
- general->secure_http: Set if the application is running in HTTPS. Use true or false;
- general->application_charset: Set the application charset;
- general->application_timezone: Set the application timezone. (http://php.net/manual/en/timezones.php);

- router->application_localhost: Set if the application is running on localhost. Use true or false;
- router->base_url: Set the base url of the application, if you are not running in localhost just use a single slash / ;

- service->base_url: Set the base url of the application, if you are not running in localhost just use a single slash / ;



