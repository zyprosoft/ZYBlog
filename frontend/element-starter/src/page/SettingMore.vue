
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
                        <el-form-item label="新密码">
                            <el-input show-password type="password" v-model="form.passwordFirst" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item label="确认密码">
                            <el-input show-password type="password" v-model="form.passwordSecond" autocomplete="off"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="10">
                        <div style="display:flex;flex-direction:row;">
                            <el-button :loading="isCommiting" @click="commitUpdatePassword" type="primary">确认修改</el-button>
                        </div>
                    </el-col>
                </el-form>
            </div>
        </el-card>
    
    </div>
</template>

<script>
import { clearCache, updatePassword } from '../api/article'
import { removeToken } from '../util/auth'
export default {
    name: 'SettingMore',
    created() {

    },
    data() {
        return {
            isCommiting:false,
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
            let first = this.form.passwordFirst
            let second = this.form.passwordSecond
            console.log('first:'+first+' second:'+second)
            if (first !== second) {
                this.$message({ type: 'error', message: '两次密码输入不一致' })
                return
            }
            this.isCommiting = true
            updatePassword(this.form.passwordFirst).then(res => {
                this.isCommiting = false
                this.$message({ type: 'success', message: '更新成功,请重新登陆' })
                removeToken()
                this.$router.push({name:'login'})
            }).catch(error=>{
                this.isCommiting = false
            })
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

