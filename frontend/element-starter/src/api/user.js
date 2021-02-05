import _ from 'lodash'
import request from '../util/request'
import {getToken,removeToken,setToken} from '../util/auth'
import {callSafeService} from '../util/common'

function callServer(params, interfaceName) 
{
    let timestamp = new Date().getTime()
    const postData = {
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
    const token = getToken()
    if(token !== '') {
        postData['token'] = token
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

export function login(username, password, captcha) {
    return callSafeService("admin.user.login",{
        "username":username,
        "password":password,
        "captcha":captcha
    })
}   

export function logout(username, password) {
    return callSafeService("admin.user.logout",{}).then(res=>{
        console.log(res)
        removeToken()
    }).catch(error=>{
        console.log(error)
    })
} 

export function isLogin() 
{
    let token = getToken()
    if(token == null) {
        return false
    }
    if(typeof(token) !== 'string'){
        return false
    }
    if(token.length == 0) {
        return false
    }
    return true;
}