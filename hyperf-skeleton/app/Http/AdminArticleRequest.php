<?php
/**
 * This file is part of ZYProSoft/ZYBlog.
 *
 * @link     http://zyprosoft.lulinggushi.com
 * @document http://zyprosoft.lulinggushi.com
 * @contact  1003081775@qq.com
 * @Company  泽湾普罗信息技术有限公司(ZYProSoft)
 * @license  GPL
 */

declare(strict_types=1);

namespace App\Http;

/**
 * 文章相关的管理员请求类
 * Class AdminArticleRequest
 * @package App\Http
 */
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