<template>
    <div class="root-box">
        <el-breadcrumb separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>文章管理</el-breadcrumb-item>
            <el-breadcrumb-item>书写博客</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="title-box">
            <div class="left-box">
                <el-row :gutter="12">
                    <el-col :span="12">
                        <el-input placeholder="请输入内容" v-model="title" maxlength="20" show-word-limit>
                            <template slot="prepend">标题</template>
                    </el-input>
                </el-col>
                <el-col :span="4">
                    <el-select value-key="category_id" v-model="selectCategory" placeholder="请选择分类">
                        <el-option v-for="item in categories" :key="item.category_id" :label="item.name" :value="item.category_id">
                            <span>{{ item.name }}</span>
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>
            </div>
            <div class="right-box">
                <el-button type="info">存草稿</el-button>
                <el-button :loading="isPosting" type="primary" @click="postArticle">发布</el-button>
            </div>
        </div>
        <div class="tag-box">
            <el-tag :key="tag" v-for="tag in dynamicTags" closable :disable-transitions="false" @close="handleClose(tag)">
                {{tag}}
            </el-tag>
            <el-input class="input-new-tag" v-if="inputVisible" v-model="inputValue" ref="saveTagInput" size="small" @keyup.enter.native="handleInputConfirm" @blur="handleInputConfirm">
            </el-input>
            <el-button v-else class="button-new-tag" size="small" @click="showInput">+新标签</el-button>
        </div>
        <div id="editor">
            <mavon-editor ref="md" style="height: 100%" @imgAdd="handleUploadImage" v-model="content"></mavon-editor>
        </div>
    </div>
</template>

<script>
import { getAllCategory, createArticle, getArticleDetail, updateArticle } from '../api/article'
import { callUploadService } from '../util/common'
import { mavonEditor } from "mavon-editor";
import "mavon-editor/dist/css/index.css";

export default {
    name: 'CreateArticle',
    created() {

    },
    components: {
        mavonEditor
        // or 'mavon-editor': mavonEditor
    },
    watch: {
        $route(to, from) {
            console.log(to.name)
            if(to.name !== 'admin-create') {
                return
            }
            this.articleId = to.params.articleId
            this.getArticleDetail()
        }
    },
    data() {
        return {
            articleId: null,
            fileList: [],
            title: '',
            dynamicTags: ['生活'],
            inputVisible: false,
            inputValue: '',
            active: 'show',
            categories: [],
            selectCategory: '',
            content: '',
            isPosting: false
        }
    },
    computed: {},
    props: {

    },
    created() {
        this.getAllCategory();
        this.getArticleDetail();
    },
    methods: {
        handleUploadImage(pos, file) {
            console.log('handle upload image')
            callUploadService('common.upload.uploadFile', {}, file).then(res => {
                let url = res.data.url
                console.log('server image url:' + url)
                this.$refs.md.$img2Url(pos, url)
            })
        },
        async getAllCategory() {
            getAllCategory().then(res => {
                this.categories = res.data;
                this.selectCategory = res.data[0].category_id;
            }).catch(error => {
                console.log("get category list fail");
            })
        },
        handleClose(tag) {
            this.dynamicTags.splice(this.dynamicTags.indexOf(tag), 1);
        },

        showInput() {
            this.inputVisible = true;
            this.$nextTick(_ => {
                this.$refs.saveTagInput.$refs.input.focus();
            });
        },

        handleInputConfirm() {
            let inputValue = this.inputValue;
            if (inputValue) {
                this.dynamicTags.push(inputValue);
            }
            this.inputVisible = false;
            this.inputValue = '';
        },

        postArticle() {
            this.isPosting = true;
            let articleId = this.articleId;
            console.log(articleId);
            if (!articleId) {
                createArticle(this.title, this.content, this.dynamicTags, this.selectCategory).then(res => {
                    this.isPosting = false;
                    this.$message({
                        message: "发布成功",
                        type: 'success',
                        duration: 5 * 1000
                    });
                    this.title = '';
                    this.content = '';
                    this.dynamicTags = ['生活'];
                }).catch(error => {
                    this.isPosting = false;
                })
            } else {
                updateArticle(articleId, this.title, this.content, this.dynamicTags, this.selectCategory).then(res => {
                    this.isPosting = false;
                    this.$message({
                        message: "发布成功",
                        type: 'success',
                        duration: 5 * 1000
                    });
                }).catch(error => {
                    this.isPosting = false;
                })
            }
        },

        getArticleDetail() {
            let articleId = this.articleId;
            console.log(articleId);
            if (articleId === null) return;
            getArticleDetail(articleId).then(res => {
                this.title = res.data.title;
                this.dynamicTags = [];
                res.data.tags.forEach(element => {
                    this.dynamicTags.push(element.name)
                });
                this.selectCategory = res.data.category.category_id;
                this.content = res.data.content;
            });
        }
    }
}
</script>

<style lang="scss" scoped>
.title-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 20px;
    padding-top: 20px;
    .left-box {
        width: 100%;
    }
    .right-box {
        padding-right: 25px;
        position: relative;
        width: 20%;
        display: flex;
        justify-content: flex-end;
    }
}

.el-tag+.el-tag {
    margin-left: 10px;
}

.tag-box {
    padding-left: 0px;
    display: flex;
    justify-content: flex-start;
    padding-bottom: 20px;
}

.button-new-tag {
    margin-left: 10px;
    height: 32px;
    line-height: 30px;
    padding-top: 0;
    padding-bottom: 0;
    show {
        display: inline-block;
    }
    hidden {
        display: none;
    }
}

.input-new-tag {
    width: 90px;
    margin-left: 10px;
    vertical-align: bottom;
}

#editor {
    width: 95%;
    height: 580px;
}
</style>

