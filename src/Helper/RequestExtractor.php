<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

class RequestExtractor
{
    private function requestData(Request $request)
    {
        $order = $request->query->get('sort');
        $filter = $request->query->all();
        unset($filter['sort']);

        return [$order,$filter];
    }

    public function dataOrder(Request $request)
    {
        [$order, ] = $this->requestData($request);

        return $order;
    }

    public function dataFilter(Request $request)
    {
        [,$filter] = $this->requestData($request);

        return $filter;
    }
}