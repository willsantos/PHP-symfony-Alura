<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    /**
     * @var bool
     */
    private $success;
    /**
     * @var int
     */
    private $page;
    /**
     * @var int
     */
    private $items;
    /**
     * @var mixed
     */
    private $responseContent;
    /**
     * @var int
     */
    private $status;

    public function __construct(
        $responseContent,
        bool $success,
        $status = Response::HTTP_OK,
        ?int  $page = null,
        ?int  $items = null
    )
    {
        $this->success = $success;
        $this->page = $page;
        $this->items = $items;
        $this->responseContent = $responseContent;
        $this->status = $status;
    }

    public static function fromError(\Throwable $erro)
    {
        return new self(['mensagem' => $erro->getMessage()], false, Response::HTTP_INTERNAL_SERVER_ERROR, null,null);
    }

    public function getResponse(): JsonResponse
    {
        $responseContent = [
            'sucess' => $this->success,
            'page' => $this->page,
            'items' => $this->items,
            'content' => $this->responseContent
        ];

        if (is_null($this->page)) {
            unset($responseContent['page']);
            unset($responseContent['items']);
        }

        return new JsonResponse($responseContent, $this->status);
    }
}