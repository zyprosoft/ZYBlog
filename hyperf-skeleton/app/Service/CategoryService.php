<?php


namespace App\Service;
use App\Model\Category;

class CategoryService extends BaseService
{
    public function getAll()
    {
        return Category::query()->select(["name","category_id"])->get();
    }
}