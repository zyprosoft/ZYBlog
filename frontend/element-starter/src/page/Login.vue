
<template>
    <div class="root-box">
        <div class="login-box">
            <div class="logo-box">
                ZYProSoft
            </div>
            <div class="input-box">
                <div class="item-box">
                    <div class="icon-box">
                        <img class="icon" src="../assets/user-center.png">
                    </div>
                    <div class="gap-box"></div>
                    <el-input placeholder="请输入用户名" size="medium" class="input-field" type="username" v-model="form.username" auto-complete="off"></el-input>
                </div>
                <div class="item-box">
                    <div class="icon-box">
                        <img class="icon" src="../assets/unlock.png">
                    </div>
                    <div class="gap-box"></div>
                    <el-input type="password" size="medium" placeholder="请输入密码" class="input-field" v-model="form.password" show-password></el-input>
                </div>
                <div class="item-box">
                    <div class="icon-box">
                        <img class="icon-captcha" src="../assets/work.png">
                    </div>
                    <div class="gap-box"></div>
                    <div class="captcha-box">
                        <el-input size="medium" placeholder="验证码不区分大小写" class="input-captcha" v-model="form.captcha"></el-input>
                        <img @click="refreshCaptchaAction" class="captcha-img" :src="captcha.url">
                    </div>
                </div>
                <div class="operate-box">
                    <el-button @click="resetForm">重置</el-button>
                    <div class="gap-box"></div>
                    <el-button :loading="isLoging" type="primary" @click="loginAction">登陆</el-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { login } from '../api/user'
import { getCaptcha, refreshCaptcha } from '../api/article'
import { setToken } from '../util/auth'

export default {
    name: 'Login',
    created() {
        this.getCaptchAction()
    },
    data() {
        return {
            isLoging: false,
            form: {
                username: '',
                password: '',
                captcha: ''
            },
            captcha: {
                key: '',
                url: '',
            }
        }
    },
    props: {

    },
    methods: {
        getCaptchAction() {
            getCaptcha().then(res => {
                this.captcha = res.data;
            })
        },
        refreshCaptchaAction() {
            refreshCaptcha(this.captcha.key).then(res => {
                this.captcha = res.data;
            })
        },
        loginAction() {
            let form = this.form
            if (form.username.length == 0 || form.password.length == 0) {
                this.$message({ type: 'error', message: '用户名或密码为空' })
            }
            this.isLoging = true;
            let captcha = {
                'key':this.captcha.key,
                'code':this.form.captcha
            }
            login(form.username, form.password, captcha).then(res => {
                setToken(res.data.token)
                this.isLoging = true;
                this.$message({ type: 'success', message: '登陆成功' })
                this.$router.push({ name: 'admin-list' })
            }).catch(error => {
                this.isLoging = false;
            })
        },
        resetForm() {
            this.form.username = ''
            this.form.password = ''
        }
    },
}
</script>

<style lang="scss" scoped>
.root-box {
    position: absolute;
    width: 100%;
    height: 100%;
    background: black;
}

.login-box {
    position: absolute;
    height: 300px;
    width: 400px;
    background: white;
    position: absolute;
    border-radius: 1rem;
    border: 5px solid #e6e6e6;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    .logo-box {
        border-top-right-radius: 1rem;
        border-top-left-radius: 1rem;
        text-align: center;
        font-size: 28px;
        color: #00E495;
        font-family: 'apple chancery';
        height: 50px;
        background: black;
    }
    .input-box {
        height: 245px;
        width: 398px;
        align-items: left;
        border: 0.1px solid #111;
        border-bottom-right-radius: 1rem;
        border-bottom-left-radius: 1rem;
        .item-box {
            width: 100%;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-start;
            margin: 20px 10px;
            width: 480px;
            .icon-box {
                background: black;
                width: 60px;
                height: 35px;
                border-radius: 6px;
                align-items: center;
                .icon {
                    margin-top: 4px;
                    width: 25px;
                    height: 25px;
                }
                .icon-captcha {
                    margin-top: 5px;
                    width: 23px;
                    height: 23px;
                }
            }
            .gap-box {
                width: 15px;
            }
            .input-field {
                width: 63%;
            }
            .captcha-box {
                display: flex;
                flex-direction: row;
                justify-content: flex-start;
                align-items: center;
                .input-captcha {
                    width: 42%;
                }
                .captcha-img {
                    padding-left: 10px;
                    width: 150px;
                    height: 36px;
                }
            }
        }
        .operate-box {
            padding-right: 10px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-end;
            .gap-box {
                width: 20px;
            }
        }
        .gap-box-height {
            height: 10px;
        }
    }
}
</style>

