# CP Inject for ExpressionEngine 3

Allows you to inject arbitrary CSS and JS into the ExpressionEngine 3 control panel.

## Usage

In your ExpressionEngine config file (`system/user/config/config.php`), add the following:

```php
$config['cp_inject'] = array(
    'js' => 'path/to/my/js/file.js',
    'css' => 'path/to/my/css/file.css'
);
```

Or you can use an array to add multiple files to either key:

```php
$config['cp_inject'] = array(
    'js' => array(
        'path/to/my/js/file.js',
        'path/to/my/js/file2.js',
    ),
    'css' => array(
         'path/to/my/css/file.css',
         'path/to/my/css/file2.css',
     )
);
```

### Path look up

CP Inject will look in a few different places for the requested files.

1. First, it will try the path to the file by itself to see if it's an absolute path.
2. Next it will try from your document root (`$_SERVER['DOCUMENT_ROOT']`)
3. Then it will try from your `system/user` path
4. It will then try from `PATH_THIRD_THEMES`
5. Next it will check from `SYSPATH`
6. And finally it will check for the file in `PATH_THIRD`

If the file is not found in any of those places, it will be skipped.

## License

Copyright 2017 BuzzingPixel, LLC

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this software except in compliance with the License.
You may obtain a copy of the License at

[http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0)

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
