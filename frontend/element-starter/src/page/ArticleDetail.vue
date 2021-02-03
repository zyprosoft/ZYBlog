
<template>
    <div class="root-box">
        <div class="main-box">
            <div class="article-box">
                <div class="nav-box">
                    <div @click="hanldeGoBack" class="button-box">
                        <img class="back-btn" src="../assets/direction-left.png">
                    </div>
                    <div class="nav-title">{{article.title}}</div>
                </div>
                <div v-if="article != null">
                    <div ref="scrollFlow" class="content-flow-box">
                        <div class="title-box">
                            <div class="info-box">
                                <div class="left-part">
                                    <div class="info-item">
                                        <img class="icon" src="../assets/calendar.png">
                                        <span class="label">{{article.created_at}}</span>
                                    </div>
                                    <div class="info-item">
                                        <img class="icon" src="../assets/user.png">
                                        <span class="label">{{article.author.nickname}}</span>
                                    </div>
                                    <div class="info-item">
                                        <img class="icon" src="../assets/file-open.png">
                                        <span class="label">{{article.category.name}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content-box">
                            <mavon-editor :subfield="false" defaultOpen="preview" style="height: 100%" :toolbarsFlag="false" :editable="false" v-model="article.content"></mavon-editor>
                        </div>
                        <div class="comment-list-box">
                            <div class="comment-info-box">
                                <div class="count-box">共{{article.comment_count}}条评论</div>
                            </div>
                            <div class="comment-list">
                                <div v-for="item in commentList" :key="item.comment_id">
                                    <div class="comment-item-box">
                                        <div class="left-part">
                                            <div v-if="item.author.avatar && item.author.avatar.length > 0">
                                                <img class="avatar" :src="item.author.avatar">
                                            </div>
                                            <div v-else>
                                                <img class="avatar" src="../assets/user-filling.png">
                                            </div>
                                        </div>
                                        <div class="gap-width"></div>
                                        <div class="right-part">
                                            <div class="top-part">
                                                <div class="top-part-left">
                                                    <div v-if="item.author.user_id == article.author.user_id">
                                                        <div class="admin-box">博主</div>
                                                        <div class="gap-width"></div>
                                                    </div>
                                                    <div class="nickname">{{item.author.nickname}}</div>
                                                    <div class="gap-width"></div>
                                                    <div class="post-time">{{item.created_at}}</div>
                                                </div>
                                                <div @click="handleReplyAction(item)" class="reply-box">
                                                    <img class="reply-btn" src="../assets/comment.png">
                                                </div>
                                            </div>
                                            <div class="gap-box"></div>
                                            <div v-if="item.parent_comment_id !== null">
                                                <div class="parent-comment-box">
                                                    <div v-if="item.parent_comment.author.user_id == article.author.user_id">
                                                        <div class="admin-box">博主</div>
                                                    </div>
                                                    <div class="parent-comment-name-box">
                                                        <span class="parent-nickname">{{item.parent_comment.author.nickname}}:</span>
                                                        <span class="parent-content">{{item.parent_comment.content}}</span>
                                                    </div>
                                                </div>
                                                <div class="gap-box"></div>
                                            </div>
                                            <div class="content">{{item.content}}</div>
                                        </div>
                                    </div>
                                    <div></div>
                                </div>
                                <div class="no-more-title" v-if="noMore">
                                    <div v-if="article.comment_count == 0">
                                        <span>哥, 沙发等你来坐</span>
                                    </div>
                                    <div v-else>
                                        <span>哥, 这回真没有了</span>
                                    </div>
                                </div>
                                <div v-else>
                                    <div v-if="isLoadingMore">
                                        <vue-loading type="bars" color="#00E495" :size="{ width: '50px', height: '50px' }"></vue-loading>
                                    </div>
                                    <div v-else>
                                        <div class="click-more" @click="loadMore">点击加载更多</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="comment-bar">
                    <div class="left-part">
                        <div class="avatar-box">
                            <div v-if="form.avatar.length > 0">
                                <img class="avatar-network" :src="form.avatar">
                            </div>
                            <div v-else>
                                <img class="avatar" src="../assets/photo-add.png">
                            </div>
                        </div>
                        <el-button @click="handleSetAvatar" class="avatar-btn" size="small" type="primary" plain>设置头像</el-button>
                    </div>
                    <div class="middle-part">
                        <el-input ref="inputAreaRef" class="input-area" type="textarea" :placeholder="inputAreaPlaceHolder" v-model="form.content" auto-complete="off"></el-input>
                        <el-row :gutter="22">
                            <el-col :span="12">
                                <div class="item-box">
                                    <div class="label-box">
                                        <span class="item-label">马甲</span>
                                    </div>
                                    <el-input placeholder="让我记住您~" v-model="form.name" auto-complete="off"></el-input>
                                </div>
                            </el-col>
                            <el-col :span="12">
                                <div class="item-box">
                                    <div class="label-box">
                                        <span class="item-label">邮箱</span>
                                    </div>
                                    <el-input placeholder="绝对不可能泄漏的~" v-model="form.email" auto-complete="off"></el-input>
                                </div>
                            </el-col>
                        </el-row>
                        <div class="captcha-box">
                            <el-input class="input-box" placeholder="请输入验证码,区分大小写" v-model="form.captcha" auto-complete="off"></el-input>
                            <div v-if="captcha.url.length == 0">
                                <div class="get-captcha-box">
                                    <span @click="getCaptchAction" class="get-captcha">点击获取验证码</span>
                                </div>
                            </div>
                            <div v-else>
                                <img @click="refreshCaptchaAction" class="captcha-img" :src="captcha.url">
                            </div>
                        </div>
    
    
                    </div>
                    <div class="right-part">
                        <div class="btn-box">
                            <el-button @click="hanldeCommitComment" :loading="isCommentPosting" type="primary" class="title">提交</el-button>
                        </div>
                    </div>
                </div>
            </div>
            <side-bar class="side-bar"></side-bar>
        </div>
        <el-dialog title="设置网络头像" :visible.sync="dialogFormVisible">
            <el-form :model="form">
                <el-form-item label="网络图片地址" :label-width="formLabelWidth">
                    <el-input v-model="form.avatar" autocomplete="off"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false">取 消</el-button>
                <el-button type="primary" @click="dialogFormVisible = false">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
import { getArticleDetail, getCommentListByArticleId } from '../api/article'
import { createComment, getCaptcha, refreshCaptcha } from '../api/article'
import { getToken } from '../util/auth'
import SideBar from './SideBar.vue'


export default {
    components: { SideBar },
    name: 'ArticleDetail',
    created() {
        console.log('created get params:' + JSON.stringify(this.$route.params))
        this.articleId = this.$route.params.id
        this.refreshArticleDetail()
    },
    mounted() {},
    data() {
        return {
            dialogFormVisible: false,
            articleId: null,
            noMore: false,
            isLoadingMore: false,
            commentList: null,
            commentCount: null,
            pageIndex: 0,
            pageSize: 10,
            isCommentPosting: false,
            form: {
                avatar: '',
                content: '',
                name: '',
                email: '',
                site: '',
                captcha: ''
            },
            inputAreaPlaceHolder: '请说点什么吧',
            replayCommentId: null,
            article: null,
            captcha: {
                key: '',
                url: '',
            }
        }
    },
    computed: {
        isAdmin() {
            let token = getToken();
            console.log('token is:' + token)
            return token !== undefined && token.length > 0;
        }
    },
    props: {

    },
    watch: {
        $route(to, from) {
            console.log('to params:' + JSON.stringify(to.params))
            console.log('from params:' + JSON.stringify(from.params))
            if (from.params.id == to.params.id) {
                return;
            }
            this.articleId = to.params.id
            this.pageIndex = 0;
            this.noMore = false;
            this.isLoadingMore = false;
            this.refreshFeedsList();
        }
    },
    methods: {
        handleSetAvatar() {
            this.dialogFormVisible = true
        },
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
        handleCommentAction() {
            this.inputAreaPlaceHolder = '请说点什么吧'
            this.replayCommentId = null
            if (this.isAdmin) {
                this.form.email = this.article.author.email;
                this.form.name = this.article.author.nickname;
            }
            if (this.captcha.key.length == 0) {
                this.getCaptchAction()
            } else {
                this.refreshCaptcha()
            }
        },
        hanldeCommitComment() {
            this.isCommentPosting = true;
            let form = this.form
            let captcha = {
                'key': this.captcha.key,
                'code': this.form.captcha
            }
            createComment(this.articleId, form.content, form.name, form.email, form.avatar, this.replayCommentId, captcha).then(res => {
                this.$message({
                    type: 'success',
                    message: '评论成功!'
                });
                this.form.content = ''
                this.form.captcha = ''
                this.captcha.key = ''
                this.captcha.url = ''
                this.refreshArticleDetail()
                this.isCommentPosting = false;
            }).catch(error => {
                this.isCommentPosting = false;
            });
        },
        hanldeGoBack() {
            this.$router.back(-1)
        },
        refreshArticleDetail() {
            if (this.articleId == null) {
                this.$message({
                    type: 'error',
                    message: 'articleId为null'
                });
                return;
            }
            getArticleDetail(this.articleId).then(res => {
                this.article = res.data
                if (this.article.comment_count == 0) {
                    this.noMore = true
                }
            })
            getCommentListByArticleId(this.pageIndex, this.pageSize, this.articleId).then(res => {
                this.commentCount = res.data.total;
                this.commentList = res.data.list;
                if (res.data.list.length < this.pageSize) {
                    this.noMore = true
                }
            })
        },
        handleReplyAction(item) {
            this.$refs.inputAreaRef.focus()
            this.replayCommentId = item.comment_id;
            this.inputAreaPlaceHolder = '回复#' + item.author.nickname + '#';
            if (this.isAdmin) {
                this.form.email = this.article.author.email;
                this.form.name = this.article.author.nickname;
            }
        },
        loadMore() {
            if (this.noMore) {
                return;
            }
            this.pageIndex++
                this.isLoadingMore = true;
            getCommentListByArticleId(this.pageIndex, this.pageSize, this.articleId).then(res => {
                this.isLoadingMore = false
                this.commentCount = res.data.total;
                res.data.list.forEach(element => {
                    this.commentList.push(element)
                });
                if (res.data.list.length < this.pageSize) {
                    this.noMore = true
                }
            }).catch(error => {
                this.isLoadingMore = false
            })
        }
    },
}
</script>

<style lang="scss" scoped>
.root-box {
    background-color: #E6E6E6;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    .main-box {
        display: flex;
        flex-direction: row;
        width: 100%;
        justify-content: flex-start;
        align-items: top;
        .side-bar {
            margin-bottom: 60px;
        }
    }
    .article-box {
        width: 100%;
    }
}

