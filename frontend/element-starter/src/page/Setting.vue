
<template>
    <div>
        <el-card class="box-card">
            <div class="input-form-box">
                <el-form size="medium" label-width="120px" :model="form">
                    <el-form-item label="头像">
                        <el-col :span="6">
                            <input ref="uploadInput" type="file" style="display:none" @change="uploadAvatar">
                            <div v-if="form.avatar !== null">
                                <div @click="addAvatar" class="avatar-holder-box">
                                    <img class="avatar" :src="form.avatar">
                                </div>
                            </div>
                            <div v-else>
                                <div @click="addAvatar" class="avatar-holder-box">
                                    <img class="avatar-icon" src="../assets/photo-add.png">
                                </div>
                            </div>
                        </el-col>
                    </el-form-item>
                    <el-row type="flex" justify="flex-start" align="center">
                        <el-col :span="10">
                            <el-form-item label="博客名称">
                                <el-input v-model="form.blog_name"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item label="邮箱">
                                <el-input v-model="form.email"></el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row type="flex" justify="flex-start" align="center">
                        <el-col :span="10">
                            <el-form-item label="昵称">
                                <el-input v-model="form.nickname"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item label="生日" prop="birthday">
                                <el-date-picker format="yyyy 年 MM 月 dd 日" value-format="yyyy-MM-dd" type="date" placeholder="选择日期" v-model="form.birthday" style="width: 100%;"></el-date-picker>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row type="flex" justify="flex-start" align="center">
                        <el-col :span="10">
                            <el-form-item label="微信">
                                <el-input v-model="form.wx"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item label="QQ">
                                <el-input v-model="form.qq"></el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row type="flex" justify="flex-start" align="center">
                        <el-col :span="10">
                            <el-form-item label="微博">
                                <el-input v-model="form.wb"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item label="星座">
                                <el-input v-model="form.constellation"></el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row type="flex" justify="flex-start" align="center">
                        <el-col :span="10">
                            <el-form-item label="爱好">
                                <el-input v-model="form.hobby"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item label="职业">
                                <el-input v-model="form.job"></el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row type="flex" justify="flex-start" align="center">
                        <el-col :span="10">
                            <el-form-item label="公司">
                                <el-input v-model="form.company"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item label="GitHub">
                                <el-input v-model="form.github"></el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row type="flex" justify="flex-start" align="center">
                        <el-col :span="10">
                            <el-form-item label="Facebook">
                                <el-input v-model="form.facebook"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item label="Twitter">
                                <el-input v-model="form.twitter"></el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row type="flex" justify="flex-start" align="center">
                        <el-col :span="10">
                            <el-form-item label="域名备案">
                                <el-input v-model="form.icp"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item label="背景音乐">
                                <el-input v-model="form.music"></el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row type="flex" justify="flex-start" align="center">
                        <el-col :span="10">
                            <el-form-item label="关于我">
                                <el-select v-model="form.article_id" placeholder="请选择文章">
                                    <div v-for="item in articleList" :key="item.article_id">
                                        <el-option :label="item.title" :value="item.article_id"></el-option>
                                    </div>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="9">
                            <div class="tip-box">
                                <span class="tips">选择一篇以"关于我"为开头的文章进行关联</span>
                            </div>
                        </el-col>
                    </el-row>
                    <el-row type="flex" justify="flex-start" align="center">
                        <el-col :span="10">
                            <el-form-item label="QQ扫码图">
                                <input ref="uploadInputQQ" type="file" style="display:none" @change="uploadQQCode">
                                <div v-if="form.qq_code !== null">
                                    <div @click="addQQCode" class="avatar-holder-box">
                                        <img class="avatar" :src="form.qq_code">
                                    </div>
                                </div>
                                <div v-else>
                                    <div @click="addQQCode" class="avatar-holder-box">
                                        <img class="avatar-icon" src="../assets/photo-add.png">
                                    </div>
                                </div>
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item label="微信扫码图">
                                <input ref="uploadInputWx" type="file" style="display:none" @change="uploadWxCode">
                                <div v-if="form.wx_code !== null">
                                    <div @click="addWxCode" class="avatar-holder-box">
                                        <img class="avatar" :src="form.wx_code">
                                    </div>
                                </div>
                                <div v-else>
                                    <div @click="addWxCode" class="avatar-holder-box">
                                        <img class="avatar-icon" src="../assets/photo-add.png">
                                    </div>
                                </div>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-col :span="20">
                        <el-form-item label="工作经历">
                            <el-input type="textarea" v-model="form.work_history"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="20">
                        <el-form-item label="签名">
                            <el-input type="textarea" v-model="form.introduce"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="20">
                        <div class="operate-box">
                            <el-button @click="resetForm">重置</el-button>
                            <div class="gap-box"></div>
                            <el-button :loading="isCommiting" type="primary" @click="handleCommitAction">提交</el-button>
                        </div>
                    </el-col>
                </el-form>
            </div>
        </el-card>
    </div>
