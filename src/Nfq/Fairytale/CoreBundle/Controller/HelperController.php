<?php

namespace Nfq\Fairytale\CoreBundle\Controller;

use Nfq\Fairytale\CoreBundle\Entity\Image;
use Nfq\Fairytale\CoreBundle\Upload\GenericUpload;
use Nfq\Fairytale\CoreBundle\Upload\UploadManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class HelperController extends Controller
{
    /**
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function uploadAction(Request $request)
    {
        $document = new GenericUpload();
        $form = $this->createFormBuilder($document)
            ->add('file', 'file')
            ->add('Upload', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $upload = new Image();

            /** @var UploadManager $manager */
            $manager = $this->container->get('fairytale_core.upload.manager');
            $manager->store($document, $upload);

            $em = $this->getDoctrine()->getManager();
            $em->persist($upload);
            $em->flush();

            return $this->redirect($this->generateUrl('core_helper_upload_success'));
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Template()
     */
    public function uploadSuccessAction()
    {
        return [];
    }
}