.nav-box {
    width: 100%;
    background-color: #E6E6E6;
    height: 40px;
    margin-top: 10px;
    margin-bottom: 10px;
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    .nav-title {
        background-color: white;
        width: 100%;
        height: 100%;
        font-size: 18px;
        color: #222;
        text-align: center;
        border: solid 1px #DADADA;
        line-height: 40px; // background-color: #00E495;
    }
    .button-box {
        background-color: white;
        margin-left: 20px;
        width: 60px;
        height: 100%;
        align-content: center;
        align-items: center;
        border-left: solid 1px #DADADA;
        border-top: solid 1px #DADADA;
        border-bottom: solid 1px #DADADA;
    }
    .button-box:hover {
        background: #EFEFEF;
    }
    .back-btn {
        width: 25px;
        height: 25px;
        margin-right: 20px;
        margin-left: 20px;
        margin-top: 7.5px;
    }
}

.gap-width {
    width: 7px;
}

.content-flow-box {
    width: 100%;
    height: 100%;
    background-color: #E6E6E6;
    overflow-y: auto;
    overflow-x: hidden;
    padding-bottom: 20px;
    .no-more-title {
        font-size: 10px;
        color: #555;
        text-align: center;
        padding: 12px;
        border-bottom: solid 1px #DADADA;
    }
    .click-more {
        font-size: 10px;
        color: #00E495;
        text-align: center;
        padding: 12px;
        border-bottom: solid 1px #DADADA;
    }
    .content-box {
        background-color: white;
        border-left: solid 1px #DADADA;
        border-right: solid 1px #DADADA;
        margin-left: 20px;
    }
    .comment-list {
        background: white;
        border-left: solid 1px #DADADA;
        border-right: solid 1px #DADADA;
        margin-left: 20px;
    }
    .comment-info-box {
        border-left: solid 1px black;
        border-right: solid 1px black;
        margin-left: 20px;
        background-color: black;
        .count-box {
            color: #00E495;
            text-align: left;
            padding-left: 15px;
            font-size: 13px;
            padding-top: 6px;
            padding-bottom: 6px;
        }
    }
    .comment-item-box {
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: top;
        width: 100%;
        height: 100%;
        border-bottom: solid 1px #DADADA;
        .left-part {
            padding-top: 10px;
            padding-left: 13px;
            height: 50%;
            .avatar {
                width: 45px;
                height: 45px;
                border-radius: 8px;
            }
            .avatar-network {
                width: auto;
                height: auto;
                max-width: 100%;
                max-height: 100%;
            }
        }
        .right-part {
            width: 100%;
            height: 100%;
            padding-top: 5px;
            padding-bottom: 15px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: left;
            .top-part {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                .top-part-left {
                    width: 80%;
                    display: flex;
                    flex-direction: row;
                    justify-content: flex-start;
                    align-items: baseline;
                    .nickname {
                        color: #4e5f5f;
                        font-size: 12px;
                        text-align: left;
                    }
                    .post-time {
                        color: #707070;
                        font-size: 8px;
                        text-align: left;
                    }
                    .admin-box {
                        border: #00E495 1px solid;
                        color: #00E495;
                        font-size: 2px;
                        padding: 1px;
                        margin-right: 5px;
                    }
                }
                .reply-box {
                    width: 30px;
                    padding-right: 20px;
                    .reply-btn {
                        margin-top: 5px;
                        width: 18px;
                        height: 18px;
                    }
                }
                .reply-box:hover {
                    background-color: #EFEFEF;
                }
            }
            .gap-box {
                height: 3px;
            }
            .parent-comment-box {
                display: flex;
                flex-direction: row;
                justify-content: flex-start;
                align-items: baseline;
                align-items: left;
                text-align: left;
                background: #efefef;
                width: 90%;
                padding: 10px 5px;
                margin-bottom: 5px;
                .parent-comment-name-box {
                    padding: 0px 5px;
                    .parent-nickname {
                        font-size: 12px;
                        color: #4C4E4E;
                    }
                    .parent-content {
                        margin-right: 50px;
                        font-size: 12px;
                        color: #4C4E4E;
                    }
                }
                .admin-box {
                    border: #00E495 1px solid;
                    color: #00E495;
                    font-size: 1px;
                    padding: 1px 1px;
                    margin-right: 5px;
                }
            }
            .content {
                color: #4C4E4E;
                font-size: 13px;
                text-align: left;
                padding-right: 13px;
            }
        }
    }
}