</template>

<script>
import { getAboutInfo, commitAboutInfo, getAboutArticleList } from '../api/article'
import { callUploadService } from '../util/common'
export default {
    name: 'Setting',
    created() {
        this.initGetAboutInfo()
        this.initGetAboutArticleList()
    },
    data() {
        return {
            isCommiting: false,
            labelPosition: 'right',
            user: null,
            articleList: [],
            form: {
                nickname: null,
                blog_name: 'ZYProSoft',
                birthday: '1997-07-01',
                avatar: null,
                email: null,
                wx: null,
                qq: null,
                wb: null,
                constellation: null,
                hobby: null,
                job: null,
                company: null,
                github: null,
                facebook: null,
                twitter: null,
                icp: null,
                music: null,
                qq_code: null,
                wx_code: null,
                work_history: null,
                introduce: null,
                article_id: null,
            }
        }
    },
    props: {

    },
    methods: {
        initGetAboutArticleList() {
            getAboutArticleList().then(res=>{
                this.articleList = res.data
            })
        },
        uploadAvatar(event) {
            console.log(event)
            callUploadService('common.upload.uploadFile', {}, event.target.files[0]).then(res => {
                this.form.avatar = res.data.url
            })
        },
        uploadQQCode(event) {
            callUploadService('common.upload.uploadFile', {}, event.target.files[0]).then(res => {
                this.form.qq_code = res.data.url
            })
        },
        uploadWxCode(event) {
            callUploadService('common.upload.uploadFile', {}, event.target.files[0]).then(res => {
                this.form.wx_code = res.data.url
            })
        },
        addQQCode() {
            this.$refs.uploadInputQQ.click()
        },
        addWxCode() {
            this.$refs.uploadInputWx.click()
        },
        addAvatar() {
            console.log('set avatar')
            this.$refs.uploadInput.click()
        },
        initGetAboutInfo() {
            getAboutInfo().then(res => {
                this.user = res.data
                this.form.nickname = res.data.nickname
                this.form.email = res.data.email
                this.form.avatar = res.data.avatar
                Object.keys(res.data.about).forEach(key => {
                    this.form[key] = res.data.about[key]
                })
            })
        },
        resetForm() {

        },
        handleCommitAction() {
            this.isCommiting = true
            commitAboutInfo(this.form).then(res => {
                this.isCommiting = false
                this.$message({ type: 'success', message: '提交成功' })
            }).catch(error => {
                this.isCommiting = false
            })
        }
    },
}
</script>

<style lang="scss" scoped>
.box-card {
    width: 90%;
    .input-form-box {
        margin-left: 50px;
        width: 90%;
        height: 930px;
        .avatar {
            width: 80px;
            height: 80px;
        }
        .avatar-holder-box {
            width: 80px;
            height: 80px;
            background: #e6e6e6;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            .avatar-icon {
                width: 35px;
                height: 35px;
                padding: 10px 10px;
            }
        }
        .tip-box {
            height: 38px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            .tips {
                font-size: 13px;
                color: #707070;
                text-align: center;
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
}
</style>

