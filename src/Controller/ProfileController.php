<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\RegistrationFormType;
use App\Service\FileUploader;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(): Response
    {
		$user = $this->getUser();
        return $this->render('profile/index.html.twig', [
            'user' => $user
        ]);
	}

	/**
     * @Route("/profile/{id}", name="profile_param")
     */
    public function profileWithID(int $id): Response
    {
		$repository = $this->getDoctrine()->getRepository(User::class);

		$user = $repository->find($id);
        return $this->render('profile/index.html.twig', [
            'user' => $user
        ]);
	}	
}
