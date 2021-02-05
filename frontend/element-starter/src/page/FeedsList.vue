
<template>
    <div class="root-box">
        <div class="main-box">
            <div ref="feedsList" class="feeds-list">
                <div v-for="item in articleList" :key="item.article_id">
                    <feed-item v-bind:article="item"></feed-item>
                </div>
                <div class="no-more-title" v-if="noMore">
                    <span>哥, 这回真没有了</span>
                </div>
                <div v-else>
                    <vue-loading type="bars" color="#00E495" :size="{ width: '50px', height: '50px' }"></vue-loading>
                </div>
            </div>
            <side-bar class="side-bar"></side-bar>
        </div>
    </div>
</template>

<script>
import { getListByCategoryId, getListByDate, getListByRecentComment, getListByRecentPost, getListByTagId } from '../api/article';
import FeedItem from './FeedItem'
import SideBar from './SideBar.vue';
export default {
    name: 'FeedsList',
    components: {
        FeedItem,
        SideBar
    },
    created() {
        this.feedsType = this.$route.params.feedsType;
        this.condition = this.$route.params.condition;
        this.navTitle = this.$route.params.navTitle;
        console.log('created route info:' + JSON.stringify(this.$route.params))
        this.refreshFeedsList()
    },
    computed: {
        dropdownTitle() {
            console.log('this feeds type:' + this.feedsType)
            if (this.feedsType == 'recent') {
                return '最近更新';
            } else if (this.feedsType == 'comment') {
                return '最新回复'
            } else {
                return '快速选择'
            }
        }
    },
    mounted() {
        let box = document.body;
        box.addEventListener("scroll", this.handleScroll, true)
    },
    beforeDestroy() {
        window.removeEventListener('scroll', this.handleScroll); //监听页面滚动事件
    },
    data() {
        return {
            noMore: false,
            pageIndex: 0,
            pageSize: 10,
            condition: '',
            feedsType: 'recent',
            articleList: [],
            navTitle: '最近更新'
        }
    },
    props: {

    },
    watch: {
        $route(to, from) {
            let typeEqual = from.params.feedsType == to.params.feedsType;
            let conditionEqual = from.params.condition == to.params.condition;
            if (typeEqual && conditionEqual) {
                return;
            }
            this.condition = to.params.condition;
            this.feedsType = to.params.feedsType;
            this.pageIndex = 0;
            this.noMore = false;
            this.navTitle = to.params.navTitle;
            this.$refs.feedsList.scrollTop = 0
            this.refreshFeedsList();
        }
    },
    methods: {
        handleRrefresh() {
            this.pageIndex = 0;
            this.noMore = false;
            this.$refs.feedsList.scrollTop = 0;
            this.feedsType = this.$route.params.feedsType;
            this.navTitle = this.$route.params.navTitle;
            this.refreshFeedsList();
        },
        handleDropdownChoose(item) {
            if (this.feedsType == item) {
                return;
            }
            let navTitle = '';
            if (item == 'recent') {
                navTitle = '最近更新'
            } else if (item == 'comment') {
                navTitle = '最新回复'
            }
            this.$router.push({ 'name': 'feeds', params: { condition: 'all', feedsType: item, navTitle: navTitle } })
        },
        handleScroll(e) {
            //变量scrollTop是滚动条滚动时，距离顶部的距离
            var scrollTop = e.target.scrollTop;
            //变量windowHeight是可视区的高度
            var windowHeight = e.target.clientHeight;
            //变量scrollHeight是滚动条的总高度
            var scrollHeight = e.target.scrollHeight;
            //滚动条到底部的条件
            if (scrollTop + windowHeight == scrollHeight) {
                if (this.noMore) {
                    return;
                }
                console.log('items:' + this.articleList.length)
                this.pageIndex++;
                this.refreshFeedsList();
                //写后台加载数据的函数
                console.log("距顶部" + scrollTop + "可视区高度" + windowHeight + "滚动条总高度" + scrollHeight);
            }
        },
        refreshFeedsList() {
            switch (this.feedsType) {
                case 'recent':
                    {
                        getListByRecentPost(this.pageIndex, this.pageSize).then(res => {
                            if (res.data.list.length < this.pageSize) {
                                this.noMore = true;
                            }
                            if (this.pageIndex == 0) {
                                this.articleList = res.data.list
                            } else {
                                res.data.list.forEach(element => {
                                    this.articleList.push(element)
                                });
                            }
                        })
                    }
                    break;
                case 'comment':
                    {
                        getListByRecentComment(this.pageIndex, this.pageSize).then(res => {
                            if (res.data.list.length < this.pageSize) {
                                this.noMore = true;
                            }
                            if (this.pageIndex == 0) {
                                this.articleList = res.data.list
                            } else {
                                res.data.list.forEach(element => {
                                    this.articleList.push(element)
                                });
                            }
                        })
                    }
                    break;
                case 'category':
                    {
                        getListByCategoryId(this.condition, this.pageIndex, this.pageSize).then(res => {
                            if (res.data.list.length < this.pageSize) {
                                this.noMore = true;
                            }
                            if (this.pageIndex == 0) {
                                this.articleList = res.data.list
                            } else {
                                res.data.list.forEach(element => {
                                    this.articleList.push(element)
                                });
                            }
                        })
                    }
                    break;
                case 'archive':
                    {
                        getListByDate(this.condition, this.pageIndex, this.pageSize).then(res => {
                            if (res.data.list.length < this.pageSize) {
                                this.noMore = true;
                            }
                            if (this.pageIndex == 0) {
                                this.articleList = res.data.list
                            } else {
                                res.data.list.forEach(element => {
                                    this.articleList.push(element)
                                });
                            }
                        })
                    }
                    break;
                case 'tag':
                    {
                        getListByTagId(this.condition, this.pageIndex, this.pageSize).then(res => {
                            if (res.data.list.length < this.pageSize) {
                                this.noMore = true;
                            }
                            if (this.pageIndex == 0) {
                                this.articleList = res.data.list
                            } else {
                                res.data.list.forEach(element => {
                                    this.articleList.push(element)
                                });
                            }
                        })
                    }
                    break;
                default:
                    {
                        getListByRecentPost(this.pageIndex, this.pageSize).then(res => {
                            if (res.data.list.length < this.pageSize) {
                                this.noMore = true;
                            }
                            if (this.pageIndex == 0) {
                                this.articleList = res.data.list
                            } else {
                                res.data.list.forEach(element => {
                                    this.articleList.push(element)
                                });
                                console.log("concat:" + this.articleList)
                            }
                        }).catch(error => {

                        })
                    }
                    break;
            }
        }
    }
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
        .feeds-list {
            width: 100%;
            height: 100%;
            background-color: #E6E6E6;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 20px;
            margin: 0px 0px 20px 20px;
            .no-more-title {
                font-size: 10px;
                color: #555;
                text-align: center;
                padding: 12px;
            }
        }
        .side-bar {
            margin-top: 10px;
            margin-bottom: 50px;
        }
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
</style>

