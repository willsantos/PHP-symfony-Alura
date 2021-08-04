<?php


namespace App\Controller;


use App\Helper\EntityFactoryInterface;
use App\Helper\RequestExtractor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    /**
     * @var ObjectRepository
     */
    protected $repository;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     * @var EntityFactoryInterface
     */
    protected $factory;
    /**
     * @var RequestExtractor
     */
    private $extractor;

    public function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager,
        EntityFactoryInterface $factory,
        RequestExtractor $extractor
    )
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->extractor = $extractor;
    }

    public function index(Request $request): Response
    {
        $order = $this->extractor->dataOrder($request);
        $filter = $this->extractor->dataFilter($request);
        [$page, $items] = $this->extractor->dataPage($request);

        $entityList = $this->repository->findBy(
           $filter,
           $order,
           $items,
            ($page - 1) * $items
        );


        return new JsonResponse($entityList);
    }

    public function show(int $id): Response
    {
        return new JsonResponse($this->repository->find($id));
    }

    public function remove(int $id):Response
    {
        $entity = $this->repository->find($id);
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return new Response('',Response::HTTP_NO_CONTENT);
    }

    public function create(Request $request): Response
    {
        $body = $request->getContent();
        $entity = $this->factory->makeEntity($body);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return new JsonResponse($entity);
    }

    public function update(int $id, Request $request): Response
    {
        $body = $request->getContent();

        $entityUpdate = $this->factory->makeEntity($body);

        $entity = $this->repository->find($id);

        if(is_null($entity)){
            return new Response('',Response::HTTP_NOT_FOUND);
        }

        $this->updateEntity($entityUpdate,$entity);

        $this->entityManager->flush();

        return new JsonResponse($entity);
    }

    abstract function updateEntity($entityUpdate,$entity);

}