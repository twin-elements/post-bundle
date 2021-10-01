<?php

namespace TwinElements\PostBundle\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TwinElements\AdminBundle\Model\CrudControllerTrait;
use TwinElements\AdminBundle\Service\AdminTranslator;
use TwinElements\PostBundle\Entity\PostTag\PostTag;
use TwinElements\PostBundle\Form\PostTagType;
use TwinElements\PostBundle\Repository\PostTagRepository;
use TwinElements\PostBundle\Security\SliderVoter;

/**
 * @Route("/post-tag")
 */
class PostTagController extends AbstractController
{
    use CrudControllerTrait;

    /**
     * @Route("/", name="post_tag_index", methods="GET")
     */
    public function index(
        Request $request,
        PostTagRepository $postTagRepository,
        PaginatorInterface $paginator
    ): Response
    {
        $post = new PostTag();

        $postTagsQuery = $postTagRepository->findIndexListItems($request->getLocale());
        $postTags = $paginator->paginate($postTagsQuery, $request->query->getInt('page', 1), 20);

        $this->breadcrumbs->setItems([
            $this->adminTranslator->translate('post_tag.post_tags_list') => null
        ]);

        return $this->render('@TwinElementsPost/admin/post_tag/index.html.twig', [
            'postTags' => $postTags
        ]);
    }


    /**
     * @Route("/new", name="post_tag_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $postTag = new PostTag();
        $postTag->setCurrentLocale($request->getLocale());

        $form = $this->createForm(PostTagType::class, $postTag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {

                $em = $this->getDoctrine()->getManager();
                $em->persist($postTag);
                $postTag->mergeNewTranslations();
                $em->flush();

                $this->crudLogger->createLog($postTag->getId(), $postTag->getTitle());

                $this->flashes->successMessage();

                if ('save' === $form->getClickedButton()->getName()) {
                    return $this->redirectToRoute('post_tag_edit', array('id' => $postTag->getId()));
                } else {
                    return $this->redirectToRoute('post_tag_index');
                }

            } catch (\Exception $exception) {
                $this->flashes->errorMessage($exception);
                return $this->redirectToRoute('post_tag_index');
            }
        }

        $this->breadcrumbs->setItems([
            $this->adminTranslator->translate('post_tag.posts_tags_list') => $this->generateUrl('post_tag_index'),
            $this->adminTranslator->translate('post_tag.create_new_post_tag') => null
        ]);

        return $this->render('@TwinElementsPost/admin/post_tag/new.html.twig', [
            'postTag' => $postTag,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="post_tag_edit", methods="GET|POST")
     */
    public function edit(Request $request, PostTag $postTag): Response
    {
        $form = $this->createForm(PostTagType::class, $postTag);
        $form->handleRequest($request);

        $deleteForm = $this->createDeleteForm($postTag);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $postTag->mergeNewTranslations();
                $this->getDoctrine()->getManager()->flush();
                $this->crudLogger->createLog($postTag->getId(), $postTag->getTitle());

                $this->flashes->successMessage();
            } catch (\Exception $exception) {
                $this->flashes->errorMessage($exception->getMessage());
            }

            if ('save' === $form->getClickedButton()->getName()) {
                return $this->redirectToRoute('post_tag_edit', ['id' => $postTag->getId()]);
            } else {
                return $this->redirectToRoute('post_tag_index');
            }
        }

        $this->breadcrumbs->setItems([
            $this->adminTranslator->translate('post_tag.posts_tags_list') => $this->generateUrl('post_tag_index'),
            $postTag->getTitle() => null
        ]);

        return $this->render('@TwinElementsPost/admin/post_tag/edit.html.twig', [
            'entity' => $postTag,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_tag_delete", methods="DELETE")
     */
    public function delete(Request $request, PostTag $postTag): Response
    {
        $form = $this->createDeleteForm($postTag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $id = $postTag->getId();
                $title = $postTag->getTitle();

                $em = $this->getDoctrine()->getManager();
                $em->remove($postTag);
                $em->flush();

                $this->flashes->successMessage();
                $this->crudLogger->createLog($id, $title);

            } catch (\Exception $exception) {
                $this->flashes->errorMessage($exception->getMessage());
            }
        }

        return $this->redirectToRoute('post_tag_index');
    }


    private function createDeleteForm(PostTag $postTag)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_tag_delete', array('id' => $postTag->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
