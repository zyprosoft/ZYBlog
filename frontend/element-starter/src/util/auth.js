import Cookies from 'js-cookie'

const TokenKey = 'Admin-Token'

export function isAdminLogin() 
{
   let token = getToken()
   if( !token) {
     return false
   }
   return true
}

export function getToken() {
  return Cookies.get(TokenKey)
}

export function setToken(token) {
  return Cookies.set(TokenKey, token)
}

export function removeToken() {
  return Cookies.remove(TokenKey)
}
