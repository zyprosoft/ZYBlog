
<template>
    <div class="root-box">
        <div class="main-box">
            <div class="article-box">
                <div class="nav-box">
                    <div @click="backforward" class="button-box">
                        <img class="back-btn" src="../assets/direction-left.png">
                    </div>
                    <div class="nav-title">{{title}}</div>
                </div>
                <div class="content-flow-box">
                    <div class="content-box">
                        <mavon-editor :subfield="false" defaultOpen="preview" style="height: 100%" :toolbarsFlag="false" :editable="false" v-model="content"></mavon-editor>
                    </div>
                </div>
            </div>
            <side-bar class="side-bar"></side-bar>
        </div>
    </div>
</template>

<script>
import SideBar from './SideBar.vue'
import { getAboutPageInfo } from '../api/article'
export default {
    name: 'About',
    created() {
        this.initPageInfo()
    },
    components: {
        SideBar
    },
    data() {
        return {
            title:'关于我',
            content: ''
        }
    },
    props: {

    },
    methods: {
        initPageInfo() {
            getAboutPageInfo().then(res=>{
                this.title = res.data.title
                this.content = res.data.content
            })
        },
        backforward() {
            this.$router.back(-1)
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
        .content-flow-box {
            width: 100%;
            height: 100%;
            background-color: #E6E6E6;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 120px;
            .content-box {
                width: 100%;
                height: 100%;
                background: white;
                border-left: solid 1px #DADADA;
                border-right: solid 1px #DADADA;
                margin-right: 13px;
                margin-left: 20px;
            }
        }
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
</style>

