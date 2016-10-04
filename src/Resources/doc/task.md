# Task

To setup a new runnable task you should follow these steps

## 1) implement the TaskInterface

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

## 2) try run trough cli

```bash

    $ app/console task:run glooby_task.ping

    "pong"

```


## 2) call the service trough the api

```bash

$ curl -XPOST\
    -H "Accept: application/json"\
    -H "Content-Type: application/json"\
    -H "Authorization: Bearer TOKEN"\
    -d '{"service": "glooby_task.ping"}'\
    http://api.glooby.com/Task/run

    "pong"

```
