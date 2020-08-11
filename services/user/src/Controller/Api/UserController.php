<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Json;

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
     * @Route("/api/user/register", name="register", methods={"POST"})
     */
    public function register(Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        if (empty($data['email']) || empty($data['password'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'email or password is empty'], Response::HTTP_ACCEPTED);
        }

        $newUser = $this->userRepository->createUser($data['email'], $data['password']);
        if ($newUser['status'] == 'already_exist') {
            return new JsonResponse(['status' => 'error', 'message' => 'email already exist'], Response::HTTP_ACCEPTED);
        }

        return new JsonResponse(['status' => 'success', 'message' => 'user created'], Response::HTTP_CREATED);
    }
}
