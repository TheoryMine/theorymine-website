----------------------------------------------------------------------------
  TheoryMine Website
----------------------------------------------------------------------------
  (C) Lucas Dixon 2010
  TheoryMine
----------------------------------------------------------------------------

The different files and directories:

index.php -- this is the initial php page which includes other pages,
as and when needed, from the pages subdirectory. The reason for doing
it this way is that you can make further subdirectories the pages
subdirectory, but treat them all as having the same location. That way
you can strutcure files, but you never need relative links from
different locations, e.g. you never need a link "../images/logo.png",
or "include '../utils/image_specific_tools.php'", but you can still
have structure specific stuff like "pages/login/images/hello.png" or
"include 'pages/login/utils/auth_tools.php'". Another advantage of
this structuring is that you can write the geneic login/session stuff
in one location, and it works for all pages visited. Otherwise you
have to write the appropriate (and different) includes in different
pages.

pages -- this subdirectory holds different kinds of pages that might
be visited. For example, login stuff, admin stuf, point view, user
profile, etc.

utils -- this holds generic useful php utils. 

prefs -- when running this holds the global preferences which, for
instance, inicate where public-hidden prefs are stored. By default
there is a template file here. Do not commit a prefs.php file to this
directory. We want to have the svn checkout provide a read-to-use
unconfigured setup.

setup -- holds some files for the sql database setup. By default we
have an .htaccess that restricts web-access to this directory, just in
case you do use it for the safe sql prefs directory. If this is the
case then no other users should be able to see your webpages -
probably not true - but this is often the necessary setup for many
ISPs/servers.

paypal -- holds the notify script for instant payment notification

debug -- some debug scripts 

cancel -- holds redirect script for cancelled paypal payments. 
success -- holds redirect script for successfull paypal payments. 

css -- holds global style files 

docs -- holds further documentation. 

images -- holds useful global images

