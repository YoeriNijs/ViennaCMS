# ViennaCMS
A mightily simple blog system that is based on the powerful Fatfree microframework. At the moment, it is only possible to publish articles, because this is for demonstration purposes only. Hence, there are no plans to extend the current codebase. So, in order to use this in a production environment, you have to implement some (crud) functionalities yourself.

This basic blog system uses the following techniques in order to display awesome content to the world:
  * [Fatfree](https://fatfreeframework.com)
  * [SpectreCSS](https://picturepan2.github.io/spectre/index.html)
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
  - Install the composer dependencies by Using Composer:
    ```sh
    $ php composer.phar install
    ```
  - Finally, serve the content to the world. You can login by typing Admin/admin. At the moment, there is no support for editing the password, because this project is for demonstration purposes only. However, you can easily override the default password by inserting a password that is encrypted by Bcrypt.

# Credits
  * [Logo](https://www.flaticon.com/authors/smashicons)
  * 'No image' by The Noun Project/Gabriele Malaspina
  
# Screenshots
![Screenshot 1](https://i.imgur.com/6ogcEHT.png)
![Screenshot 2](https://i.imgur.com/t7FmqCO.png)
![Screenshot 3](https://i.imgur.com/TxpV9TT.png)
