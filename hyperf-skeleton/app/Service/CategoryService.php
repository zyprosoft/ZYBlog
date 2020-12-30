<?php


namespace App\Service;
use App\Model\Tag;

class CategoryService extends BaseService
{
    public function getAll()
    {
        return Tag::query()->select("name","category_id")->get();
    }
}