# PHP Restful HMAC Webservice
A simple restful webservice based on Amazon HMAC authentication.

# Installation & Configuration
1 - First of all download the whole content and unzip into your web root diretory.

2 - Open .htaccess file and change the default charset [AddDefaultCharset utf-8] if you want.

3 - Edit the Config.xml file with your options.

- general->company_name: Set the company name;
- general->application_name: Set the name of the application;
- general->application_state: Set if the application is in Development (D) or Production (P) this enable or disable errors;
- general->session_enabled: Set if the $_SESSION vars are enabled or disabled. Use true or false;
- general->secure_http: Set if the application is running in HTTPS. Use true or false;
- general->application_charset: Set the application charset;
- general->application_timezone: Set the application timezone. (http://php.net/manual/en/timezones.php);
- router->application_localhost: Set if the application is running on localhost. This basically means if you have a base url or not. Example: domain.com is not localhost but domain.com/myapp is. Use true or false;
- router->base_url: Set the base url of the application, if you are not running in localhost just use a single slash / ;
- service->request_timeout: The request timeout that expires the call. Use it in milliseconds.
- service->authorization_prefix: The authorization prefix can be any string but the client application must use the same header prefix;
- service->auth_name_label: Name of the column in your user table that is the username. Example: (email, login, username etc..);
- service->auth_password_label: Name of the column in your user table that is the password. Example: (password, secretkey etc..);
- service->auth_user_id_label: Name of the column in your user table that is the id. Example: (id, ID, user_id etc..);
- service->user_table: Name of your user table. Example: (user, client etc..);
- service->user_active_label: Name of the column in your user table that is the active. Example: (active, status etc..);
- database: Here you can add any desired amount and types of database that you want. Since PDO can handle it. For each database you must spefify the key that calls it, in the default example we will use db1 but can be any key.
- database->db1->enabled: Set if the databse is enabled or disabled . Use true or false;
- database->db1->dbms: Set the database management system for the connection. If you want to check the full supported dbms open the PDOHandler.class.php in the Library/Toolkit path in the method dsn_switcher();
- database->db1->host: The database host;
- database->db1->user: The database user;
- database->db1->password: The database password;
- database->db1->database_name: The database name;
