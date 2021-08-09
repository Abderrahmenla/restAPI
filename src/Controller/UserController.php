<?php

namespace App\Controller;
use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Log\LoggerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;



    /**
     * @Route("/api/user", name="userController")
     */
class UserController extends AbstractController
{
    private $logger;
    /**
     * @var Serializer $serializer
     */
    private $serializer;

    /**
     * @var DocumentManager
     */
    private $dm ;


    /**
     * TaskController constructor.
     * @param DocumentManager $dm
     */
    public function __construct(DocumentManager $dm,LoggerInterface $logger)
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
        $this->dm = $dm;
        $this->logger = $logger;
    }

    /**
     * return User Repository
     */
    public function getUserRepository(){
        return $this->dm->getRepository(User::class);
    }

    /**
     * @Route("/", name="users",methods={"GET"})
     */
    public function getUsers(): Response
    {        
        $this->logger->info('this is the get user controller.');
        $users= $this->getUserRepository()->findAll();
        return $this->json($users);
    }

    /**
     * @param Request $request
     * @Route("/", name="createUser",methods={"POST"})
     */
    public function createUser(Request $request): Response
    {
        
        $user = $this->serializer->deserialize($request->getContent(), User::class , 'json');
        $this->logger->info($user->getAge());
        $this->dm->persist($user);
        $this->dm->flush();
        return $this->json($user);
    }
    /**
     * @param Request $request
     * @Route("/{id}", name="getUserById",methods={"GET"})
     */
    public function getUserById(string $id): Response
    {
        $this->logger->info($id);
        $user= $this->getUserRepository()->find($id);
        return $this->json($user);
    }
    /**
     * @param Request $request
     * @Route("/{id}", name="updateUser",methods={"PUT"})
     */
    public function updateUser(string $id,Request $request): Response
    {
        $this->logger->info($id);
        $user= $this->getUserRepository()->find($id);
        $updatedUser= $this->serializer->deserialize($request->getContent(), User::class , 'json');
        $user->setAge($updatedUser->getAge());
        $user->setName($updatedUser->getName());
        $user->setBirthday($updatedUser->getBirthday());
        $this->dm->persist($user);
        $this->dm->flush();
        return $this->json($user);
    }
    /**
     * @Route("/{id}", name="deleteUser",methods={"DELETE"})
     */
    public function deleteUser(string $id,Request $request): Response
    {
        $this->logger->info($id);
        $user= $this->getUserRepository()->find($id);
        $this->dm->remove($user);
        $this->dm->flush();
        return $this->json([
            'message' => 'User has been deleted',
        ]);
    }
}