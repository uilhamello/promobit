<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Cast\Array_;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{

    private $manager;
    private $passwordEncoder;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($registry, User::class);
        $this->manager = $manager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function createUser(string $email, string $password): array
    {
        $newUser = new User();

        if (empty($email) || empty($password)) {
            return ['status' => 'empty', 'data' => $password, 'message' => 'Data can not be empty'];
        }

        if ($userExisted = $this->FindUserByEmail($email)) {
            return ['status' => 'already_exist', 'data' => $userExisted, 'message' => 'email already exist'];
        }

        $newUser->setPassword($this->passwordEncoder->encodePassword(
            $newUser,
            $password
        ));

        $newUser->setEmail($email);

        $this->manager->persist($newUser);
        $this->manager->flush();

        return ['status' => 'success', 'data' => $newUser, 'message' => 'has successful created'];
    }

    public function removeUser(string $email)
    {
        if (empty($email)) {
            return ['status' => 'empty', 'data' => $email, 'message' => 'Email is empty'];
        }

        if (!$userExisted = $this->FindUserByEmail($email)) {
            return ['status' => 'email_not_exit', 'message' => 'email not exit'];
        }

        $this->manager->remove($userExisted);
        $this->manager->flush();
        return ['status' => 'success', 'message' => 'user has been removed'];
    }

    public function updateUser(array $data)
    {
        if (empty($data['email'])) {
            return ['status' => 'empty', 'data' => $data, 'message' => 'Email is empty'];
        }

        if (!$userExisted = $this->FindUserByEmail($data['email'])) {
            return ['status' => 'email_not_exit', 'message' => 'email not exit'];
        }

        $userExisted->setPassword($this->passwordEncoder->encodePassword(
            $userExisted,
            $data['new']['password']
        ));
        $userExisted->setEmail($data['new']['email']);
        $this->_em->persist($userExisted);
        $this->_em->flush();

        return ['status' => 'success', 'data' => $userExisted, 'message' => 'user has been updated'];
    }

    public function FindUserByEmail(string $email)
    {
        return $this->findOneBy(['email' => $email]);
    }
}
