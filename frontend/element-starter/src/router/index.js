import Vue from 'vue'
import Router from 'vue-router'
import {isAdminLogin} from '../util/auth'

Vue.use(Router)

const routes = [
    {
        path:'/admin',
        name:'manage',
        component:() => import('../page/Manage'),
        redirect:'/article/list',
        children:[
            {
                path:'/article/list',
                name:'admin-list',
                component:() => import('../page/Article')
            },
            {
                path:'/article/create',
                name:'admin-create',
                component:() => import('../page/CreateArticle')
            },
            {
                path:'/comment',
                name:'admin-comment',
                component:() => import('../page/Comment')
            },
            {
                path:'/category',
                name:'admin-category',
                component:() => import('../page/Category')
            },
            {
                path:'/tags',
                name:'admin-tags',
                component:() => import('../page/Tag')
            },
            {
                path:'/setting',
                name:'admin-setting',
                component:() => import('../page/Setting')
            },
            {
                path:'/setting/more',
                name:'admin-setting-more',
                component:() => import('../page/SettingMore')
            }
        ]
    },
    {
        path:'/login',
        name:'login',
        component:() => import('../page/Login')
    },
    {
        path:'*',
        name:'404',
        component:() => import('../page/404')
    },
    {
        path:'/',
        name:'index',
        component:() => import('../page/Home'),
        redirect:'/feeds/recent/key/all',
        children: [
            {
                path:'/feeds/:feedsType/key/:condition',
                name:'feeds',
                component:() => import('../page/FeedsList')
            },
            {
                path:'/article/:id',
                name:'detail',
                component:() => import('../page/ArticleDetail')
            },
            {
                path:'/about',
                name:'about',
                component:() => import('../page/About')
            }
        ]
    }
]

let router = new Router(
    {
        routes:routes
    }
)

// 全局路由守卫
router.beforeEach((to, from, next) => {
    console.log('navigation-guards');
    // to: Route: 即将要进入的目标 路由对象
    // from: Route: 当前导航正要离开的路由
    // next: Function: 一定要调用该方法来 resolve 这个钩子。执行效果依赖 next 方法的调用参数。
  
    const nextRoute = ['admin-setting-more','admin-list', 'admin-comment', 'admin-create', 'admin-tag', 'admin-category', 'admin-setting'];
    let isLogin = isAdminLogin();  // 是否登录
    // 未登录状态；当路由到nextRoute指定页时，跳转至login
    if (nextRoute.indexOf(to.name) >= 0) {  
      if (!isLogin) {
        router.push({ name: 'login' })
      }
    }
    // 已登录状态；当路由到login时，跳转至home 
    if (to.name === 'login') {
      if (isLogin) {
        router.push({ name: 'admin-list' });
      }
    }
    next();
  });

export default router;