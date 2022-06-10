<?php

namespace TwinElements\PostBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TwinElements\AdminBundle\Model\CrudControllerTrait;
use TwinElements\AdminBundle\Role\AdminUserRole;
use TwinElements\Component\AdminTranslator\AdminTranslator;
use TwinElements\Component\CrudLogger\CrudLogger;
use TwinElements\PostBundle\Entity\PostCategory\PostCategory;
use TwinElements\PostBundle\Form\PostCategoryType;
use TwinElements\PostBundle\Repository\PostCategoryRepository;
use TwinElements\PostBundle\Role\SliderRoles;

/**
 * @Route("/post-category")
 */
class PostCategoryController extends AbstractController
{

    use CrudControllerTrait;

    /**
     * @Route("/", name="post_category_index", methods="GET")
     */
    public function index(Request $request, PostCategoryRepository $postCategoryRepository, AdminTranslator $translator): Response
    {

        $postCategories = $postCategoryRepository->findIndexListItems($request->getLocale());

        $this->breadcrumbs->setItems([
            $translator->translate('post_category.post_categories') => null
        ]);

        return $this->render('@TwinElementsPost/post_category/index.html.twig', [
            'post_categories' => $postCategories
        ]);
    }


    /**
     * @Route("/new", name="post_category_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted(AdminUserRole::ROLE_ADMIN);

        $postCategory = new PostCategory();
        $postCategory->setCurrentLocale($request->getLocale());

        $form = $this->createForm(PostCategoryType::class, $postCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {

                $em = $this->getDoctrine()->getManager();
                $em->persist($postCategory);
                $postCategory->mergeNewTranslations();
                $em->flush();

                $this->crudLogger->createLog(PostCategory::class, CrudLogger::CreateAction, $postCategory->getId());

                $this->flashes->successMessage($this->adminTranslator->translate('admin.success_operation'));;

                if ('save' === $form->getClickedButton()->getName()) {
                    return $this->redirectToRoute('post_category_edit', array('id' => $postCategory->getId()));
                } else {
                    return $this->redirectToRoute('post_category_index');
                }

            } catch (\Exception $exception) {
                $this->flashes->errorMessage($exception);
                return $this->redirectToRoute('post_category_index');
            }
        }

        $this->breadcrumbs->setItems([
            $this->adminTranslator->translate('post_category.post_categories') => $this->generateUrl('post_category_index'),
            $this->adminTranslator->translate('post_category.create_new_post_category') => null
        ]);

        return $this->render('@TwinElementsPost/post_category/new.html.twig', [
            'post_category' => $postCategory,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="post_category_edit", methods="GET|POST")
     */
    public function edit(Request $request, PostCategory $postCategory): Response
    {
        $this->denyAccessUnlessGranted(AdminUserRole::ROLE_ADMIN);

        $form = $this->createForm(PostCategoryType::class, $postCategory);
        $form->handleRequest($request);

        $deleteForm = $this->createDeleteForm($postCategory);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $postCategory->mergeNewTranslations();
                $this->getDoctrine()->getManager()->flush();
                $this->crudLogger->createLog(PostCategory::class, CrudLogger::EditAction, $postCategory->getId());

                $this->flashes->successMessage($this->adminTranslator->translate('admin.success_operation'));;
            } catch (\Exception $exception) {
                $this->flashes->errorMessage($exception->getMessage());
            }

            if ('save' === $form->getClickedButton()->getName()) {
                return $this->redirectToRoute('post_category_edit', ['id' => $postCategory->getId()]);
            } else {
                return $this->redirectToRoute('post_category_index');
            }
        }

        $this->breadcrumbs->setItems([
            $this->adminTranslator->translate('post_category.post_categories') => $this->generateUrl('post_category_index'),
            $postCategory->getTitle() => null
        ]);

        return $this->render('@TwinElementsPost/post_category/edit.html.twig', [
            'entity' => $postCategory,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_category_delete", methods="DELETE")
     */
    public function delete(Request $request, PostCategory $postCategory): Response
    {
        $this->denyAccessUnlessGranted(AdminUserRole::ROLE_ADMIN);

        $form = $this->createDeleteForm($postCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $id = $postCategory->getId();
                $title = $postCategory->getTitle();

                $em = $this->getDoctrine()->getManager();
                $em->remove($postCategory);
                $em->flush();

                $this->flashes->successMessage($this->adminTranslator->translate('admin.success_operation'));;
                $this->crudLogger->createLog(PostCategory::class, CrudLogger::DeleteAction, $postCategory->getId());

            } catch (\Exception $exception) {
                $this->flashes->errorMessage($exception->getMessage());
            }
        }

        return $this->redirectToRoute('post_category_index');
    }


    private function createDeleteForm(PostCategory $postCategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_category_delete', array('id' => $postCategory->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
