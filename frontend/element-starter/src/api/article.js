import _ from 'lodash'
import {callSafeService, callService} from '../util/common'

export function createArticle(title,content,tags,categoryId) 
{
    const param = {
        'title':title,
        'content':content,
        'tags':tags,
        'categoryId':categoryId
    }
    const interfaceName = 'admin.article.create'
    return callSafeService(interfaceName, param)
}

export function updateArticle(articleId,title,content,tags,categoryId) 
{
    const param = {
        'articleId':articleId,
        'title':title,
        'content':content,
        'tags':tags,
        'categoryId':categoryId
    }
    const interfaceName = 'admin.article.update'
    return callSafeService(interfaceName, param)
}

export function getArticleDetail(articleId) 
{
    const param = {
        'articleId':Number(articleId),
    }
    const interfaceName = 'common.article.detail'
    return callSafeService(interfaceName, param)
}

export function getList(pageIndex,pageSize) 
{
    const param = {
        'pageIndex':pageIndex,
        'pageSize':pageSize,
    }
    const interfaceName = 'admin.article.list'
    return callSafeService(interfaceName, param)
}

export function getAllCategory() 
{
    const param = {
    }
    const interfaceName = 'common.category.getAll'
    return callSafeService(interfaceName, param)
}

export function getArchiveDateList() 
{
    const param = {
    }
    const interfaceName = 'common.article.getArchiveDateList'
    return callSafeService(interfaceName, param)
}

export function moveToTrash(articleId) 
{
    const param = {
        'articleId':articleId
    }
    const interfaceName = 'admin.article.moveToTrash'
    return callSafeService(interfaceName, param)
}

export function updateCategory(categoryId, name, avatar) 
{
    let param = {
        'categoryId':categoryId,
    }
    if (name) {
        param['name'] = name;
    }
    if (avatar) {
        param['avatar'] = avatar;
    }
    const interfaceName = 'admin.category.update'
    return callSafeService(interfaceName, param)
}

export function addCategory(name, avatar) 
{
    let param = {
        'name':name,
    }
    if (avatar) {
        param['icon'] = avatar;
    }
    const interfaceName = 'admin.category.create'
    return callSafeService(interfaceName, param)
}

export function deleteCategory(categoryId) 
{
    let param = {
        'categoryId':categoryId,
    }
    const interfaceName = 'admin.category.delete'
    return callSafeService(interfaceName, param)
}

export function getAllTag() 
{
    const param = {
    }
    const interfaceName = 'common.tag.getAll'
    return callSafeService(interfaceName, param)
}

export function getHotTags() 
{
    const param = {
    }
    const interfaceName = 'common.tag.getHotTags'
    return callSafeService(interfaceName, param)
}

export function updateTag(tagId, name) 
{
    let param = {
        'tagId':tagId,
        'name':name
    }
    const interfaceName = 'admin.tag.update'
    return callSafeService(interfaceName, param)
}

export function addTag(name) 
{
    let param = {
        'name':name,
    }
    const interfaceName = 'admin.tag.create'
    return callSafeService(interfaceName, param)
}

export function deleteTag(tagId) 
{
    let param = {
        'tagId':tagId,
    }
    const interfaceName = 'admin.tag.delete'
    return callSafeService(interfaceName, param)
}

export function getCommentList(pageIndex, pageSize) 
{
    const param = {
        'pageIndex':pageIndex,
        'pageSize':pageSize,
    }
    const interfaceName = 'admin.comment.list'
    return callSafeService(interfaceName, param)
}

export function getCommentListByArticleId(pageIndex, pageSize, articleId) 
{
    const param = {
        'pageIndex':pageIndex,
        'pageSize':pageSize,
        'articleId':Number(articleId)
    }
    const interfaceName = 'common.comment.list'
    return callSafeService(interfaceName, param)
}

