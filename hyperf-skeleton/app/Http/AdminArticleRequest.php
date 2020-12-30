<?php


namespace App\Http;

class AdminArticleRequest extends AppAdminRequest
{
    public function rules()
    {
        if ($this->hasParam('articleId')) {
            return [
                'articleId' => 'integer|required',
                'title' => 'string|max:50',
                'content' => 'string|max:20000',
                'tags' => 'array|max:5',
                'categoryId' => 'integer|exists:category,category_id'
            ];
        }else{
            return [
                'title' => 'string|max:50|required',
                'content' => 'string|max:20000|required',
                'tags' => 'array|max:5|required',
                'categoryId' => 'integer|exists:category,category_id|required'
            ];
        }
    }
}