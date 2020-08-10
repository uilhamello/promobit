<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route(name="user.")
 */
class UserController extends AbstractController
{

    private $passwordEncoder;
    private $userRepository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/register", name="create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        $user = new User();

        if ($this->userRepository->findOneBy(["username" => $data['username']])) {
            return new JsonResponse(['status' => 'error', 'message' => 'username already exist'], Response::HTTP_ACCEPTED);
        }

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $data['password']
        ));

        $user->setUsername($data['username']);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($user);

        $entityManager->flush();

        return new JsonResponse(['status' => 'success', 'message' => 'user created'], Response::HTTP_CREATED);
    }
}