export function createComment(articleId,content,nickname,email,avatar,commentId,captcha) 
{
    let param = {
        'articleId':Number(articleId),
        'content':content,
        'nickname':nickname,
        'email':email,
        'captcha':captcha,
    }
    if(avatar) {
        param['avatar'] = avatar
    }
    if(commentId) {
        param['commentId'] = Number(commentId)
    }
    const interfaceName = 'common.comment.create'
    return callSafeService(interfaceName, param)
}


export function deleteComment(commentId) 
{
    let param = {
        'commentId':commentId,
    }
    const interfaceName = 'admin.comment.delete'
    return callSafeService(interfaceName, param)
}

export function getListByRecentPost(pageIndex,pageSize) 
{
    const param = {
        'pageIndex':pageIndex,
        'pageSize':pageSize,
    }
    const interfaceName = 'common.article.getListByRecentPost'
    return callSafeService(interfaceName, param)
}

export function getListByRecentComment(pageIndex,pageSize) 
{
    const param = {
        'pageIndex':pageIndex,
        'pageSize':pageSize,
    }
    const interfaceName = 'common.article.getListByRecentComment'
    return callSafeService(interfaceName, param)
}

export function getListByDate(date,pageIndex,pageSize) 
{
    let year = date.substr(0,4);
    let month = date.substr(5,2);
    let reqDate = year + '-' + month;
    console.log('request archive date:'+reqDate);
    const param = {
        'pageIndex':pageIndex,
        'pageSize':pageSize,
        'date':reqDate
    }
    const interfaceName = 'common.article.getListByDate'
    return callSafeService(interfaceName, param)
}

export function getListByTagId(tagId,pageIndex,pageSize) 
{
    const param = {
        'pageIndex':pageIndex,
        'pageSize':pageSize,
        'tagId':Number(tagId)
    }
    const interfaceName = 'common.article.getListByTagId'
    return callSafeService(interfaceName, param)
}

export function getListByCategoryId(categoryId,pageIndex,pageSize) 
{
    const param = {
        'pageIndex':pageIndex,
        'pageSize':pageSize,
        'categoryId':Number(categoryId)
    }
    const interfaceName = 'common.article.list'
    return callSafeService(interfaceName, param)
}

export function getCaptcha() 
{
    const param = {
    }
    const interfaceName = 'common.captcha.get'
    return callSafeService(interfaceName, param)
}

export function refreshCaptcha(oldKey) 
{
    const param = {
    }
    if(oldKey !== null && oldKey.length > 0) {
        param['key'] = oldKey
    }
    const interfaceName = 'common.captcha.refresh'
    return callSafeService(interfaceName, param)
}

export function getOneSentence() 
{
    const param = {
    }
    const interfaceName = 'common.about.getOneSentence'
    return callSafeService(interfaceName, param)
}

export function commitAboutInfo(aboutInfo) 
{
    let params = {}
    Object.keys(aboutInfo).forEach(key=>{
        if(aboutInfo[key] != null) {
            params[key] = aboutInfo[key]
        }
    })
    const interfaceName = 'admin.about.commitAboutInfo'
    return callSafeService(interfaceName, params)
}

export function getAboutInfo() 
{
    const param = {
    }
    const interfaceName = 'common.about.info'
    return callSafeService(interfaceName, param)
}

export function getAboutPageInfo() 
{
    const param = {
    }
    const interfaceName = 'common.about.pageInfo'
    return callSafeService(interfaceName, param)
}

export function getAboutArticleList() 
{
    const param = {
    }
    const interfaceName = 'admin.article.getAboutArticleList'
    return callSafeService(interfaceName, param)
}

export function clearCache() 
{
    const param = {
    }
    const interfaceName = 'common.setting.clearCache'
    return callSafeService(interfaceName, param)
}

export function adminReply(content, commentId) 
{
    const param = {
        'content':content,
        'commentId':commentId
    }
    const interfaceName = 'admin.comment.reply'
    return callSafeService(interfaceName, param)
}

export function updatePassword(content, commentId) 
{
    const param = {
        'password':content,
    }
    const interfaceName = 'admin.setting.updatePassword'
    return callSafeService(interfaceName, param)
}