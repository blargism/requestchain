requestchain
============

A simple library that handles requests.  Really, really, simple.

## Manifesto

So... I got really sick of the really, REALLY big frameworks that do little more than route requests and call the database.  Sure, there's lots of features in these frameworks, but most of those features are unneeded most of the time.  Mostly, you just need to get some data from somewhere and then send it to the thing that asked for it.  That's not nearly as hard as what most frameworks make it.

Enter requestchain.  It's certainly *not* a full featured framework.  In fact, it's really more of a toolkit than a framework.  It has the ability to take a request and route it to a request handler, with the ability to add features to the chain, aka middleware.

This tool follows three basic core values:

1. The tool needs to be very simple.
2. The tool needs to be flexible.
3. The tool is not there to enforce good programming practices, that's the developer's job.

Request chain is supposed to be thin, unashamedly procedural, and little more than a blank canvas. After the request is routed, the user can do whatever they want. Well, that's not true, they can even mess with the request using middleware.  Really, it's just a tool that makes handling requests somewhat standardized. Enjoy.

## Warning

Request chain does not do anything to prevent you from being dumb.  More than with large frameworks, good programming practices are important.

Ok, enough prattling on... documentation.

# Handling Requests

Request chain is a chain of middleware ending in a handling function call.  There are four constructs at work: middleware, helpers, handlers and views.

## Middleware

These functions are simple in nature.  They take an array of parameters `$params` and the configuration for the request from `config.php` in `$config`. The parameters are then returned at the end of the middleware function.  The configuration should remain unchanged (note this is not enforced, just recommended).  Here's an example:

```php
<?php

function example_middleware($params, $config) {
  $a_value = $config['some_config_value'];
  $params['a_key'] = $a_value;
  
  return $params;
}
```

Obviously, this middleware is pretty close to worthless, but you see the structure.  A few uses for middleware might be handling a custom session using memcache or sanitizing user input.

## Helpers

These are just functions.  That's it. Nothing more special than that.  They can either be required each request or you can include it for a specific request in the handler file.

## Handlers

This is a special version of middleware.  There are two topics to cover here.  Getting to a handler file, and how it handles requests.

### Getting to a Handler

Handlers are all kept in the `handlers` directory.  However, routing is defined in the middleware.  A basic router is included, but can be replaced with more advanced regex routing.  The path to the handler is configured by the router.  As an aside, the basic router also defines the path to the view and layout.  The core router file includes the handler based on the `$params['handler_path']`.

### Handling Requests

Requests are handled with RESTfully.  The four restful verbs `GET`, `PUT`, `POST`, and `DELETE` are respected.  However, the routes only need handle the verbs that need to be supported.  That way if a `DELETE` request comes in, it doesn't need to be handled if it should not be.  These methods are named by convention as follows

```
GET    => get_request($params, $config)
PUT    => put_request($params, $config)
POST   => post_request($params, $config)
DELETE => delete_request($params, $config)
```

Each method accepts the parameters from the rest of the middleware as well as the config.  The one difference is that it is expected to return an associative array of values to be used by the views and layouts.  These are converted to top level variables so if you do this:

```php
<?php

function get_request($params, $config) {
  return ['a' => 'string a', 'b' => 'string b'];
}
```

You will have access to this in the views:

```php
<h3>This will be 'string a'</h3>
<p><?php echo $a; ?></a>

<h3>This will be 'string b'</h3>
<p><?php echo $b; ?></a>
```

Simple, easy and fun.  Note that you can add anything you want into those variables, up to and including anonymous functions, objects, or whatever.  Be warned, numbered arrays will break things and the base router won't check first.

## Views

I wasn't sure if I event wanted to include views, but since we almost always want to send output of some kind, it made sense to include a VERY simple view handler as well.  Right now it's burned in, but slated in the near future I'll make them more portable.  Views come in two breeds:

### Views

These are just `.phtml` files with a mixture of php and markup.  As with anything else, you can be a miserable PHP programmer and put whatever you what here. You can also do the right thing and make a clean separation of concerns.  The tool won't enforce either.

As stated before, you have access to what is returned in array form from the handler as top level variables (see the "Handlers" section).  You just build the view like any normal PHP markup file. Example:

```php
<p>I like simple things like '<?= $a_variable_from_the_handler; ?>'.</p>
```

### Layouts

These wrap the main views, they do so by inserting the output of the main view into itself using the variable `$page_contents`.  They have access to everything that the main view does.  Here's an example:

```php
<!doctype html>
<html>
        <head>
                <title>HEY!</title>
        </head>
        <body>
                <?= $page_contents; ?>
        </body>
</html>
```

Mixing the view from the previous section and this you would get the following:


```php
<!doctype html>
<html>
        <head>
                <title>HEY!</title>
        </head>
        <body>
                <p>I like simple things like 'math'.</p>
        </body>
</html>
```

Happy programming.

The MIT License (MIT)

Copyright (c) 2013 Joe Mills

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
