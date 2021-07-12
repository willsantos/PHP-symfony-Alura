<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class HelloController
{
    /**
     * Undocumented function
     *
     * @Route("/ola")
     */
    public function HelloAction(Request $request):Response
    {
        $pathInfo = $request->getPathInfo();
        $params = $request->query->all();
        return new JsonResponse(['mensagem'=>'Olá mundo!','pathInfo'=>$pathInfo,'query'=>$params]);
    }
}