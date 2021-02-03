
<template>
    <div class="root-box">
        <div class="box-card">
            <div class="title">{{article.title}}</div>
            <div class="summary-box">
                <div class="summary">{{article.content}}</div>
                <div class="more">
                    <el-button @click="handleJumpDetail" class="read-button" type="primary" icon="el-icon-eye" size="small" plain>阅读全文</el-button>
                </div>
            </div>
            <div class="info-box">
                <div class="left-part">
                    <div class="info-item">
                        <img class="icon" src="../assets/calendar.png">
                        <span class="label">{{article.created_at}}</span>
                    </div>
                    <div class="info-item">
                        <img class="icon" src="../assets/user.png">
                        <span class="label">{{article.author.nickname}}</span>
                    </div>
                    <div @click="handleCategoryChoose(category)" class="info-item">
                        <img class="icon" src="../assets/file-open.png">
                        <span class="category-label">{{article.category.name}}</span>
                    </div>
                </div>
                <div class="right-part">
                    <el-tag @click.native="handleTagChoose(item.tag_id, item.name)" type="info" class="tag-item" size="small" v-for="item in article.tags" :key="item.tag_id" effect="dark">
                        {{ item.name }}
                    </el-tag>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'FeedItem',
    created() {
        console.log('FeedsItem articleId:'+JSON.stringify(this.article))
    },
    data() {
        return {}
    },
    props: {
        'article':Object,
    },
    methods: {
        handleCategoryChoose(title) {
            this.$router.push({ name: 'feeds', params: { condition: this.article.category_id, feedsType: 'category', navTitle: this.article.category.name } })
        },
        handleTagChoose(tagId, title) {
            this.$router.push({ name: 'feeds', params: { condition: tagId, feedsType: 'tag', navTitle: title } })
        },
        handleJumpDetail() {
            this.$router.push({ name:'detail', params:{ id:this.article.article_id}})
        }
    },
}
</script>

<style lang="scss" scoped>
.box-card {
    background-color: white;
    margin-top: 20px;
    border: solid 1px #DADADA;
}

.summary-box {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    .summary {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        overflow: hidden;
        text-align: left;
        border-top: solid 1px #DADADA;
        color: #444;
        font-size: 13px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 20px;
        padding: 10px 15px 0px 15px;
    }
    .more {
        display: flex;
        flex-direction: row;
        justify-content: start;
        width: 100%;
        align-items: left;
        align-content: left;
        padding-left: 15px;
        padding-bottom: 15px;
        padding-top: 10px;
    }
}

.title {
    font-size: 20px;
    background: white;
    color: #222222;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    text-align: left;
    line-height: 28px;
    padding: 15px 15px 15px 15px;
}

.read-button {
    color: #00E495;
    border-color: #00E495;
    background-color: white;
}

.info-box {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    border-top: solid 1px #DADADA;
    background-color: #EFEFEF;
    align-items: center;
    .left-part {
        padding-top: 10px;
        padding-bottom: 10px;
        width: 50%;
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: center;
        .info-item {
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
            padding-left: 13px;
            .icon {
                width: 12px;
                height: 12px;
            }
            .label {
                font-size: 12px;
                color: #707070;
                padding: 2px 5px;
            }
            .category-label {
                font-size: 12px;
                color: #707070;
                padding: 2px 5px;
            }
            .category-label:hover {
                color: #00E495;
            }
        }
    }
    .right-part {
        padding-top: 2px;
        padding-bottom: 2px;
        padding-right: 13px;
        width: 50%;
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
        align-items: center;
        .tag-item {
            margin: 5px;
        }
    }
}
</style>

