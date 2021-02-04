
<template>
    <div>
        <el-breadcrumb separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>文章管理</el-breadcrumb-item>
            <el-breadcrumb-item>博客列表</el-breadcrumb-item>
        </el-breadcrumb>
        <el-card class="box-card">
            <div class="search-box">
                <el-row>
                    <el-col :span="7">
                        <el-input placeholder="请输入内容" v-model="search" class="input-with-select">
                            <el-button slot="append" icon="el-icon-search"></el-button>
                        </el-input>
                    </el-col>
                    <el-col :span="4">
                        <el-button type="primary">搜索</el-button>
                    </el-col>
                </el-row>
            </div>
            <el-table :border="true" :data="tableData" :row-style="{height:'45px'}" :cell-style="{padding:'0px'}">
                <el-table-column type="index"></el-table-column>
                <el-table-column align="center" prop="title" label="标题" width="180">
                </el-table-column>
                <el-table-column align="center" prop="categoryName" label="分类" width="180">
                </el-table-column>
                <el-table-column align="center" prop="author" label="作者" width="180">
                </el-table-column>
                <el-table-column align="center" prop="readCount" label="阅读数">
                </el-table-column>
                <el-table-column align="center" prop="commentCount" label="评论数">
                </el-table-column>
                <el-table-column align="center" prop="date" label="日期">
                </el-table-column>
                <el-table-column align="center" fixed="right" label="操作" width="180">
                    <template slot-scope="scope">
                                            <el-tooltip class="item" effect="dark" content="编辑" placement="top">
                                              <el-button @click="handleEdit(scope.row)" type="primary" icon="iconfont el-icon-edit" size="small"></el-button>
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
      </div>
</template>

<script>
import { createArticle, getList, moveToTrash } from '../api/article'

export default {
    name: 'Article',
    created() {
        this.getList()
    },
    data() {
        return {
            search:'',
            pageIndex: 0,
            pageSize: 10,
            totalCount: 0,
            tableData: []
        }
    },
    props: {

    },
    methods: {
        async getList() {
            let curPageIndex = this.pageIndex - 1 <= 0 ? 0 : this.pageIndex - 1;
            getList(curPageIndex, this.pageSize).then(res => {
                this.totalCount = res.data.total;
                this.tableData = [];
                res.data.list.forEach(item => {
                    const article = {
                        'title': item.title,
                        'content': item.content,
                        'author': item.author.nickname,
                        'date': item.updated_at,
                        'articleId': item.article_id,
                        'readCount': item.read_count,
                        'commentCount': item.comment_count,
                        'categoryName': item.category.name
                    }
                    this.tableData.push(article)
                })
            }).catch(error => {
                console.log(error)
            })
        },
        handleEdit(article) {
            console.log(article);
            this.$router.push({
                name: 'admin-create',
                params:{
                    articleId: article.articleId
                }
            });
            console.log(this.$refs.manageMenuRef);
        },
        handleDelete(article) {
            this.$confirm('此操作将文章移动至回收站, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning',
                center: true
            }).then(() => {
                moveToTrash(article.articleId).then(res => {
                    //刷新
                    this.getList();
                    this.$message({
                        type: 'success',
                        message: '删除成功!'
                    });
                });
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: '已取消删除'
                });
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
    width: 95%;
}

.pagination {
    display: flex;
    align-items: center;
    padding: 20px 0 0 0;
    justify-content: flex-end;
}

.search-box {
    padding-bottom: 20px;
}
</style>

