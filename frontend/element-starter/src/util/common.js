import _ from 'lodash'
import request from './request'
import {getToken} from './auth'
import Hashes from 'jshashes'
import { Message } from 'element-ui'

export const FeedsTypeRecentPost = 'recent';
export const FeedsTypeRecentComment = 'comment';
export const FeedsTypeByTagId = 'tag';
export const FeedsTypeByCategoryId = 'category';
export const FeedsTypeByArchiveDate = 'archive';

export function callService(interfaceName, params) 
{
    let timestamp = new Date().getTime()
    const postData = {
        'token':getToken(),
        'timestamp':timestamp,
        'version':'1.0',
        'eventId':timestamp,
        'caller':'vue-admin',
        'seqId':String(timestamp),
        'interface':{
            'name':interfaceName,
            'param':params
        }
    }

    console.log(JSON.stringify(postData))

    return request({
        url: '/api',
        method: 'post',
        data: postData,
        headers: {
            'content-type':'application/json',
            'Access-Control-Allow-Origin':'*'
        }
      })
}

function ksort(params) {
    let keys = Object.keys(params).sort();
    let newParams = {};
    keys.forEach((key) => {
      newParams[key] = params[key];
    });
    return newParams;
}

function deepCopyObject(obj){
  var result = Array.isArray(obj) ? [] : {};
      for (var key in obj) {
        if (obj.hasOwnProperty(key)) {
          if (typeof obj[key] === 'object' && obj[key]!==null) {
            result[key] = deepCopyObject(obj[key]);   //递归复制
          } else {
            result[key] = obj[key];
          }
        }
      }
      return result;
}

export function callSafeService(interfaceName, params) 
{
    let AppReqAuthAppId = ZGW_APPID;
    let AppReqAuthSecret = ZGW_SECRET;

    let timestamp = new Date().getTime()
    timestamp = parseInt(timestamp/1000)
    let nonce = String(timestamp);
    let signParam = deepCopyObject(params);
    signParam['interfaceName'] = interfaceName;
    signParam = ksort(signParam);
    let signParamString = JSON.stringify(signParam);
    console.log('step 1:'+signParamString);
    let MD5 = new Hashes.MD5({utf8:true});
    signParamString = MD5.hex(signParamString);
    console.log('sign param md5:'+signParamString);
    let base = "appId="+AppReqAuthAppId+"&appSecret="+AppReqAuthSecret+"&nonce="+nonce+"&timestamp="+timestamp+"&"+signParamString
    console.log("sign base:"+base)
    let SHA256 = new Hashes.SHA256;
    let signature = SHA256.hex_hmac(AppReqAuthSecret,base)
    let auth = {
      'timestamp':timestamp,
      'nonce':nonce,
      'signature':signature,
      'appId':AppReqAuthAppId
    }
    const postData = {
        'token':getToken(),
        'timestamp':timestamp,
        'auth': auth,
        'version':'1.0',
        'eventId':timestamp,
        'caller':'vue-admin',
        'seqId':String(timestamp),
        'interface':{
            'name':interfaceName,
            'param':params
        }
    }

    console.log(JSON.stringify(postData))

    return request({
        url: '/api',
        method: 'post',
        data: postData,
        headers: {
            'content-type':'application/json',
            'Access-Control-Allow-Origin':'*'
        }
    })
}

export function callUploadService(interfaceName, params, file) 
{
    let AppReqAuthAppId = ZGW_APPID;
    let AppReqAuthSecret = ZGW_SECRET;

    let timestamp = new Date().getTime()
    timestamp = parseInt(timestamp/1000)
    let nonce = String(timestamp);
    let signParam = deepCopyObject(params);
    signParam['interfaceName'] = interfaceName;
    signParam = ksort(signParam);
    let signParamString = JSON.stringify(signParam);
    console.log('step 1:'+signParamString);
    let MD5 = new Hashes.MD5({utf8:true});
    signParamString = MD5.hex(signParamString);
    console.log('sign param md5:'+signParamString);
    let base = "appId="+AppReqAuthAppId+"&appSecret="+AppReqAuthSecret+"&nonce="+nonce+"&timestamp="+timestamp+"&"+signParamString
    console.log("sign base:"+base)
    let SHA256 = new Hashes.SHA256;
    let signature = SHA256.hex_hmac(AppReqAuthSecret,base)
    let auth = {
      'timestamp':timestamp,
      'nonce':nonce,
      'signature':signature,
      'appId':AppReqAuthAppId
    }

    const postData = {
      'token':getToken(),
      'timestamp':timestamp,
      'auth': JSON.stringify(auth),
      'version':'1.0',
      'eventId':timestamp,
      'caller':'vue-admin',
      'seqId':String(timestamp),
      'interface':JSON.stringify(
          {
            'name':interfaceName,
            'param':params
          }
      )
    }

    console.log(JSON.stringify(postData))

    let formData = new FormData()
    formData.append('upload', file)
    Object.keys(postData).forEach(key => {
        formData.append(key, postData[key])
    })

    return request({
      url: '/upload',
      method: 'post',
      data: formData,
      headers: {
          'content-type':'multipart/form-data',
          'Access-Control-Allow-Origin':'*'
      }
  })
}

export function alertServerMsg(code, msg) 
{
  let transMsg = '服务器内部错误'
   switch (code) {
     case 10014:
       transMsg = '验证码填写错误'
       break;
     case 10015:
       transMsg = '验证码创建失败' 
       break;
     case 10013: 
       transMsg = '验证码已过期'
       break;
     case 10012: 
       transMsg = '用户未登陆或无权进行此操作'
       break;
     case 10008: 
       transMsg = '此操作要求管理员身份'
       break;
     case 10001: 
       transMsg = '令牌非法'
       break;
     case 10004: 
       transMsg = '鉴权失败'
       break;
     case 9994: 
       transMsg = '访问频率过快'
       break;
     case 9999:
       transMsg =  msg
       break;
     default:
       break;
   }
   Message({
    message: transMsg,
    type: 'error',
    duration: 5 * 1000
  })
}