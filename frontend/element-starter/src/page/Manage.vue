
<template>
    <div class="base-container">
        <el-container class="base-container">
            <el-header class="header">
                <div @click="jumpToFront" class="title-box">
                    ZYProSoft
                </div>
                <div @click="logoutAction" class="operate-box">
                    <el-button type="primary">退出</el-button>
                </div>
            </el-header>
            <el-container>
                <el-aside class="aside" width="200px">
                    <el-menu ref="manageMenuRef" :router=true :default-active="$route.path" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose" background-color="#000" text-color="#fff" active-text-color="#00E495">
                        <el-menu-item index="/article/list">
                            <i class="iconfont el-icon-menu"></i>
                            <span slot="title">博客列表</span>
                        </el-menu-item>
                        <el-menu-item index="/article/create">
                            <i class="iconfont el-icon-edit"></i>
                            <span slot="title">书写博客</span>
                        </el-menu-item>
                        <el-menu-item index="/category">
                            <i class="iconfont el-icon-layers"></i>
                            <span slot="title">分类管理</span>
                        </el-menu-item>
                        <el-menu-item index="/comment">
                            <i class="iconfont el-icon-comment"></i>
                            <span slot="title">评论管理</span>
                        </el-menu-item>
                        <el-menu-item index="/tags">
                            <i class="iconfont el-icon-discount"></i>
                            <span slot="title">标签管理</span>
                        </el-menu-item>
                        <el-menu-item index="/setting">
                            <i class="iconfont el-icon-setting"></i>
                            <span slot="title">应用设置</span>
                        </el-menu-item>
                        <el-menu-item index="/setting/more">
                            <i class="iconfont el-icon-setting"></i>
                            <span slot="title">更多设置</span>
                        </el-menu-item>
                    </el-menu>
                </el-aside>
                <el-container>
                    <el-main class="main">
                        <router-view></router-view>
                    </el-main>
                    <el-footer class="footer">
                        <div class="footer text">ZYProSoft Inc @copyright 2020.12.12</div>
                    </el-footer>
                </el-container>
            </el-container>
        </el-container>
    </div>
</template>

<script>
import { logout } from '../api/user'
import { removeToken } from '../util/auth'

export default {
    name: 'Manage',
    created() {},
    data() {
        return {
            breadTitle: "文章",
            breadTo: "article"
        }
    },
    props: {

    },
    methods: {
        handleOpen(key, keyPath) {
            console.log(key, keyPath);
            this.login()
        },
        handleClose(key, keyPath) {
            console.log(key, keyPath);
            this.login()
        },
        jumpToFront() {
            this.$router.push({ name: 'index' })
        },
        logoutAction() {
            logout().then(res => {
                removeToken()
                this.$router.push({ name: 'index' })
            })
        }
    },
}
</script>

<style lang="scss" scoped>
.base-container {
    position: absolute;
    width: 100%;
    height: 100%;
}

.aside {
    background-color: black;
}

.logo-title {
    height: 48px;
    width: 100%;
    background-color: black;
    text-align: center;
    ;
}

.header {
    background-color: black;
    display: flex;
    justify-content: space-between;
    align-items: center;
    .operate-box {
        padding-right: 25px;
    }
    .title-box {
        color: #00E495;
        font-size: 36px;
        font-family: 'apple chancery';
    }
}

.main {
    height: 100%; // background-color: rgb(212, 212, 209);
}

.el-breadcrumb-item {
    color: white
}

footer {
    height: 48px;
    background-color: black;
    .text {
        margin: 20px 0 0 0;
        color: gray;
        font-size: 16px;
        text-align: center;
        align-content: center;
    }
}
</style>