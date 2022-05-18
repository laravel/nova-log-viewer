![Nova Log Viewer](https://banners.beyondco.de/Nova%20Log%20Viewer.png?theme=light&packageManager=composer+require&packageName=laravel%2Fnova-log-viewer&pattern=cage&style=style_1&description=A+Laravel+Nova+tool+for+viewing+your+application+logs&md=1&showWatermark=0&fontSize=100px&images=login)

This package makes it easy to view your Laravel application logs inside of Nova. It even supports polling.

![logviewer](https://user-images.githubusercontent.com/58970/166284652-f7aea6f8-849d-4698-aa18-c8c31d8eb0d9.png)

## Installation

You can install the Nova tool via Composer:

```shell
composer require laravel/nova-log-viewer
```

Next, you must register the tool with Nova. This is typically done in the `tools` method of your application's `NovaServiceProvider`.

```php
public function tools()
{
    return [
        // ...
        \Laravel\Nova\LogViewer\LogViewer::make(),
    ];
}
```
