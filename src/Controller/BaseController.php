<?php


namespace App\Controller;


use App\Helper\EntityFactoryInterface;
use App\Helper\RequestExtractor;
use App\Helper\ResponseFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
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
    /**
     * @var CacheItemInterface
     */
    private $cacheItem;

    public function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager,
        EntityFactoryInterface $factory,
        RequestExtractor $extractor,
        CacheItemPoolInterface $cacheItem
    )
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->extractor = $extractor;
        $this->cacheItem = $cacheItem;
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

        $response = new ResponseFactory(
            $entityList,
            true,
            Response::HTTP_OK,
            $page,
            $items
        );

        return $response->getResponse();
    }

    public function show(int $id): Response
    {

        $entity = $this->cacheItem->hasItem($this->cachePrefix() . $id)
            ? $this->cacheItem->getItem($this->cachePrefix() . $id)->get()
            : $this->repository->find($id);

        $status = is_null($entity) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        $response = new ResponseFactory(
            $entity,
            true,
            $status
        );
        return $response->getResponse();
    }

    public function remove(int $id):Response
    {
        $entity = $this->repository->find($id);
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        $this->cacheItem->deleteItem($this->cachePrefix() . $id);


        return new Response('',Response::HTTP_NO_CONTENT);
    }

    public function create(Request $request): Response
    {
        $body = $request->getContent();
        $entity = $this->factory->makeEntity($body);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $cache= $this->cacheItem->getItem($this->cachePrefix() . $entity->getId());
        $cache->set($entity);
        $this->cacheItem->save($cache);

        return new JsonResponse($entity);
    }

    public function update(int $id, Request $request): Response
    {
        $body = $request->getContent();

        $entityUpdate = $this->factory->makeEntity($body);

        try {
           //$entity = $this->repository->find($id);
           $this->updateEntity($entityUpdate,$id);
           $this->entityManager->flush();
           $response = new ResponseFactory(true,$entityUpdate, Response::HTTP_OK);

           $cache = $this->cacheItem->getItem($this->cachePrefix(). $id);
           $cache->set($entityUpdate);
           $this->cacheItem->save($cache);

           return $response->getResponse();
        }catch (\InvalidArgumentException $exception){
            $response = new ResponseFactory(
                false,
                'Not Found',
                Response::HTTP_NOT_FOUND
            );
            return $response->getResponse();
        }

    }

    abstract function updateEntity($entityUpdate,$entity);
    abstract function cachePrefix():string;
}