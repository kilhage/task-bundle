# Schedules

To setup a new schedule you should follow the steps below

## 1) Make your service runnable

Follow the steps in task.md

## 2) Tag your service

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

## 3) Annotate your class

Annotate your class with this annotation: Glooby\TaskBundle\Annotation\Schedule

### Parameters

#### interval

The first parameter to the annotation is defaulted to the "interval" parameter. In this parameter you configure the
interval that the service should be executed.

the following library is used to parse the crontab expression:

https://github.com/mtdowling/cron-expression

This is the only required parameter

```php

use Glooby\TaskBundle\Annotation\Schedule;

/**
 * @Schedule("*")
 */
class PingTask implements TaskInterface
{

```

#### params

The params that should be used when calling

```php

use Glooby\TaskBundle\Annotation\Schedule;

/**
* @Schedule("@weekly", params={"wash": true, "flush": 500})
*/
class CityImporter implements TaskInterface
{

```

#### active

the active parameter tells if the schedule should be active or not, default=true

```php

use Glooby\TaskBundle\Annotation\Schedule;

/**
* @Schedule("*/6", active=false)
*/
class PingTask implements TaskInterface
{

```

#### timeout

The timeout of the task in seconds (NOT FULLY IMPLEMENTED)

```php

use Glooby\TaskBundle\Annotation\Schedule;

/**
* @Schedule("@weekly", timeout=3600)
*/
class PingTask implements TaskInterface
{

```

## 4) Notify job-queue about changed schedules

```php

app/console schedule:sync

```
