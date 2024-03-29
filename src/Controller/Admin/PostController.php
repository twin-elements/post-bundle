<?php

namespace TwinElements\PostBundle\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TwinElements\AdminBundle\Model\CrudControllerTrait;
use TwinElements\Component\CrudLogger\CrudLogger;
use TwinElements\PostBundle\Entity\Post\Post;
use TwinElements\PostBundle\Entity\Post\SearchPost;
use TwinElements\PostBundle\Entity\PostCategory\PostCategory;
use TwinElements\PostBundle\Form\PostType;
use TwinElements\PostBundle\Form\SearchPostType;
use TwinElements\PostBundle\Repository\PostCategoryRepository;
use TwinElements\PostBundle\Repository\PostRepository;
use TwinElements\PostBundle\Security\PostVoter;
use TwinElements\PostBundle\UrlGenerator\PostPreviewGeneratorInterface;
use function Doctrine\ORM\QueryBuilder;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{

    use CrudControllerTrait;

    /**
     * @Route("/{category}/", name="post_index", methods="GET")
     */
    public function index(
        int                    $category,
        Request                $request,
        PostRepository         $postRepository,
        PaginatorInterface     $paginator,
        PostCategoryRepository $postCategoryRepository
    ): Response
    {
        try {
            $this->denyAccessUnlessGranted(PostVoter::VIEW, new Post());

            $limit = 20;
            if ($request->query->has('limit')) {
                $limit = $request->query->getInt('limit');
            }
            $searchPost = new SearchPost();
            $searchPostForm = $this->createForm(SearchPostType::class, $searchPost, [
                'action' => $this->generateUrl('post_index', [
                    'category' => $category
                ])
            ]);
            $searchPostForm->handleRequest($request);

            /**
             * @var PostCategory $postCategory
             */
            $postCategory = $postCategoryRepository->find($category);

            $postsQB = $postRepository->findPostsByCategoryQB($request->getLocale(), $category);
            if ($searchPostForm->isSubmitted() && $searchPostForm->isValid() && $searchPost->getTitle()) {
                $postsQB
                    ->andWhere(
                        $postsQB->expr()->like('post_translations.title', ':like')
                    )
                    ->setParameter('like', "%" . $searchPost->getTitle() . "%");
            }

            $posts = $paginator->paginate($postsQB->getQuery(), $request->query->getInt('page', 1), $limit);

            $this->breadcrumbs->setItems([
                $this->adminTranslator->translate('post_category.post_categories') => $this->generateUrl('post_category_index'),
                $this->adminTranslator->translate('post.posts_list') => null
            ]);

            return $this->render('@TwinElementsPost/post/index.html.twig', [
                'posts' => $posts,
                'post_category' => $postCategory,
                'limit' => $limit,
                'searchForm' => $searchPostForm->createView()
            ]);
        } catch (\Exception $exception) {
            $this->flashes->errorMessage($exception->getMessage());
            return $this->redirectToRoute('admin_dashboard');
        }
    }


    /**
     * @Route("/{category}/new", name="post_new", methods="GET|POST")
     */
    public function new(
        int                    $category,
        Request                $request,
        PostCategoryRepository $postCategoryRepository
    ): Response
    {
        $post = new Post();
        if (!$this->isGranted(PostVoter::FULL, $post) && !$this->isGranted(PostVoter::OWN, $post)) {
            throw $this->createAccessDeniedException();
        }

        /**
         * @var PostCategory $postCategory
         */
        $postCategory = $postCategoryRepository->find($category);
        if (is_null($postCategory)) {
            throw new \Exception('Brak kategorii');
        }

        $post->setParent($postCategory);
        $post->setCurrentLocale($request->getLocale());

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {

                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $post->mergeNewTranslations();
                $em->flush();

                $this->crudLogger->createLog(Post::class, CrudLogger::CreateAction, $post->getId());

                $this->flashes->successMessage($this->adminTranslator->translate('admin.success_operation'));;

                if ('save' === $form->getClickedButton()->getName()) {
                    return $this->redirectToRoute('post_edit', [
                        'category' => $postCategory->getId(),
                        'id' => $post->getId()
                    ]);
                } else {
                    return $this->redirectToRoute('post_index', ['category' => $postCategory->getId()]);
                }

            } catch (\Exception $exception) {
                $this->flashes->errorMessage($exception->getMessage());
                return $this->redirectToRoute('post_index', [
                    'category' => $postCategory->getId()
                ]);
            }
        }

        $this->breadcrumbs->setItems([
            $this->adminTranslator->translate('post.posts_list') => $this->generateUrl('post_index', ['category' => $postCategory->getId()]),
            $this->adminTranslator->translate('post.create_new_post') => null
        ]);

        return $this->render('@TwinElementsPost/post/new.html.twig', [
            'post' => $post,
            'category' => $postCategory,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{category}/{id}/edit", name="post_edit", methods="GET|POST")
     */
    public function edit(int $category, Request $request, Post $post, PostPreviewGeneratorInterface $postPreviewGenerator): Response
    {
        if (!$this->isGranted(PostVoter::EDIT, $post) && !$this->isGranted(PostVoter::OWN, $post)) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        $deleteForm = $this->createDeleteForm($post);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $post->mergeNewTranslations();
                $this->getDoctrine()->getManager()->flush();
                $this->crudLogger->createLog(Post::class, CrudLogger::EditAction, $post->getId());

                $this->flashes->successMessage($this->adminTranslator->translate('admin.success_operation'));;
            } catch (\Exception $exception) {
                $this->flashes->errorMessage($exception->getMessage());
            }

            if ('save' === $form->getClickedButton()->getName()) {
                return $this->redirectToRoute('post_edit', [
                    'id' => $post->getId(),
                    'category' => $post->getParent()->getId()
                ]);
            } else {
                return $this->redirectToRoute('post_index', [
                    'category' => $post->getParent()->getId()
                ]);
            }
        }

        $this->breadcrumbs->setItems([
            $this->adminTranslator->translate('post_category.post_categories') => $this->generateUrl('post_category_index'),
            $this->adminTranslator->translate('post.posts_list') => $this->generateUrl('post_index', ['category' => $post->getParent()->getId()]),
            $post->getTitle() => null
        ]);

        return $this->render('@TwinElementsPost/post/edit.html.twig', [
            'entity' => $post,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
            'preview_url' => $postPreviewGenerator->generatePreviewUrl($post)
        ]);
    }

    /**
     * @Route("/{category}/{id}", name="post_delete", methods="DELETE")
     */
    public function delete(int $category, Request $request, Post $post): Response
    {
        if (!$this->isGranted(PostVoter::FULL, $post) && !$this->isGranted(PostVoter::OWN, $post)) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $id = $post->getId();

                $em = $this->getDoctrine()->getManager();
                $em->remove($post);
                $em->flush();

                $this->flashes->successMessage($this->adminTranslator->translate('admin.success_operation'));;
                $this->crudLogger->createLog(Post::class, CrudLogger::DeleteAction, $id);

            } catch (\Exception $exception) {
                $this->flashes->errorMessage($exception->getMessage());
            }
        }

        return $this->redirectToRoute('post_index', [
            'category' => $category
        ]);
    }


    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', [
                'category' => $post->getParent()->getId(),
                'id' => $post->getId()
            ]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
