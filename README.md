Wan Shi Tong
============

Wan Shi Tong, named afte the knowledge-collecting spirit from Avatar: The Last
Airbender, is a web interface to a bookstore database.

Project Structure
-----------------

Wan Shi Tong roughly follows an MVC stucture. Controllers and views are fairly
typical, but traditional thick models are replaced with thin data storage
objects instantiated on the fly by repository classes accessing the database.

* `engine/`: The heavy-lifting code.
    * `wanshitong/`: Project-specific components.
        * `controllers/`: Request controller classes.
        * `models/`: Repository classes to access the database.
        * `views/`: View classes.
* `static/`: Static resources (images, stylesheets, JavaScript, etc.)
* `templates/`: Template files used by views.

Class layout follows the proposed PSR-4, and `engine/` can be considered the
base directory; that is, the class `\wanshitong\controllers\ExampleController`
would be physically located at
`engine/wanshitong/controllers/ExampleController.php`.

Project Components
------------------

### Routing

The router, `\wanshitong\Router`, is configured with a series of routes and
destination controllers. The various methods of defining routes are best
described by the class comments, but suffice to say that the actual routing
is configured in `index.php`. Route destinations are given as closures so
involved objects are not instantiated prior to being required as a page load
time-saving measure.

### Controllers

As a rule, once having been routed to, controllers are responsible for both
deciding what ought to be displayed on a page and rendering it (although the
act of rendering is probably best delegated to a view). Controllers extend the
abstract class `\wanshitong\controllers\Controller`. This requires the
implementation of a `get` method, which is responsible for handling `GET`
requests. Controllers may optionally implement a `post` method if they need to
perform a different operation for `POST` requests; if not implemented, the `get`
method is used as a fallback for `POST` requests.

### Views

The most useful implementation of the low-level `\wanshitong\views\View`
interface is the `\wanshitong\views\PageView` class. This class receives a page
title and main content and wraps them in the global template. The exception to
this rule is the `\wanshitong\views\OrderView`, which, in light of its intended
printability, uses a separate, more barebones template.

Views in the project are fairly thin and act mostly as a means of passing data
from controllers into one or more templates. In a few cases, flags can be set on
the view to affect the template, but the majority of conditional logic is found
in the templates themselves and is effected based on varying input.

### Templates ###

Templates, as loaded by `\wanshitong\TemplateManager`, leverage the use of PHP
as a templating language. An instance of `\wanshitong\Template` is constructed
with knowledge of a template file and populated with key/value pairs; upon being
rendered, values are made available within the template as local variables based
on their key.
