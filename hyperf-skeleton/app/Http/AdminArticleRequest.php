<?php


namespace App\Http;
use ZYProSoft\Http\AdminRequest;

class AdminArticleRequest extends AdminRequest
{
    public function rules()
    {
        if ($this->hasParam('articleId')) {
            return [
                'articleId' => 'integer|required',
            ];
        }else{
            return [
                'title' => 'string|max:50|required',
                'content' => 'string|max:20000|required',
                'tags' => 'array|max:5|required',
                'categoryId' => 'integer|exist:category,category_id|required'
            ];
        }
    }
}