
<template>
    <div>
        <el-card class="box-card">
            <div class="input-form-box">
                <el-form label-position="right" :model="form">
                    <el-form-item label="清空应用缓存:">
                        <el-col :span="4">
                            <el-button @click="doClearCache" size="small" type="primary">点击清空</el-button>
                        </el-col>
                    </el-form-item>
                    <el-col :span="20">
                        <el-form-item label="新密码" prop="pass">
                            <el-input show-password type="password" v-model="form.passwordFirst" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item label="确认密码" prop="checkPass">
                            <el-input show-password type="password" v-model="form.passwordSecond" autocomplete="off"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="10">
                        <div style="display:flex;flex-direction:row;">
                            <el-button @click="commitUpdatePassword" type="primary">确认修改</el-button>
                        </div>
                    </el-col>
                </el-form>
            </div>
        </el-card>
    
    </div>
</template>

<script>
import { clearCache } from '../api/article'
export default {
    name: 'SettingMore',
    created() {

    },
    data() {
        return {
            form: {
                passwordFirst: '',
                passowrdSecond: ''
            }
        }
    },
    props: {

    },
    methods: {
        doClearCache() {
            clearCache().then(res => {
                this.$message({ type: 'success', message: '清理成功' })
            })
        },
        commitUpdatePassword() {

        }
    },
}
</script>

<style lang="scss" scoped>
.box-card {
    width: 70%;
    .input-form-box {
        margin-left: 50px;
        width: 90%;
        height: 800px;
    }
}
</style>

