<?php

namespace App\Controller;

use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewHomeController extends AbstractController
{
    #[Route('/vieweverything', name: 'view_everything')]
    public function viewEverything(EntityManagerInterface $entityManager): Response
    {
        // Lấy tất cả các status từ database và sắp xếp theo ngày tạo mới nhất trước
        $statuses = $entityManager->getRepository(Status::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('view_everything.html.twig', [
            'statuses' => $statuses,
        ]);
    }
}
