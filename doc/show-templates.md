## Templates used on the page

In order to better edit the templates, I need to know which templates the page uses.

So far, I'm doing this by:

## I'll set up development mode

In the configuration file `/config/vufind/httpd-vufind.conf` I will set up a development environment

\# Uncomment this line to put VuFind into development mode in order to see more detailed messages:

SetEnv VUFIND_ENV development

## I'm modifying vendor\laminas\... - > PhpRenderer.php

In the file `\vendor\laminas\laminas-view\src\Renderer\PhpRenderer.php`

find line:

`$includeReturn = include $this->__file;`

and I will replace it with:

`$templateFileName = $this->__file;`
`$tplDebug = (isset($_SERVER['VUFIND_ENV']) && $_SERVER['VUFIND_ENV']=='development');`
`if ($tplDebug) { echo "\n<!-- BEGIN TEMPLATE: $templateFileName -->\n"; }`
`$includeReturn = include $this->__file;`
`if ($tplDebug) { echo "\n<!-- END TEMPLATE: $templateFileName -->\n"; }`

## Result

HTML comments are then generated into the source code with the path and name of the template, indicating the beginning and end of the template.

`<!-- BEGIN TEMPLATE: D:\www\vufind\themes\bootstrap3\templates\search\filters.phtml -->`
`<!-- END TEMPLATE: D:\www\vufind\themes\bootstrap3\templates\search\filters.phtml -->`
