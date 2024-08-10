<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    #[Route('/profile/{id}', name: 'user_profile')]
    public function profile(User $user): Response
    {
        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/edit/{id}', name: 'user_edit')]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            if (!$name) {
                // Nếu trường name rỗng, thêm thông báo lỗi hoặc xử lý thích hợp
                $this->addFlash('error', 'Name cannot be empty.');
                return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
            }

            $user->setName($name);
            $user->setRelationshipStatus($request->request->get('relationship_status'));
            $user->setWork($request->request->get('work'));
            $user->setEducation($request->request->get('education'));
            $user->setContactInfo($request->request->get('contact_info'));
            $user->setAbout($request->request->get('about'));

            // Xử lý ảnh đại diện
            /** @var UploadedFile $profilePicture */
            $profilePicture = $request->files->get('profile_picture');
            if ($profilePicture) {
                $originalFilename = pathinfo($profilePicture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $profilePicture->guessExtension();

                $profilePicture->move(
                    $this->getParameter('profile_pictures_directory'),
                    $newFilename
                );

                $user->setProfilePicture($newFilename);
            }

            // Xử lý ảnh bìa
            /** @var UploadedFile $coverPhoto */
            $coverPhoto = $request->files->get('cover_photo');
            if ($coverPhoto) {
                $originalFilename = pathinfo($coverPhoto->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $coverPhoto->guessExtension();

                $coverPhoto->move(
                    $this->getParameter('cover_photos_directory'),
                    $newFilename
                );

                $user->setCoverPhoto($newFilename);
            }

            $user->setUpdatedAt(new \DateTime());

            // Lưu thay đổi vào cơ sở dữ liệu
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
        ]);
    }
    // Thêm vào UserController.php

    // src/Controller/UserController.php

    #[Route('/profile/update-status/{id}', name: 'user_update_status')]
    public function updateStatus(Request $request, User $user, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if ($request->isMethod('POST')) {
            $statusText = $request->request->get('status_text');
            if (!$statusText) {
                $this->addFlash('error', 'Status text cannot be empty.');
                return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
            }

            // Xử lý ảnh trạng thái
            /** @var UploadedFile $statusImage */
            $statusImage = $request->files->get('status_image');
            if ($statusImage) {
                $originalFilename = pathinfo($statusImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $statusImage->guessExtension();

                $statusImage->move(
                    $this->getParameter('status_images_directory'),
                    $newFilename
                );

                $user->setStatusImage($newFilename);
            }

            // Cập nhật trạng thái người dùng
            $user->setStatusText($statusText);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
        }

        return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
    }


}
