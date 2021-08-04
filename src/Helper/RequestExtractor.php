<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

class RequestExtractor
{
    private function requestData(Request $request)
    {
        $query = $request->query->all();
        $order = array_key_exists('sort',$query) ? $query['sort'] : [];
        unset($query['sort']);

        $page = array_key_exists('page',$query) ? $query['page'] : 1;
        unset($query['page']);

        $items = array_key_exists('items',$query) ? $query['items'] : 5;
        unset($query['items']);

        return [$order,$query, $page,$items];
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

    public function dataPage(Request $request)
    {
        [,,$page,$items] = $this->requestData($request);

        return [$page,$items];
    }
}