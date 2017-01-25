# php-htaccess-redirects-parser

Simple PHP Script, takes an CSV File with Source / Target URLs, and renders it to the given Format.
Includes a docker configuration to run it instantly

Check /app/lib/RedirectRulesCreator.php for Setup options and
/app/index.php how to use it

To validate the output, use
http://www.htaccesscheck.com/


# Example Output (from testdata)

```
RedirectMatch 301 /my/src/path/ https://www.mytargetdomain.de/path/to/new/page/

RedirectMatch 301 /my/src/path2/sub/ https://www.mytargetdomain.de/path/to/new/page/2/

RewriteCond %{HTTP_HOST} ^localhost\:8000$ [NC]
RewriteCond %{REQUEST_URI} /my/src/path/index\.php
RewriteCond %{QUERY_STRING} id=12
RewriteCond %{QUERY_STRING} category=35
RewriteRule .* https\://www\.mytargetdomain\.de/path/to/new/page/? [QSD,R=301,L]

RewriteCond %{HTTP_HOST} ^www\.test\.com$ [NC]
RewriteCond %{REQUEST_URI} /my/src/path/index\.php
RewriteCond %{QUERY_STRING} id=12
RewriteCond %{QUERY_STRING} category=35
RewriteRule .* https\://www\.mytargetdomain\.de/path/to/new/page/? [QSD,R=301,L]
```
