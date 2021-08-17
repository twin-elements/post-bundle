#Installation
1.composer 

2.Add to routes.yaml
```
post_admin:
    resource: "@TwinElementsPostBundle/Controller/Admin/"
    prefix: /admin
    type: annotation
    requirements:
        _locale: '%app_locales%'
    defaults:
        _locale: '%locale%'
        _admin_locale: '%admin_locale%'
    options: { i18n: false }
```
