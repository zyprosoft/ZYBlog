
<template>
    <div>
        <el-breadcrumb separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>评论管理</el-breadcrumb-item>
        </el-breadcrumb>
        <el-card class="box-card">
            <el-table :border="true" :data="tableData" :row-style="{height:'45px'}" :cell-style="{padding:'0px'}">
                <el-table-column align="center" prop="commentId" label="编号" width="180">
                </el-table-column>
                <el-table-column prop="content" align="center" label="内容" width="180">
                </el-table-column>
                <el-table-column align="center" prop="parentComment" label="回复评论">
                </el-table-column>
                <el-table-column align="center" prop="articleTitle" label="引用文章">
                </el-table-column>
                <el-table-column align="center" prop="authorName" label="作者">
                </el-table-column>
                <el-table-column align="center" prop="createTime" label="创建时间">
                </el-table-column>
                <el-table-column align="center" prop="updateTime" label="更新时间">
                </el-table-column>
                <el-table-column align="center" fixed="right" label="操作" width="180">
                    <template slot-scope="scope">
                                                                            <el-tooltip class="item" effect="dark" content="回复" placement="top">
                                                                                                  <el-button @click="handleReply(scope.row)" type="primary" icon="iconfont el-icon-comment" size="small"></el-button>
                                                                                                </el-tooltip>
                                                                                                <el-tooltip class="item" effect="dark" content="移动至回收站" placement="top">
                                                                                                  <el-button @click="handleDelete(scope.row)" type="danger" icon="iconfont el-icon-ashbin" size="small"></el-button>
                                                                                                </el-tooltip>
</template>
                </el-table-column>
  </el-table>
  <el-pagination class="pagination"
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
      :current-page="pageIndex"
      :page-sizes="[10, 20, 30, 40]"
      :page-size="pageSize"
      layout="total, sizes, prev, pager, next, jumper"
      :total="totalCount">
    </el-pagination>
  </el-card>
  <el-dialog class="dialog-box" title="快速回复" :visible.sync="dialogFormVisible">
    <div class="dialog-content-box">
            <el-form   :model="reply">
                <el-col>
                    <div class="origin-article-box">
                        <span class="origin-article">原文:《{{reply.title}}》</span></span></span>
                    </div>
                </el-col>
                <el-col>
                    <div class="origin-box">
                        <span class="origin-comment">{{reply.nickname}}:{{reply.origin}}</span>
                    </div>
                </el-col>
                <el-col :span="20">
                    <el-form-item label="回复内容" label-width="180px">
                        <el-input type="textarea" v-model="reply.content" autocomplete="off"></el-input>
                </el-form-item>
                </el-col>
            </el-form>
            <div class="operate-box">
                <el-button @click="dialogFormVisible = false">取 消</el-button>
                <el-button :loading="isCommiting" type="primary" @click="handleCommitReply">确 定</el-button>
            </div>
        </div>          
  </el-dialog>
  </div>
</template>

<script>
import { getCommentList, deleteComment, adminReply } from '../api/article';

export default {
    name: 'Comment',
    created() {
        this.getList();
    },
    data() {
        return {
            isCommiting: false,
            dialogFormVisible: false,
            reply: {
                origin: null,
                nickname: null,
                content: null,
                commentId: null,
                title: null
            },
            pageIndex: 0,
            pageSize: 10,
            totalCount: 0,
            tableData: []
        }
    },
    props: {

    },
    methods: {
        handleDelete(comment) {
            deleteComment(comment.comment_id).then(res => {
                this.getList()
            })
        },
        handleCommitReply() {
            adminReply(this.reply.content, this.reply.commentId).then(res => {
                this.isCommiting = false
                this.$message({ type: 'success', message: '回复成功' })
                this.dialogFormVisible = false
            }).catch(error => {
                this.isCommiting = false
            })
        },
        handleReply(comment) {
            this.dialogFormVisible = true
            this.reply.origin = comment.content
            this.reply.nickname = comment.authorName
            this.reply.commentId = comment.commentId
            this.reply.title = comment.articleTitle
        },
        async getList() {
            getCommentList(this.pageIndex, this.pageSize).then(res => {
                this.tableData = [];
                res.data.list.forEach(element => {
                    const comment = {
                        'articleTitle': element.article.title,
                        'content': element.content,
                        'authorName': element.author.nickname,
                        'commentId': element.comment_id,
                        'parentComment': element.parent_comment ? element.parent_comment.content : "无",
                        'createTime': element.created_at,
                        'updateTime': element.updated_at
                    }
                    this.tableData.push(comment);
                });
            }).catch(error => {
                console.log(error);
            });
        },
        handleSizeChange(val) {
            console.log('size change');
            this.pageSize = val;
            this.getList();
        },
        handleCurrentChange(val) {
            this.pageIndex = val;
            this.getList();
            console.log('current change');
            console.log(this.pageIndex);
        }
    },
}
</script>

<style lang="scss" scoped>
.box-card {
    margin: 20px 0px;
    width: 90%;
}

.pagination {
    display: flex;
    align-items: center;
    padding: 20px 0 0 0;
    justify-content: flex-end;
    width: 90%;
}

.dialog-content-box {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    .origin-box {
        margin-bottom: 10px;
        padding: 10px 10px;
        background: #eee;
        .origin-comment {
            font-size: 14px;
            color: #666;
        }
    }
    .origin-article-box {
        margin-bottom: 10px;
        padding: 10px 10px;
        background: #eee;
        .origin-article {
            font-size: 14px;
            color: #666;
        }
    }
    .reply-box {
        width: 100%;
        height: 50px;
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: top;
    }
}
</style>

