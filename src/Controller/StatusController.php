<?php
// src/Controller/StatusController.php

namespace App\Controller;

use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StatusController extends AbstractController
{
#[Route('/like-status', name: 'like_status', methods: ['POST'])]
public function likeStatus(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
$statusId = $request->request->get('id');
$status = $entityManager->getRepository(Status::class)->find($statusId);

if (!$status) {
return new JsonResponse(['error' => 'Status not found'], 404);
}

$likes = $status->getLikes();
$status->setLikes($likes + 1);
$entityManager->flush();

return new JsonResponse(['likes' => $status->getLikes()]);
}
}
