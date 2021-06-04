<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\TrickImage;
use App\Entity\TrickVideo;
use App\Entity\User;
use App\Form\CommentFormType;
use App\Form\TrickFormMainImageType;
use App\Form\TrickFormSingleImageType;
use App\Form\TrickFormType;
use App\Form\TrickFormVideoType;
use App\Repository\TrickImageRepository;
use App\Repository\TrickRepository;
use App\Service\FileUploader;
use DateTime;
use DateTimeZone;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/modifyTrickVideo/{slug}",name="modifyTrickVideo")
     * @param Trick $trick
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return RedirectResponse
     * @throws Exception
     */
    public function modifyVideo(Trick $trick, Request $request): RedirectResponse
    {
        $trickVideo = new TrickVideo();

        $videoForm = $this->createForm(TrickFormVideoType::class, $trickVideo);
        $videoForm->handleRequest($request);

        if ($videoForm->isSubmitted() && $videoForm->isValid()) {

            $videoEmbed = $videoForm->get('embed')->getData();

            $videoId = $videoForm->get('id')->getData();

            if ($videoId === null) {
                $trickVideo->setTrick($trick);
                $trickVideo->setCreatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));
                $trick->addTrickVideo($trickVideo);
                $trick->setUpdatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));
                $this->addFlash('success', 'Video créée.');

            } else {
                $trickVideo = $trick->getTrickVideo($videoId);
                $trickVideo->setUpdatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));
                $this->addFlash('success', 'Video modifié.');

            }


            $trickVideo->setEmbed($videoEmbed);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trickVideo);
            $entityManager->persist($trick);
            $entityManager->flush();

        }

        return $this->redirectToRoute('modifyTrick', ['slug' => $trick->getSlug()]);
    }

    /**
     * @Route("/modifyTrickMainImage/{slug}",name="modifyTrickMainImage")
     * @param Trick $trick
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return RedirectResponse
     * @throws Exception
     */
    public function modifyMainImage(Trick $trick, Request $request, SluggerInterface $slugger): RedirectResponse
    {

        $mainImageForm = $this->createForm(TrickFormMainImageType::class, $trick);
        $mainImageForm->handleRequest($request);

        $mainImageForm->isSubmitted();
        $mainImageForm->isValid();

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

            $trick->setUpdatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'Image principal modifié.');

        }
        return $this->redirectToRoute('modifyTrick', ['slug' => $trick->getSlug()]);
    }

    /**
     * @Route("/modifyTrickImage/{slug}",name="modifyTrickImage")
     * @param Trick $trick
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return RedirectResponse
     * @throws Exception
     */
    public function modifyImage(Trick $trick, Request $request, SluggerInterface $slugger): RedirectResponse
    {

        $singleImageForm = $this->createForm(TrickFormSingleImageType::class);
        $singleImageForm->handleRequest($request);

        if ($singleImageForm->isSubmitted() && $singleImageForm->isValid()) {

            $singleImageFile = $singleImageForm->get('path')->getData();

            $singleImageId = $singleImageForm->get('id')->getData();

            if ($singleImageFile) {

                $originalFilename = pathinfo($singleImageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $singleImageFile->guessExtension();

                $singleImageFile->move(
                    './images/tricks/',
                    $newFilename
                );


                if ($singleImageId === NULL) {
                    $trickImage = new TrickImage();

                    $trickImage->setPath($newFilename);
                    $trickImage->setTrick($trick);
                    $trickImage->setCreatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));

                    $trick->addTrickImage($trickImage);

                    $this->addFlash('success', 'Image ajouté.');


                } else {
                    $trickImage = $trick->getTrickImage($singleImageId);

                    $trickImage->setPath($newFilename);
                    $trickImage->setUpdatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));

                    $this->addFlash('success', 'Image modifié.');

                }

                $trick->setUpdatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($trick);
                $entityManager->persist($trickImage);
                $entityManager->flush();

            }

        }
        return $this->redirectToRoute('modifyTrick', ['slug' => $trick->getSlug()]);
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
            $trick->setCreatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));
            $trick->setUser($user);

            foreach ($trick->getTrickImages() as $trickImage) {
                $fileNameOriginal = $trickImage->getFile();

                $fileUploader = new FileUploader('./images/tricks/', $slugger);
                $fileName = $fileUploader->upload($fileNameOriginal);
                $trickImage->setPath($fileName);
            }

            if ($trick->getMainImageFile() != null) {
                $fileNameOriginal = $trick->getMainImageFile();
                $fileUploader = new FileUploader('./images/tricks/', $slugger);
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

        $trickForm = clone $trick;

        $trickForm = $this->createForm(TrickFormType::class, $trickForm, ['validation_groups' => 'edit']);
        $trickForm->handleRequest($request);

        if ($trickForm->isSubmitted() && $trickForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $fileUploader = new FileUploader('./images/tricks/', $slugger);
            $trick = $trickForm->getData();

//         Main image edit
            $fileNameOriginal = $trick->getMainImageFile();
            $fileName = $fileUploader->upload($fileNameOriginal);
            $trick->setMainImage($fileName);

            foreach ($trick->getTrickImages() as $trickImage) {
                if ($trickImage->getFile() != null) {
                    $fileNameOriginal = $trickImage->getFile();
                    $fileName = $fileUploader->upload($fileNameOriginal);
                    if ($trick->getTrickImage($trickImage) != null) {
                        $trickImage = $trick->getTrickImage($trickImage);
                        $trickImage->setPath($fileName);
                    } else {
                        $trickImage->setPath($fileName);
                        $trick->setTrickImage($trickImage);
                        $entityManager->persist($trickImage);
                    }
                }
            }

            $this->addFlash('success', 'Figure modifiée.');

            $trick->setUpdatedAt(new DateTime('now', new DateTimeZone('Europe/Paris')));

            $entityManager->persist($trick);
            $entityManager->flush();

        }

        return $this->render('layout/modify-trick.html.twig', [
            'header' => 'fullheight',
            'trickForm' => $trickForm->createView(),
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

        $entityManager->remove($trick);
        $entityManager->flush();

        $this->addFlash('success', 'Figure supprimée.');

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("get-image-data", name="getImageData")
     * @param Request $request
     * @param TrickImageRepository $imageRepository
     * @return JsonResponse
     */
    public function getImageData(Request $request, TrickImageRepository $imageRepository): JsonResponse
    {
        $IdArray = $request->request->get('array');

        $pathArray = array();

        foreach ($IdArray as $elt) {
            //$pathArray[$elt] = $imageRepository->findAll();
            $pathArray[$elt] = $imageRepository->getImagePath($elt);
        }

        $response = array(
            'success' => true,
            'data' => $pathArray
        );

        return new JsonResponse($response);
    }

}