
<template>
    <div>
        <div class="nav-box">
            <el-breadcrumb separator-class="el-icon-arrow-right">
                <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
                <el-breadcrumb-item>分类管理</el-breadcrumb-item>
            </el-breadcrumb>
            <el-button :loading="isAdding" type="primary" @click="handleAdd">新增</el-button>
        </div>
        <el-card class="box-card">
            <el-table :border="true" :data="tableData" :row-style="{height:'45px'}" :cell-style="{padding:'0px'}">
                <el-table-column type="index"></el-table-column>
                <el-table-column  prop="name" align="center" label="名字" width="180">
                </el-table-column>
                <el-table-column align="center" prop="category_id" label="编号" width="180">
                </el-table-column>
                <el-table-column align="center" prop="avatar" label="图标">
                </el-table-column>
                <el-table-column align="center" prop="created_at" label="创建时间">
                </el-table-column>
                <el-table-column align="center" prop="updated_at" label="更新时间">
                </el-table-column>
                <el-table-column align="center" fixed="right" label="操作" width="180">
<template slot-scope="scope">
    <el-tooltip class="item" effect="dark" content="编辑" placement="top">
        <el-button :loading="isNameUpdating" @click="handleEditName(scope.row)" type="primary" icon="iconfont el-icon-edit" size="small"></el-button>
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
import { getAllCategory, updateCategory, addCategory, deleteCategory } from '../api/article';

export default {
    name: 'Category',
    created() {
        this.getList();
    },
    data() {
        return {
            isNameUpdating: false,
            isAvatarUpdating: false,
            isAdding: false,
            tableData: []
        }
    },
    props: {

    },
    methods: {
        getList() {
            getAllCategory().then(res => {
                this.tableData = res.data;
            })
        },
        handleEditName(category) {
            this.$prompt('请输入分类名称', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                inputPlaceholder: category.name,
                center: true
            }).then(({ value }) => {
                this.isNameUpdating = true;
                updateCategory(category.category_id, value).then(res => {
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
        handleEditAvatar(category) {
            this.$prompt('请输入图标路径', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                center: true
            }).then(({ value }) => {
                this.$message({
                    type: 'success',
                    message: '修改成功'
                });
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: '取消输入'
                });
            });
        },
        handleDelete(category) {
            this.$confirm('此操作将彻底删除分类, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning',
                center: true
            }).then(() => {
                deleteCategory(category.category_id).then(res => {
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
            this.$prompt('请输入分类名称', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                center: true
            }).then(({ value }) => {
                this.isAdding = true;
                addCategory(value).then(res => {
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
    width: 95%;
    margin: 20px 0px;
}

.nav-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 95%;
}
</style>

