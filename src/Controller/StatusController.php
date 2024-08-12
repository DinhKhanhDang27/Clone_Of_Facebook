<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/status')]
class StatusController extends AbstractController
{
    #[Route('/create', name: 'status_create')]
    public function createStatus(Request $request, User $user): Response
    {
        
    }
}