# Installation
Copy or clone the files to your local
run `composer install` from a terminal window if required
Setup your local webserver to hosts backend-course.local
Create a local database
Create a config.php file in the root with the following contents

```
<?php

// Set your db connection here
define( 'DB_HOST', 'localhost' );
define( 'DB_NAME', 'backend-course' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );

?>
```

Load the site 
Click the hamburger menu and choose settings from the popout menu
Click Populate data, you should see the Current records update to show 100 user rows
Click back to home to see the user data
Click on the plus to see the user information and course enrolment information