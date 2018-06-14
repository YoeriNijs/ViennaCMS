# ViennaCMS

Demonstration purpose only!

A mightily simple blog system that is based on the powerful Fatfree microframework.

This basic blog system uses the following techniques in order to display awesome content to the world:
  * [Fatfree](https://fatfreeframework.com)
  * [PureCSS](https://purecss.io)
  * [Yarn](https://yarnpkg.com/en/)
  * [Composer](https://getcomposer.org)
  * [TinyMCE](https://www.tinymce.com)
  * [Gulp](https://gulpjs.com)
  * And more...
  
# Installation
  - Install the sql scripts that are provided in the sql directory (no database migration tool added, yet);
  - Install the node dependencies by using Yarn or NPM:
    ```sh
    $ yarn install
    ```
  - Install the composer dependencies by using Composer:
    ```sh
    $ composer install
    ```
  - Build the app by running Gulp (development mode):
    ```sh
    $ gulp build
    ```
  - Ensure that you have created a directory called 'logs' in the root of the project;
  - Ensure that you have changed the permissions (chmod) to 777 for logs and tmp; 
  - Finally, serve the content to the world. You can login by typing Admin/admin. At the moment, there is no support for editing the password, because this project is for demonstration purposes only. However, you can easily override the default password by inserting a password that is encrypted by Bcrypt.
  
  If you want to see the stacktrace in case of any errors, just comment the error part in index.php.

# Updates
  - 03-05-2018: added edit and delete options for blogposts, added support for pages.
  
# Credits
  * 'No image' by The Noun Project/Gabriele Malaspina
  
# Screenshots
![Screenshot 1](https://i.imgur.com/dFBSI4u.png)
![Screenshot 2](https://i.imgur.com/htmsCyy.png)
![Screenshot 3](https://i.imgur.com/ULWWGS0.png)
![Screenshot 5](https://i.imgur.com/BzEHjBf.png)
