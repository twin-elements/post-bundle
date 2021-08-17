<?php

namespace TwinElements\PostBundle\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use TwinElements\AdminBundle\Model\CrudControllerTrait;
use TwinElements\AdminBundle\Role\AdminUserRole;
use TwinElements\PostBundle\Entity\Post\Post;
use TwinElements\PostBundle\Entity\PostCategory\PostCategory;
use TwinElements\PostBundle\Form\PostType;
use TwinElements\PostBundle\Repository\PostCategoryRepository;
use TwinElements\PostBundle\Repository\PostRepository;
use TwinElements\PostBundle\Security\PostVoter;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{

    use CrudControllerTrait;

    /**
     * @Route("/", name="post_index", methods="GET")
     */
    public function index(
        Request $request,
        PostRepository $postRepository,
        TranslatorInterface $translator,
        PaginatorInterface $paginator,
        PostCategoryRepository $postCategoryRepository
    ): Response
    {
        $post = new Post();
        $this->denyAccessUnlessGranted(PostVoter::VIEW, $post);

        if (!$request->query->has('category')) {
            throw new \Exception('No category ID');
        }
        /**
         * @var PostCategory $postCategory
         */
        $postCategory = $postCategoryRepository->find($request->query->has('category'));

        $postsQuery = $postRepository->findIndexListItemsByCategory($request->getLocale(), $request->query->get('category'));
        $posts = $paginator->paginate($postsQuery, $request->query->getInt('page', 1), 20);

        $this->breadcrumbs->setItems([
            $this->adminTranslator->translate('post_category.post_categories') => $this->generateUrl('post_category_index'),
            $this->adminTranslator->translate('post.posts_list') => null
        ]);

        return $this->render('@TwinElementsPost/admin/post/index.html.twig', [
            'posts' => $posts,
            'post_category' => $postCategory
        ]);
    }


    /**
     * @Route("/new", name="post_new", methods="GET|POST")
     */
    public function new(Request $request, PostCategoryRepository $postCategoryRepository): Response
    {
        $post = new Post();
        $this->denyAccessUnlessGranted(PostVoter::FULL, $post);

        if (!$request->query->has('category')) {
            throw new \Exception('No category ID');
        }

        /**
         * @var PostCategory $postCategory
         */
        $postCategory = $postCategoryRepository->find($request->query->has('category'));


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

                $this->crudLogger->createLog($post->getId(), $post->getTitle());

                $this->flashes->successMessage();

                if ('save' === $form->getClickedButton()->getName()) {
                    return $this->redirectToRoute('post_edit', array('id' => $post->getId()));
                } else {
                    return $this->redirectToRoute('post_index', ['category' => $postCategory->getId()]);
                }

            } catch (\Exception $exception) {
                $this->flashes->errorMessage($exception);
                return $this->redirectToRoute('post_index');
            }
        }

        $this->breadcrumbs->setItems([
            $this->adminTranslator->translate('post.posts_list') => $this->generateUrl('post_index', ['category' => $postCategory->getId()]),
            $this->adminTranslator->translate('post.create_new_post') => null
        ]);

        return $this->render('@TwinElementsPost/admin/post/new.html.twig', [
            'post' => $post,
            'category' => $postCategory,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="post_edit", methods="GET|POST")
     */
    public function edit(Request $request, Post $post): Response
    {
        $this->denyAccessUnlessGranted(PostVoter::EDIT, $post);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        $deleteForm = $this->createDeleteForm($post);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $post->mergeNewTranslations();
                $this->getDoctrine()->getManager()->flush();
                $this->crudLogger->createLog($post->getId(), $post->getTitle());

                $this->flashes->successMessage();
            } catch (\Exception $exception) {
                $this->flashes->errorMessage($exception->getMessage());
            }

            if ('save' === $form->getClickedButton()->getName()) {
                return $this->redirectToRoute('post_edit', ['id' => $post->getId()]);
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

        return $this->render('@TwinElementsPost/admin/post/edit.html.twig', [
            'entity' => $post,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods="DELETE")
     */
    public function delete(Request $request, Post $post): Response
    {
        $this->denyAccessUnlessGranted(PostVoter::FULL, $post);

        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $id = $post->getId();
                $title = $post->getTitle();

                $em = $this->getDoctrine()->getManager();
                $em->remove($post);
                $em->flush();

                $this->flashes->successMessage();
                $this->crudLogger->createLog($id, $title);

            } catch (\Exception $exception) {
                $this->flashes->errorMessage($exception->getMessage());
            }
        }

        return $this->redirectToRoute('post_index');
    }


    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $post->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
