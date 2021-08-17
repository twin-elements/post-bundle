<?php

namespace TwinElements\PostBundle;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use TwinElements\FormExtensions\Component\UrlBuilder\ModuleUrlGeneratorInterface;
use TwinElements\PostBundle\Entity\Post;
use TwinElements\PostBundle\Entity\PostCategory\PostCategory;
use TwinElements\PostBundle\Repository\PostCategoryRepository;

class PostCategoryUrlGenerator implements ModuleUrlGeneratorInterface
{
    /**
     * @var PostCategoryRepository $repository
     */
    private $repository;

    /**
     * @var RouterInterface $router
     */
    private $router;

    /**
     * @var string|null
     */
    private $locale;

    public static function getName(): string
    {
        return 'post_category';
    }

    public function __construct(PostCategoryRepository $postCategoryRepository, RouterInterface $router, RequestStack $requestStack)
    {
        $this->repository = $postCategoryRepository;
        $this->router = $router;
        if($requestStack->getCurrentRequest()){
            $this->locale = $requestStack->getCurrentRequest()->getLocale();
        }
    }

    public function getUrlList()
    {
        return $this->repository->findItemsForUrlBuilder($this->locale);
    }

    public function generateUrl(int $id)
    {
        /**
         * @var PostCategory $postCategory
         */
        $postCategory = $this->repository->find($id);

        if (!is_null($postCategory)) {
            return $this->router->generate('post_category', [
                'id' => $postCategory->getId(),
                'slug' => $postCategory->getSlug()
            ]);
        }

        throw new \Exception('Post with the given ID not exist in DB');
    }
}
