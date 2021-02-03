
<template>
    <div>
        <div class="side-box">
            <div class="category-box">
                <div class="side-box-common">
                    <div class="side-box-title">分类</div>
                    <div class="side-box-list">
                        <div v-for="item in categoryList" :key="item.category_id">
                            <div @click="handleCategoryChoose(item.category_id, item.name)" class="side-box-item">
                                <img class="box-item-icon" src="../assets/category.png">
                                <div class="box-item-title">{{item.name}}({{item.total}})</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="archive-box">
                <div class="side-box-common">
                    <div class="side-box-title">归档</div>
                    <div class="side-box-list">
                        <div v-for="item in archiveList" :key="item.date">
                            <div @click="handleArchiveDateChoose(item.date)" class="side-box-item">
                                <img class="box-item-icon" src="../assets/packaging.png">
                                <div class="box-item-title">{{item.date}}({{item.total}})</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tag-box">
                <div class="side-box-common">
                    <div class="side-box-title">标签云</div>
                    <div class="side-box-list">
                        <el-tag @click.native="handleTagChoose(item.tag_id, item.tag.name+' ('+item.count+')')" type="info" class="tag-item" size="small" v-for="item in tagList" :key="item.tag_id" effect="plain">
                            {{ item.tag.name }} ({{item.count}})
                        </el-tag>
                    </div>
                </div>
            </div>
            <div class="app-box">
                <div class="side-box-common">
                    <div class="side-box-title">应用信息</div>
                    <div class="app-info-box">
                        <span class="icp">{{aboutInfo.icp}}</span>
                        <div class="gap-box"></div>
                        <span class="server-use">后台框架:<a href="https://hyperf.wiki/2.0/#/"><span class="framework">Hyperf</span></a>
                        </span>
                        <div class="gap-box"></div>
                        <span class="front-use">前台:vue+element-ui</span>
                        <div class="gap-box"></div>
                        <span class="front-use">QQ:{{aboutInfo.qq}}</span>
                        <div class="gap-box"></div>
                        <span class="front-use">微信:{{aboutInfo.wx}}</span>
                        <div class="gap-box"></div>
                        <span class="front-use"><a :href="aboutInfo.github"><span class="framework">Github</span></a>
                        </span>
                        <span><a href="http://https://developer.hitokoto.cn"><span class="framework">一言</span></a></span>
                    </div>
                </div>
            </div>
            <div class="music-box">
                <div class="side-box-music">
                    <div class="side-box-title">音乐盒</div>
                    <div class="side-box-list-music">
                        <audio class="audio-player" controls autoplay loop src="http://www.lulinggushi.com/wp-content/uploads/2020/04/梦词-若相惜-Cover：姜帆.mp3">                                                                                             </audio>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { getHotTags, getAllCategory, getArchiveDateList, getAboutInfo } from '../api/article'
export default {
    name: 'SideBar',
    created() {
        this.getTagList()
        this.getAllCategory()
        this.getArchiveDateList()
        this.initAboutInfo()
    },
    data() {
        return {
            archiveList: [],
            tagList: [],
            categoryList: [],
            aboutInfo:{},
            user:{}
        }
    },
    props: {

    },
    methods: {
        getTagList() {
            getHotTags().then(res => {
                this.tagList = res.data
            })
        },
        getAllCategory() {
            getAllCategory().then(res => {
                this.categoryList = res.data
            })
        },
        getArchiveDateList() {
            getArchiveDateList().then(res => {
                this.archiveList = res.data
            })
        },
        initAboutInfo() {
            getAboutInfo().then(res=>{
                this.aboutInfo = res.data.about
                this.user = res.data 
            })
        },
        handleCategoryChoose(categoryId, title) {
            this.$router.push({ name: 'feeds', params: { condition: categoryId, feedsType: 'category', navTitle: title } })
        },
        handleArchiveDateChoose(date) {
            this.$router.push({ name: 'feeds', params: { condition: date, feedsType: 'archive', navTitle: date } })
        },
        handleTagChoose(tagId, title) {
            console.log('click tag item:' + tagId);
            this.$router.push({ name: 'feeds', params: { condition: tagId, feedsType: 'tag', navTitle: title } })
        }
    },
}
</script>

<style lang="scss" scoped>
.side-box {
    background-color: #E6E6E6;
    width: 240px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    .archive-box {
        max-height: 280px;
        height: 220px;
        background: white;
        margin-left: 10px;
        margin-right: 10px;
        margin-top: 20px;
    }
    .category-box {
        max-height: 280px;
        height: 220px;
        background: white;
        margin-left: 10px;
        margin-right: 10px;
        margin-top: 10px;
    }
    .tag-box {
        max-height: 280px;
        height: 220px;
        background: white;
        margin-left: 10px;
        margin-right: 10px;
        margin-top: 20px;
    }
    .app-box {
        max-height: 280px;
        height: 220px;
        background: white;
        margin-left: 10px;
        margin-right: 10px;
        margin-top: 20px;
    }
    .music-box {
        height: 100px;
        background: white;
        margin-left: 10px;
        margin-right: 10px;
        margin-top: 20px;
        margin-bottom: 15px;
    }
    .side-box-music {
        height: 100%;
        border: solid 1px #DADADA;
        background: white;
        display: flex;
        flex-direction: column;
        .side-box-title {
            background: black;
            color: #00E495;
            padding: 5px 15px;
            text-align: left;
        }
        .side-box-list-music {
            height: 100%;
            width: 100%;
            .audio-player {
                height: 50%;
                width: 80%;
                margin: 0 auto;
                position: relative;
                top: 25%;
            }
        }
    }
    .side-box-common {
        height: 100%;
        border: solid 1px #DADADA;
        background: white;
        display: flex;
        flex-direction: column;
        .side-box-title {
            background: black;
            color: #00E495;
            padding: 5px 15px;
            text-align: left;
        }
        .side-box-list {
            height: 100%;
            width: 100%;
            overflow-y: auto;
            .tag-item {
                margin: 5px;
            }
            .side-box-item {
                display: flex;
                flex-direction: row;
                align-items: center;
                padding: 2px 13px;
                border-bottom: solid 1px #DADADA;
                .box-item-icon {
                    width: 20px;
                    height: 20px;
                    padding-right: 5px;
                }
                .box-item-title {
                    width: 100%;
                    color: #222;
                    text-align: left;
                    line-height: 30px;
                    font-size: 14px;
                }
                .box-item-title:hover {
                    color: #14B27B;
                }
            }
        }
    }
    .app-info-box {
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        font-size: 12px;
        letter-spacing: 0.5px;
        line-height: 27px;
        align-items: center;
        justify-content: center;
        padding-top: 5px;
        .framework {
            color: #00E495;
        }
        .gap-box {
            width: 20px;
        }
        .server-use {
            display: inline-block;
        }
        .front-use {
            display: inline-block;
        }
    }
    ::-webkit-scrollbar {
        width: 14px;
        height: 14px;
    }
    ::-webkit-scrollbar-track,
    ::-webkit-scrollbar-thumb {
        border-radius: 999px;
        border: 5px solid transparent;
    }
    ::-webkit-scrollbar-track {
        box-shadow: 1px 1px 5px rgba(0, 0, 0, .2) inset;
    }
    ::-webkit-scrollbar-thumb {
        min-height: 20px;
        background-clip: content-box;
        box-shadow: 0 0 0 5px rgba(0, 0, 0, .2) inset;
    }
    ::-webkit-scrollbar-corner {
        background: transparent;
    }
}
</style>