.info-box {
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    background: #E6E6E6;
    .left-part {
        border: solid 1px #DADADA;
        background-color: #efefef;
        margin-left: 20px;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        .info-item {
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
            padding-left: 13px;
            .icon {
                width: 12px;
                height: 12px;
            }
            .label {
                font-size: 12px;
                color: #707070;
                padding: 2px 5px;
            }
        }
    }
}

.comment-bar {
    width: 100%;
    height: 155px;
    background: #E6E6E6;
    display: flex;
    flex-direction: row;
    margin-bottom: 70px;
    .left-part {
        width: 120px;
        margin-left: 20px;
        margin-right: 5px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: white;
        border: solid 1px #DADADA;
        .avatar-box {
            background: #E6E6E6;
            width: 90%;
            height: 90%;
            margin-bottom: 10px;
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            .avatar {
                margin-top: 20px;
                width: 44px;
                height: 44px;
            }
            .avatar-network {
                display: inline-block;
                height: auto;
                max-width: 100%;
            }
        }
        .avatar-btn {
            height: 44px;
            margin-bottom: 10px;
        }
    }
    .middle-part {
        background: #E6E6E6;
        display: flex;
        flex-direction: column;
        width: 100%;
        .input-area {
            margin-bottom: 10px;
        }
        .item-box {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            .label-box {
                width: 90px;
                height: 36px;
                background: #dedede;
                margin-right: 5px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                border-radius: 6px;
                .item-label {
                    width: 60px;
                    font: 14px;
                    color: #222;
                    text-align: center;
                }
            }
        }
        .captcha-box {
            margin-top: 10px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-start;
            .input-box {
                width: 100%;
            }
            .captcha-img {
                padding-left: 15px;
                height: 40px;
                width: 150px;
            }
            .get-captcha-box {
                width: 150px;
                height: 40px;
                margin-left: 15px;
                background: black;
                border-radius: 6px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                .get-captcha {
                    color: #00E495;
                    font-size: 10px;
                    width: 150px;
                }
            }
        }
    }
    .right-part {
        background: white;
        width: 120px;
        margin-left: 5px;
        .btn-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 80%;
            height: 88%;
            margin-top: 10px;
            margin-bottom: 10px;
            margin-left: 10px;
            margin-right: 10px;
            border-radius: 6px;
            .title {
                height: 100%;
                width: 100%;
                font-size: 16px;
                color: #00E495;
                background: black;
                border: #00E495 solid 1px;
            }
        }
    }
}
</style>

