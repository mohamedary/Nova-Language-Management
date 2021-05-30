# Laravel Nova Langauge Management

A tool for Laravel Nova to implement dynamic languages

## Installation

Use the package manager [composer](https://pip.pypa.io/en/stable/) to install this tool.

```bash
composer require crayon/nova-language-management
```

## Usage
Open up NovaServiceProvider and register the tool in the tools method

```php
NovaLanguageEditor::make()
```
### Permissions
```php
NovaLanguageEditor::make()->canSee(fn($request) => $request->user()->isSuperAdmin()),
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
