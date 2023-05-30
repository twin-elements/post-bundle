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

## Preview url generator
Create class `PostPreviewGenerator`
```php
class PostPreviewGenerator implements PostPreviewGeneratorInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function generatePreviewUrl(Post $post): string
    {
        return $this->router->generate('post', [
            'id' => $post->getId(),
            'slug' => $post->getSlug()
        ]);
    }
}
```

in `service.yaml`
```yaml
services:
    TwinElements\PostBundle\UrlGenerator\PostPreviewGeneratorInterface:
        alias: 'App\PreviewUrlGenerator\PostPreviewGenerator'
```
