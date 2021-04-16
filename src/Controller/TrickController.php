<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\TrickImage;
use App\Entity\TrickVideo;
use App\Entity\User;
use App\Form\AddTrickSingleImageFormType;
use App\Form\AddTrickVideoFormType;
use App\Form\CommentFormType;
use App\Form\TrickFormMainImageType;
use App\Form\TrickFormSingleImageType;
use App\Form\TrickFormType;
use App\Form\TrickFormVideoType;
use DateTimeZone;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    /**
     * @Route("/single-trick/{id}",name="singleTrick")
     * @param Trick $trick
     * @param Request $request
     * @param Security $security
     * @return Response
     * @throws Exception
     */
    public function singleTrick(Trick $trick, Request $request)
    {

        $comment = new Comment();

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var  User $user */
            $user = $this->getUser();

            $comment->setUser($user);
            $comment->setTrick($trick);
            $comment->setCreatedAt(new \DateTime('now', new DateTimeZone('Europe/Paris')));

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
     * @Route("/modify-trick/{id}",name="modifyTrick")
     * @param Trick $trick
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     * @throws Exception
     */
    public function modifyTrick(Trick $trick, Request $request, SluggerInterface $slugger)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $mainImageForm = $this->createForm(TrickFormMainImageType::class, $trick);
        $mainImageForm->handleRequest($request);

        $singleImageForm = $this->createForm(TrickFormSingleImageType::class);
        $singleImageForm->handleRequest($request);

        $videoForm = $this->createForm(TrickFormVideoType::class);
        $videoForm->handleRequest($request);

        $addImageForm = $this->createForm(AddTrickSingleImageFormType::class);
        $addImageForm->handleRequest($request);

        $addVideoForm = $this->createForm(AddTrickVideoFormType::class);
        $addVideoForm->handleRequest($request);


        $trickForm = $this->createForm(TrickFormType::class, $trick);
        $trickForm->handleRequest($request);

        if ($mainImageForm->isSubmitted() && $mainImageForm->isValid()) {

            $mainImageFile = $mainImageForm->get('mainImage')->getData();

            $trick = $mainImageForm->getData();

            if ($mainImageFile) {

                $originalFilename = pathinfo($mainImageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $mainImageFile->guessExtension();

                // Move the file to the directory where brochures are stored

                $mainImageFile->move(
                    './images/tricks/',
                    $newFilename
                );

                $trick->setMainImage($newFilename);
            }

            $trick->setUpdatedAt(new \DateTime('now', new DateTimeZone('Europe/Paris')));

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($trick);
            $entityManager->flush();

        } else if ($singleImageForm->isSubmitted() && $singleImageForm->isValid()) {

            $singleImageFile = $singleImageForm->get('path')->getData();

            $singleImageId = $singleImageForm->get('id')->getData();

            if ($singleImageFile) {

                $originalFilename = pathinfo($singleImageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $singleImageFile->guessExtension();

                // Move the file to the directory where brochures are stored

                $singleImageFile->move(
                    './images/tricks/',
                    $newFilename
                );

                $trickImage = $trick->getTrickImage($singleImageId);

                $trickImage->setPath($newFilename);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($trickImage);
                $entityManager->flush();

            }

            $trick->setUpdatedAt(new \DateTime('now', new DateTimeZone('Europe/Paris')));

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($trick);
            $entityManager->flush();

        } else if ($addImageForm->isSubmitted() && $addImageForm->isValid()) {

            $newTrickImage = new TrickImage();
            $singleImageFile = $addImageForm->get('path')->getData();

            if ($singleImageFile) {

                $originalFilename = pathinfo($singleImageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $singleImageFile->guessExtension();

                // Move the file to the directory where brochures are stored

                $singleImageFile->move(
                    './images/tricks/',
                    $newFilename
                );
            }

            $newTrickImage->setPath($newFilename);
            $newTrickImage->setTrick($trick);
            $newTrickImage->setCreatedAt(new \DateTime('now', new DateTimeZone('Europe/Paris')));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newTrickImage);
            $entityManager->flush();

            $trick->addTrickImage($newTrickImage);

            $trick->setUpdatedAt(new \DateTime('now', new DateTimeZone('Europe/Paris')));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

        } else if ($videoForm->isSubmitted() && $videoForm->isValid()) {

            $videoPath = $videoForm->get('embed')->getData();

            $videoid = $videoForm->get('id')->getData();

            $trickVideo = $trick->getTrickVideo($videoid);

            $trickVideo->setEmbed($videoPath);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trickVideo);
            $entityManager->flush();

            $trick->setUpdatedAt(new \DateTime('now', new DateTimeZone('Europe/Paris')));

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($trick);
            $entityManager->flush();

        } else if ($addVideoForm->isSubmitted() && $addVideoForm->isValid()) {

            $videoPath = $addVideoForm->get('embed')->getData();

            $newVideo = new TrickVideo();

            $newVideo->setEmbed($videoPath);
            $newVideo->setTrick($trick);
            $newVideo->setCreatedAt(new \DateTime('now', new DateTimeZone('Europe/Paris')));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newVideo);
            $entityManager->flush();

            $trick->addTrickVideo($newVideo);
            $trick->setUpdatedAt(new \DateTime('now', new DateTimeZone('Europe/Paris')));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

        } elseif ($trickForm->isSubmitted() && $trickForm->isValid()) {

            $trick = $trickForm->getData();

            $trick->setUpdatedAt(new \DateTime('now', new DateTimeZone('Europe/Paris')));

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($trick);
            $entityManager->flush();

        }

        return $this->render('layout/modify-trick.html.twig', [
            'header' => 'fullheight',
            'trickMainImageForm' => $mainImageForm->createView(),
            'trickSingleImageForm' => $singleImageForm->createView(),
            'trickVideoForm' => $videoForm->createView(),
            'trickForm' => $trickForm->createView(),
            'addImageForm' => $addImageForm->createView(),
            'addVideoForm' => $addVideoForm->createView(),
            'trick' => $trick
        ]);

    }

    /**
     * @Route("/deleteMainImageTrick/{id}", name="deleteMainImageTrick")
     * @param Trick $trick
     * @return Response
     * @throws Exception
     */
    public
    function deleteMainImage(Trick $trick): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $trick->deleteMainImage();

        $trick->setUpdatedAt(new \DateTime('now', new DateTimeZone('Europe/Paris')));

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($trick);
        $entityManager->flush();

        return $this->redirectToRoute('modifyTrick', ['id' => $trick->getId()]);
    }

    /**
     * @Route("/deleteSingleImageTrick/{id}/{trickImage_id}", name="deleteSingleImageTrick")
     * @ParamConverter("trickImage", options={"id" = "trickImage_id"})
     * @param Trick $trick
     * @param TrickImage $trickImage
     * @return RedirectResponse
     * @throws Exception
     */
    public
    function deleteSingleImage(Trick $trick, TrickImage $trickImage): RedirectResponse
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $trick->removeTrickImage($trickImage);

        $trick->setUpdatedAt(new \DateTime('now', new DateTimeZone('Europe/Paris')));

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($trick);
        $entityManager->flush();

        return $this->redirectToRoute('modifyTrick', ['id' => $trick->getId()]);
    }

    /**
     * @Route("/deleteTrickVideo/{id}/{trickVideo_id}", name="deleteTrickVideo")
     * @ParamConverter("trickVideo", options={"id" = "trickVideo_id"})
     * @param Trick $trick
     * @param TrickVideo $trickVideo
     * @return RedirectResponse
     * @throws Exception
     */
    public
    function deleteTrickVideo(Trick $trick, TrickVideo $trickVideo): RedirectResponse
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $trick->removeTrickVideo($trickVideo);

        $trick->setUpdatedAt(new \DateTime('now', new DateTimeZone('Europe/Paris')));

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($trick);
        $entityManager->flush();

        return $this->redirectToRoute('modifyTrick', ['id' => $trick->getId()]);
    }

    /**
     * @Route("delete-trick/{id}", name="deleteTrick")
     * @param Trick $trick
     * @return RedirectResponse
     */
    public
    function deleteTrick(Trick $trick): RedirectResponse
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($trick);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }

}