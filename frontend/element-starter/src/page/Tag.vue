
<template>
    <div>
        <div class="nav-box">
            <el-breadcrumb separator-class="el-icon-arrow-right">
                <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
                <el-breadcrumb-item>标签管理</el-breadcrumb-item>
            </el-breadcrumb>
            <el-button :loading="isAdding" type="primary" @click="handleAdd">新增</el-button>
        </div>
        <el-card class="box-card">
            <el-table :border="true" :data="tableData" :row-style="{height:'45px'}" :cell-style="{padding:'0px'}">
                <el-table-column type="index"></el-table-column>
                <el-table-column prop="name" align="center" label="名字" width="180">
                </el-table-column>
                <el-table-column align="center" prop="tag_id" label="编号" width="180">
                </el-table-column>
                <el-table-column align="center" prop="created_at" label="创建时间">
                </el-table-column>
                <el-table-column align="center" prop="updated_at" label="更新时间">
                </el-table-column>
                <el-table-column align="center" fixed="right" label="操作" width="180">
                    <template slot-scope="scope">
                                            <el-tooltip class="item" effect="dark" content="编辑" placement="top">
                                              <el-button @click="handleEdit(scope.row)" type="primary" icon="iconfont el-icon-edit" size="small"></el-button>
                                            </el-tooltip>
                                            <el-tooltip class="item" effect="dark" content="彻底删除" placement="top">
                                              <el-button @click="handleDelete(scope.row)" type="danger" icon="iconfont el-icon-ashbin" size="small"></el-button>
                                            </el-tooltip>
</template>
                </el-table-column>
  </el-table>
  </el-card>
  </div>
</template>

<script>
import { getAllTag, updateTag, addTag, deleteTag } from '../api/article';

export default {
    name: 'Tag',
    created() {
        this.getList();
    },
    data() {
        return {
            isNameUpdating: false,
            isAdding: false,
            tableData: []
        }
    },
    props: {

    },
    methods: {
        getList() {
            getAllTag().then(res => {
                this.tableData = res.data;
            })
        },
        handleEdit(tag) {
            this.$prompt('请输入标签名称', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                inputPlaceholder: tag.name,
                center: true
            }).then(({ value }) => {
                this.isNameUpdating = true;
                updateTag(tag.tag_id, value).then(res => {
                    this.isNameUpdating = false;
                    this.getList();
                    this.$message({
                        type: 'success',
                        message: '修改成功'
                    });
                }).catch(error => {
                    this.isNameUpdating = false;
                });
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: '取消输入'
                });
            });
        },
        handleDelete(tag) {
            this.$confirm('此操作将彻底删除标签, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning',
                center: true
            }).then(() => {
                deleteTag(tag.tag_id).then(res => {
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
        handleAdd() {
            this.$prompt('请输入标签名称', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                center: true
            }).then(({ value }) => {
                this.isAdding = true;
                addTag(value).then(res => {
                    this.isAdding = false;
                    this.getList();
                    this.$message({
                        type: 'success',
                        message: '添加成功'
                    });
                }).catch(error => {
                    this.isAdding = false;
                });
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: '取消输入'
                });
            });
        }
    },
}
</script>

<style lang="scss" scoped>
.box-card {
    margin: 20px 0px;
    width: 95%;
}

.nav-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 95%;
}
</style>

