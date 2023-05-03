<?php

namespace App\Resources;

class UserResource 
{
    protected $resource;

    /**
     * Constructor.
     * @param $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource->toArray();
    }

    /**
     * Index resource
     * @return array
     */
    public function index(): array
    {
        $response = $this->resource;
        foreach ($response['lists'] as $key => $value) {
            $response['lists'][$key] = [
                //
            ];
        }

        return $response;
    }

    /**
     * Show resource
     * @return array
     */
    public function show(): array
    {
        $result = $this->resource;
        $response = [];

        return $response;
    }

    /**
     * Store resource
     * @return array
     */
    public function store(): array
    {
        $result = $this->resource;
        $response = [];

        return $response;
    }
}