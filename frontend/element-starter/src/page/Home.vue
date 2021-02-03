
<template>
    <div class="base-content">
        <div class="header-box">
            <div @click="handleHomeAction" class="logo-box">
                {{aboutInfo.blog_name}}
            </div>
            <div class="nav-box">
                <marquee-text class="marquee">
                    {{oneSentence}}
                </marquee-text>
                <div v-if="isLogin">
                    <div @click="jumpToManage" class="manage-btn-box">
                        <img class="manage-btn" src="../assets/layout.png">
                    </div>
                </div>
                <div v-if="aboutInfo.article_id !== null">
                    <el-menu default-active="about" mode="horizontal" @select="handleSelect" background-color="#0000" text-color="#fff" active-text-color="#00E495">
                        <el-menu-item index="about">关于我</el-menu-item>
                    </el-menu>
                </div>
            </div>
        </div>
        <div class="body-box">
            <div class="main-box">
                <div class="person-box">
                    <div class="info-part">
                        <div v-if="user.avatar != null">
                            <img class="avatar" :src="user.avatar">
                        </div>
                        <div v-else>
                            <img class="avatar" src="https://ss0.bdstatic.com/70cFuHSh_Q1YnxGkpoWK1HF6hhy/it/u=3127300162,614350134&fm=26&gp=0.jpg">
                        </div>
                        <span class="author">{{aboutInfo.nickname}}</span>
                        <div class="introduce-box">
                            <div class="line-box">
                                <span class="left-tag">生 日</span>
                                <span class="seprate-line">｜</span>
                                <span class="right-content">{{aboutInfo.birthday}}</span>
                            </div>
                            <div class="line-box">
                                <span class="left-tag">职 业</span>
                                <span class="seprate-line">｜</span>
                                <span class="right-content">{{aboutInfo.job}}</span>
                            </div>
                            <div class="line-box">
                                <span class="left-tag">爱 好</span>
                                <span class="seprate-line">｜</span>
                                <span class="right-content">{{aboutInfo.hobby}}</span>
                            </div>
                        </div>
                        <span class="sign-box">
                                                                                                                                                                                                                    <span class="quote">“</span>
                        <span class="sign">{{aboutInfo.introduce}}</span>
                        <span class="quote">”</span>
                        </span>
                    </div>
                    <div class="icon-part">
                        <div v-if="aboutInfo.github !== null">
                            <a :href="aboutInfo.github"><img class="github-box" src="../assets/github.jpeg"></a>
                        </div>
                        <div v-else>
                            <a href="http://www.github.com/zyprosoft"><img class="github-box" src="../assets/github.jpeg"></a>
                        </div>
                        <div class="social-box">
                            <div v-if="aboutInfo.qq_code !== null">
                                <img class="qrcode" :src="aboutInfo.qq_code">
                            </div>
                            <div v-if="aboutInfo.wx_code !== null">
                                <img class="qrcode" :src="aboutInfo.wx_code">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="feeds-box">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { isLogin } from '../api/user'
import MarqueeText from 'vue-marquee-text-component'
import { getOneSentence, getAboutInfo } from '../api/article'

export default {
    name: 'Home',
    created() {
        this.isLogin = isLogin()
        this.initOneSentence()
        this.initAdminInfo()
    },
    watch: {
        $route(to, from) {
            let userPageList = ['feeds', 'detail', 'about']
            if (to.name in userPageList) {
                this.initOneSentence()
            }
        }
    },
    components: {
        MarqueeText
    },
    data() {
        return {
            isLogin: false,
            oneSentence: '',
            aboutInfo: {},
            user: {}
        }
    },
    props: {

    },
    methods: {
        handleHomeAction() {
            this.$router.push({ name: 'index' })
        },
        initOneSentence() {
            getOneSentence().then(res => {
                this.oneSentence = "”" + res.data.sentence + "“"
            })
        },
        jumpToManage() {
            this.$router.push({ name: 'manage' })
        },
        handleSelect() {
            this.$router.push({ name: 'about' })
        },
        initAdminInfo() {
            getAboutInfo().then(res => {
                this.aboutInfo = res.data.about
                this.user = res.data
            })
        }
    },
}
</script>

<style lang="scss" scoped>
.base-content {
    position: absolute;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    overflow: hidden;
}

.header-box {
    width: 100%;
    height: 56px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    background-color: black;
    .logo-box {
        color: #00E495;
        font-size: 36px;
        padding-left: 48px;
        font-family: 'apple chancery';
    }
    .nav-box {
        display: flex;
        justify-content: flex-end;
        width: 80%;
        height: 60px;
        align-items: center;
        border-bottom: 0.5px;
        border-bottom-color: gray;
        padding-right: 15px;
        .marquee {
            color: white;
            width: 100%;
            height: 100%;
            font-size: 15px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .manage-btn-box {
            width: 35px;
            height: 35px;
            border: 1px solid #00E495;
            border-radius: 20px;
            .manage-btn {
                width: 20px;
                height: 20px;
                margin-top: 7.5px;
            }
        }
    }
}

.body-box {
    height: 100%;
    width: 100%; // background-color: blue;
    .main-box {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: start;
        flex-direction: row;
        .person-box {
            background-color: black;
            align-items: center;
            justify-content: space-between;
            width: 15%;
            height: 100%;
            display: flex;
            flex-direction: column;
            .info-part {
                background-color: black;
                align-items: center;
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: column;
                padding-top: 20px;
                .avatar {
                    width: 80px;
                    height: 80px;
                    background-color: white;
                    padding: 3px 3px;
                    border-radius: 10px;
                    border-color: #00E495;
                    border-style: solid;
                    border-width: 2px;
                }
                .author {
                    color: white;
                    padding-top: 8px;
                }
                .introduce-box {
                    display: flex;
                    justify-content: flex-start;
                    flex-direction: column;
                    align-items: left;
                    margin-top: 10px;
                    .line-box {
                        align-content: left;
                        text-align: left;
                        $tagFontSize: 14px;
                        .left-tag {
                            color: #00E495;
                            font-size: $tagFontSize;
                            margin-right: 6px;
                            text-align: left;
                        }
                        .seprate-line {
                            width: 1px;
                            font-size: $tagFontSize;
                            color: white;
                        }
                        .right-content {
                            color: white;
                            font-size: $tagFontSize;
                        }
                    }
                }
                .sign-box {
                    color: white;
                    padding: 15px 0px;
                    margin: 5px 18px;
                    font-size: 14px;
                    .quote {
                        font-size: 18px;
                        text-align: bottom;
                    }
                }
            }
            .icon-part {
                width: 100%;
                margin-bottom: 80px;
                display: flex;
                flex-direction: column;
                align-items: center;
                .github-box {
                    width: 40px;
                    height: 40px;
                    border-radius: 20px;
                }
                .social-box {
                    width: 100%;
                    display: flex;
                    flex-direction: row;
                    justify-content: space-between;
                    .qrcode {
                        margin-left: 15px;
                        margin-right: 15px;
                        width: 70px;
                        height: 70px;
                        border-radius: 4px;
                    }
                }
            }
        }
        .feeds-box {
            background-color: #E6E6E6;
            width: 100%;
            height: 100%;
            overflow-y: auto;
            overflow-x: hidden;
        }
    }
}
</style>

