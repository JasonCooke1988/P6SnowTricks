<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\TrickImage;
use App\Entity\TrickVideo;
use App\Entity\User;
use App\Form\CommentFormType;
use App\Form\TrickFormSingleImageType;
use App\Form\TrickFormType;
use App\Form\TrickFormVideoType;
use App\Service\FileUploader;
use DateTime;
use DateTimeZone;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    /**
     * @Route("/single-trick/{slug}",name="singleTrick")
     * @param Trick $trick
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function singleTrick(Trick $trick, Request $request): Response
    {

        $comment = new Comment();

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var  User $user */
            $user = $this->getUser();

            $comment->setUser($user);
            $comment->setTrick($trick);
            $comment->setCreatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            $comment = new Comment();
            $form = $this->createForm(CommentFormType::class, $comment);

        }

        return $this->render('layout/single-trick.html.twig', [
            'header' => 'fullheight',
            'commentForm' => $form->createView(),
            'trick' => $trick
        ]);

    }

    /**
     * @Route("/add-trick/",name="addTrick")
     * @param Request $request
     * @param UserInterface $user
     * @param SluggerInterface $slugger
     * @return Response
     * @throws Exception
     */
    public function addTrick(Request $request, UserInterface $user, SluggerInterface $slugger): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $trick = new Trick();

        $trickForm = $this->createForm(TrickFormType::class, $trick, ['validation_groups' => 'new']);
        $trickForm->handleRequest($request);

        if ($trickForm->isSubmitted() && $trickForm->isValid()) {

            $trick = $trickForm->getData();

//            Possible solution ?
//            foreach($trick->getTrickVideos() as $video) {
//                if ($video->getEmbed() === '' | $video->getEmbed() === null) {
//                    dd('coucou');
//                }
//            }

            $trick->setCreatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));
            $trick->setUser($user);

            $fileUploader = new FileUploader('./images/tricks/', $slugger);

            $this->uploadImages($trick, $fileUploader);

            if ($trick->getMainImageFile() != null) {
                $fileNameOriginal = $trick->getMainImageFile();
                $fileName = $fileUploader->upload($fileNameOriginal);
                $trick->setMainImage($fileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'Figure Ajoutée.');
            return $this->redirectToRoute('home');
        }

        return $this->render('layout/add-trick.html.twig', [
            'trickForm' => $trickForm->createView()
        ]);

    }


    /**
     * @Route("/modify-trick/{slug}",name="modifyTrick")
     * @param Trick $trick
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     * @throws Exception
     */
    public function modifyTrick(Trick $trick, Request $request, SluggerInterface $slugger): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $trickForm = $this->createForm(TrickFormType::class, $trick, ['validation_groups' => 'edit']);
        $trickForm->handleRequest($request);

        if ($trickForm->isSubmitted() && $trickForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $fileUploader = new FileUploader('./images/tricks/', $slugger);
            $trick = $trickForm->getData();


//         Main image edit
            if ($trick->getMainImageFile() != null) {
                $fileNameOriginal = $trick->getMainImageFile();
                $fileName = $fileUploader->upload($fileNameOriginal);
                $trick->setMainImage($fileName);
            }

            $this->addFlash('success', 'Figure modifiée.');

            $trick->setUpdatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));
            $entityManager->flush();
            $trickForm = $this->createForm(TrickFormType::class, $trick, ['validation_groups' => 'edit']);
        }

        return $this->render('layout/modify-trick.html.twig', [
            'header' => 'fullheight',
            'trickForm' => $trickForm->createView(),
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/add-video/{slug}",name="addVideo")
     * @param Trick $trick
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     * @throws Exception
     */
    public function addVideo(Trick $trick, Request $request, SluggerInterface $slugger): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $trickVideo = new TrickVideo();

        $trickVideoForm = $this->createForm(TrickFormVideoType::class, $trickVideo, ['validation_groups' => 'edit']);
        $trickVideoForm->handleRequest($request);

        if ($trickVideoForm->isSubmitted() && $trickVideoForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $trick->addTrickVideo($trickVideo);

            $trick->setUpdatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));
            $entityManager->flush();

            $this->addFlash('success', 'Video ajoutée.');
            return $this->redirectToRoute('modifyTrick', array('slug' => $trick->getSlug()));
        }

        return $this->render('layout/add-video.html.twig', [
            'header' => 'fullheight',
            'trickVideoForm' => $trickVideoForm->createView(),
            'trickVideo' => $trickVideo,
            'trick' => $trick
        ]);
    }


    /**
     * @Route("/add-image/{slug}",name="addImage")
     * @param Trick $trick
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     * @throws Exception
     */
    public function addImage(Trick $trick, Request $request, SluggerInterface $slugger): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $trickImage = new TrickImage();

        $trickImageForm = $this->createForm(TrickFormSingleImageType::class, $trickImage, ['validation_groups' => 'edit']);
        $trickImageForm->handleRequest($request);

        if ($trickImageForm->isSubmitted() && $trickImageForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $fileUploader = new FileUploader('./images/tricks/', $slugger);

            //          Single image collection edit
            $this->uploadImage($trick, $trickImage, $fileUploader);

            $trick->setUpdatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));
            $entityManager->flush();

            $this->addFlash('success', 'Image ajoutée.');
            return $this->redirectToRoute('modifyTrick', array('slug' => $trick->getSlug()));
        }

        return $this->render('layout/add-image.html.twig', [
            'header' => 'fullheight',
            'trickImageForm' => $trickImageForm->createView(),
            'trickImage' => $trickImage,
            'trick' => $trick
        ]);
    }


    /**
     * @Route("/modify-image/{slug}/{trickImage_id}",name="modifyImage")
     * @param Trick $trick
     * @param TrickImage $trickImage
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     * @throws Exception
     */
    public function modifyImage(Trick $trick, TrickImage $trickImage, Request $request, SluggerInterface $slugger): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');


        $trickImageForm = $this->createForm(TrickFormSingleImageType::class, $trickImage, ['validation_groups' => 'edit']);
        $trickImageForm->handleRequest($request);

        if ($trickImageForm->isSubmitted() && $trickImageForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $fileUploader = new FileUploader('./images/tricks/', $slugger);

            //          Single image collection edit
            $this->uploadImage($trick, $trickImage, $fileUploader);

            $trick->setUpdatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));
            $entityManager->flush();

            $this->addFlash('success', 'Image modifié.');
            return $this->redirectToRoute('modifyTrick', array('slug' => $trick->getSlug()));
        }

        return $this->render('layout/modify-image.html.twig', [
            'header' => 'fullheight',
            'trickImageForm' => $trickImageForm->createView(),
            'trickImage' => $trickImage,
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/modify-video/{slug}/{trickVideo_id}",name="modifyVideo")
     * @param Trick $trick
     * @param TrickVideo $trickVideo
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     * @throws Exception
     */
    public function modifyVideo(Trick $trick, TrickVideo $trickVideo, Request $request): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');


        $trickVideoForm = $this->createForm(TrickFormVideoType::class, $trickVideo, ['validation_groups' => 'edit']);
        $trickVideoForm->handleRequest($request);

        if ($trickVideoForm->isSubmitted() && $trickVideoForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $trick->setUpdatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));
            $entityManager->flush();

            $this->addFlash('success', 'Video modifié.');
            return $this->redirectToRoute('modifyTrick', array('slug' => $trick->getSlug()));
        }

        return $this->render('layout/modify-video.html.twig', [
            'header' => 'fullheight',
            'trickVideoForm' => $trickVideoForm->createView(),
            'trickVideo' => $trickVideo,
            'trick' => $trick
        ]);
    }



    /**
     * @Route("/deleteMainImageTrick/{id}", name="deleteMainImageTrick")
     * @param Trick $trick
     * @return Response
     * @throws Exception
     */
    public function deleteMainImage(Trick $trick): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $trick->deleteMainImage();

        $trick->setUpdatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($trick);
        $entityManager->flush();

        $this->addFlash('success', 'Image supprimée.');


        return $this->redirectToRoute('modifyTrick', ['slug' => $trick->getSlug()]);
    }

    /**
     * @Route("/deleteSingleImageTrick/{id}/{trickImage_id}", name="deleteSingleImageTrick")
     * @ParamConverter("trickImage", options={"id" = "trickImage_id"})
     * @param Trick $trick
     * @param TrickImage $trickImage
     * @return RedirectResponse
     * @throws Exception
     */
    public function deleteSingleImage(Trick $trick, TrickImage $trickImage): RedirectResponse
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $path = $trickImage->getPath();

        unlink('images/tricks/' . $path);

        $trick->removeTrickImage($trickImage);

        $trick->setUpdatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($trick);
        $entityManager->flush();

        $this->addFlash('success', 'Image supprimée.');

        return $this->redirectToRoute('modifyTrick', ['slug' => $trick->getSlug()]);
    }

    /**
     * @Route("/deleteTrickVideo/{id}/{trickVideo_id}", name="deleteTrickVideo")
     * @ParamConverter("trickVideo", options={"id" = "trickVideo_id"})
     * @param Trick $trick
     * @param TrickVideo $trickVideo
     * @return RedirectResponse
     * @throws Exception
     */
    public function deleteTrickVideo(Trick $trick, TrickVideo $trickVideo): RedirectResponse
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $trick->removeTrickVideo($trickVideo);

        $trick->setUpdatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($trick);
        $entityManager->flush();

        $this->addFlash('success', 'Video supprimée.');

        return $this->redirectToRoute('modifyTrick', ['slug' => $trick->getSlug()]);
    }

    /**
     * @Route("delete-trick/{slug}", name="deleteTrick")
     * @param Trick $trick
     * @return RedirectResponse
     */
    public function deleteTrick(Trick $trick): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $entityManager = $this->getDoctrine()->getManager();

        foreach ($trick->getTrickImages() as $image) {
            $path = $image->getPath();
            unlink('images/tricks/' . $path);
        }


        $path = $trick->getMainImage();
        unlink('images/tricks/' . $path);

        $entityManager->remove($trick);
        $entityManager->flush();

        $this->addFlash('success', 'Figure supprimée.');

        return $this->redirectToRoute('home');
    }


    public function uploadImages($trick, $fileUploader)
    {
        foreach ($trick->getTrickImages() as $trickImage) {
            $id = $trickImage->getId();
            $fileNameOriginal = $trickImage->getFile();
            if ($fileNameOriginal != null) {
                $fileName = $fileUploader->upload($fileNameOriginal);
//                    If is a modified image
                if ($id != null) {
                    $trickImage = $trick->getTrickImage($id);
                    $oldPath = $trickImage->getPath();
                    unlink('images/tricks/' . $oldPath);
                    $trickImage->setPath($fileName);
                    $trickImage->setUpdatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));
//                        Else is a new image
                } else {
                    $trickImage->setPath($fileName);
                }
            }
        }
    }

    public function uploadImage($trick, $trickImage, $fileUploader)
    {
            $id = $trickImage->getId();
            $fileNameOriginal = $trickImage->getFile();
            if ($fileNameOriginal != null) {
                $fileName = $fileUploader->upload($fileNameOriginal);
//                    If is a modified image
                if ($id != null) {
                    $oldPath = $trickImage->getPath();
                    unlink('images/tricks/' . $oldPath);
                    $trickImage->setPath($fileName);
                    $trickImage->setUpdatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));
//                        Else is a new image
                } else {
                    $trickImage->setPath($fileName);
                    $trick->addTrickImage($trickImage);
                }
            }

    }

}