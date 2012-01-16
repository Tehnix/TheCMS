##Information / README / Documentation / Installation
####0x01        Intro
TheCMS is a PHP based CMS. Mainly as a learn-by-doing project, but is quite   potent for actual use. By looking at projects like Drupal and Django, simply taking some of the features we like and trying to avoid those we don't we have
created a somewhat full CMS solution that is also easily expandable.

####0x02        License
Is found in the license file. But also here:
<pre>
    Copyright (C) 2012 

    This program is free software; you can redistribute it and/or modify it 
    under the terms of the GNU General Public License as published by the Free 
    Software Foundation; either version 2 of the License, or (at your option) 
    any later version.

    This program is distributed in the hope that it will be useful, but WITHOUT 
    ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or 
    FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for 
    more details.

    You should have received a copy of the GNU General Public License along with 
    this program; if not, write to the Free Software Foundation, Inc., 59 Temple 
    Place, Suite 330, Boston, MA 02111-1307 USA.
</pre>


####0x03        Installation
1. First clone the repos

    <code>$ git clone git@github.com:ZealDev/TheCMS.git</code>

2. Visit the index.php page once, and a settings.php file should be created.
    You will need to fill this out with your settings.
3. Your good to go!

- - -

##Files and folders

####1x01        manage.php
In this document resides the main driveforece of TheCMS. The database class,  
module class, templates class and the various class's that handles the login  
system.

####1x02        settings.php
Here is all the constants that needs defining throughout TheCMS. From database
connection to URLs and default mailer behavior.

####1x03        urls.php
Here is the default url handling (derived from .htaccess's urlrewrite, that   
puts everything behind a ?handle= query). The path to admin panel, and main   
application is defined here.

####1x04        INSTALL.sql
Our sql information for the basic setup of TheCMS

####1x05        modules/
All modules go in here, organized in folders. The structure goes:

<pre>
    modules/mymodule/model.php # Holds the core of the module

    modules/mymodule/urls.php # Handles all the urls and passes to view.php

    modules/mymodule/view.php # What to display to the user

    modules/mymodule/admin.php # What to display in admin

    modules/mymodule/INSTALL.sql # All SQL information

    modules/mymodule/DELETE.sql # Deletion information for SQL

    modules/mymodule/style.css # CSS to be included with the module
</pre>


None of these files are required, but their names must match their use, else  
it will result in wrong order of includes and can essentially cause some nice 
500's ;)...

####1x06        templates/
In here we create our 'themes'. These can override existing page in the 
default folder.

####1x07        resources/
All of our resource files. This includes images, css, javascript but also     
error documents and uploads.

####1x08        cgi-bin/
Our backup scripts written in python

####1x09        _backup/
Location for all our backups. Server backups are located in the main folder  
saved as .zip files ,and database backups are located in: _backup/database/   
with .sql extension.