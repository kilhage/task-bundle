# task-bundle
[![Build Status](https://travis-ci.org/glooby/task-bundle.svg?branch=master)](https://travis-ci.org/glooby/task-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/glooby/task-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/glooby/task-bundle/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/glooby/task-bundle/badge.svg)](https://coveralls.io/github/glooby/task-bundle)
[![Latest Stable Version](https://poser.pugx.org/glooby/task-bundle/version)](https://packagist.org/packages/glooby/task-bundle)
[![Total Downloads](https://poser.pugx.org/glooby/task-bundle/downloads)](https://packagist.org/packages/glooby/task-bundle)
[![License](https://poser.pugx.org/glooby/task-bundle/license)](https://packagist.org/packages/glooby/task-bundle)

Provides a simple framework to manage scheduling and execution of tasks Symfony application.

Prerequisite
-----------------

This bundle requires cron to be installed on the server to be able to execute scheduled tasks

Installation
-----------------

Add the `glooby/task-bundle` package to your `require` section in the `composer.json` file.

``` bash
$ composer require glooby/task-bundle ~1.0
```

Add the GloobyTaskBundle to your application's kernel:

``` php
<?php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Glooby\TaskBundle\GloobyTaskBundle(),
        // ...
    );
    ...
}
```

Create this file /etc/cron.d/glooby_scheduler_run

``` bash
* * * * *  nginx  cd /path/to/project && php app/console scheduler:run --env=prod &> /dev/null 2>&1
```

Documentation
-----------------

### Create a executable Task

To setup a new runnable task you should follow these steps

#### Implement the TaskInterface

example: src/Glooby/Api/TaskBundle/Task/PingTask.php

```php

    class PingTask implements TaskInterface
    {
        /**
         * @inheritdoc
         */
        public function run(array $params = [])
        {
            return 'pong';
        }
    }
```

Add service

```yaml
services:
    glooby_task.ping:
        class: Glooby\TaskBundle\Task\PingTask
```

#### Try run trough cli

```bash

    $ app/console task:run glooby_task.ping

    "pong"

```
### Setup Scheduled task

To setup a new schedule you should follow the steps below

#### Make your service runnable

Follow the steps in [Create a executable Task](#Create a executable Task)

#### Tag your service

By tagging your service with the glooby.scheduled_task
tag it will be treated as a scheduled task

example:

src/Glooby/Api/TaskBundle/Resources/config/services.yml

```yml

services:
    glooby_task.ping:
        class: Glooby\TaskBundle\Task\PingTask
        tags:
            - { name: glooby.scheduled_task }
```

#### Annotate your class

Annotate your class with this annotation: Glooby\TaskBundle\Annotation\Schedule

##### Parameters

###### interval

The first parameter to the annotation is defaulted to the **interval** parameter. In this parameter you configure the
interval that the service should be executed.

The **interval** is a string of five or optional six subexpressions that describe details of the schedule. The syntax is based on the Linux cron daemon definition.
```
    *    *    *    *    *    *
    -    -    -    -    -    -
    |    |    |    |    |    |
    |    |    |    |    |    + year [optional]
    |    |    |    |    +----- day of week (0 - 7) (Sunday=0 or 7)
    |    |    |    +---------- month (1 - 12)
    |    |    +--------------- day of month (1 - 31)
    |    +-------------------- hour (0 - 23)
    +------------------------- min (0 - 59)
```

This is the only required parameter

```php

use Glooby\TaskBundle\Annotation\Schedule;

/**
 * @Schedule("* * * * *")
 */
class PingTask implements TaskInterface
{

```

Here you have several shortcuts that you can use instead for most common use cases

|      value      |   interval   |
|:---------------:|:------------:|
| @yearly         | 0 0 1 1 *    |
| @annually       | 0 0 1 1 *    |
| @monthly        | 0 0 1 * *    |
| @weekly         | 0 0 * * 0    |
| @daily          | 0 0 * * *    |
| @hourly         | 0 * * * *    |
| @semi_hourly    | */30 * * * * |
| @quarter_hourly | */15 * * * * |
| @quarter_hourly | */15 * * * * |
| *               | * * * * *    |

```php

use Glooby\TaskBundle\Annotation\Schedule;

/**
 * @Schedule("@hourly")
 */
class PingTask implements TaskInterface
{

```

###### params

The **params** that should be used when calling

```php

use Glooby\TaskBundle\Annotation\Schedule;

/**
* @Schedule("@weekly", params={"wash": true, "flush": 500})
*/
class CityImporter implements TaskInterface
{

```

###### active

Phe **active** parameter tells if the schedule should be active or not, default=true

```php

use Glooby\TaskBundle\Annotation\Schedule;

/**
* @Schedule("*/6", active=false)
*/
class PingTask implements TaskInterface
{

```

### Sync schedules to the database, this has to be run after each update

```php

app/console schedule:sync

```

Running the Tests
-----------------

Install the dependencies:

``` bash
$ script/bootstrap
```

Then, run the test suite:

``` bash
$ script/test
```

Contributing
------------

See
[CONTRIBUTING](https://github.com/glooby/task-bundle/blob/master/CONTRIBUTING.md)
file.

License
-------

This bundle is released under the MIT license. See the complete license in the
bundle:
[LICENSE.md](https://github.com/glooby/task-bundle/blob/master/LICENSE.md)
