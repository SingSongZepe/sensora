<?php header('Content-Type: text/html; charset=UTF-8'); 
    // use the utf-8 encoding
    // argv[1] is manga_title
    // argv[2] is WEB_ROOT_SERVER
?>
<!--
    SingSongZepe Sensora.
    Thanks for Evan You.
    © 2023 SingSongZepe Sensora by Vue3.

    ===
    「この能力が光散らす その先に遥かな想いを」
    # 无法成功数字化你的心情 但我一直在这里 #
-->
<!DOCTYPE html>
<html lang="en" class="bg-pic bg-pic-setting">
    <head>
        <meta charset="utf-8">
        <title>SingSongZepe Chapter</title>
        <script src="../res/js/jquery-3.7.0.min.js"></script>
        <script src="../res/js/vue.min.js"></script>
        <link rel="stylesheet" href="../res/css/font.css" type="text/css">
        <link rel="stylesheet" href="../res/css/decorator.css" type="text/css">
        <link rel="stylesheet" href="../res/css/color.css" type="text/css">
        <link rel="stylesheet" href="../res/css/pic.css" type="text/css">
        <link rel="stylesheet" href="../chapter/css/chapter.css" type="text/css">
        <link rel="icon" href="../res/img/reSSZp.png">
    </head>
    <body>
        <div id="app" class="bg-color-default-tran">
            <div class="header bg-color-default-tran2-5">
                <div class="sszp fg-lcolor-sider">
                    <h1 class="font-times" @click="open_sensora()">The Sensora Chapter !</h1>
                </div>
                <div class="login font-arial">
                    <div class="">
                        <a class="hover-underline" @click="login()">login</a>
                    </div>
                </div>
            </div>
            <div class="main bg-color-default-tran2">
                <div class="manga-info">
                    <div class="manga-image" :style="{backgroundImage: 'url(' + ABOUT_MANGA_CHAPTER.manga_chapter_info.cover + ')'}"></div>
                    <div class="manga-text">
                        <div class="manga-title">
                            <h2 class="manga-title-text font-poppins">{{this.ABOUT_MANGA_CHAPTER.manga_chapter_info.title}}</h2>
                        </div>
                        <div class="manga-author">
                            <p class="manga-author-text font-poppins hover-underline">
                                Author: 
                                <a>{{this.ABOUT_MANGA_CHAPTER.manga_chapter_info.author}}</a>
                            </p>
                        </div>
                        <div class="manga-option-info">
                            <p class="manga-status-text font-poppins hover-underline">
                                Status: 
                                <a>{{this.ABOUT_MANGA_CHAPTER.manga_chapter_info.status}}</a>
                            </p>
                            <p class="manga-type-text font-poppins hover-underline">
                                Type:
                                <a>{{display_type}}</a>
                            </p>
                        </div>
                        <div class="manga-description">
                            <p class="manga-description-text font-poppins hover-underline">
                                Description: 
                                <a>{{display_description}}</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="chapters-ctan">
                    <div class="chapters-info">
                        <p class="chapters-info-text font-poppins">
                            Total Chapter Count:
                            <a>{{this.ABOUT_MANGA_CHAPTER.manga_chapter_info.Chapter_Nums}}</a>
                        </p>
                    </div>
                    <div class="chapters">
                        <chapter-item v-for="(chapter, idx) in this.ABOUT_MANGA_CHAPTER.manga_chapter_info.chapters_info" v-bind:chapter="chapter"></chapter-item>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="../chapter/js/chapter.js"></script>
    <script>
        const CHAPTER_ITEM = {
            props: ['chapter'], // Title, Pages
            template: `
            <div class="chapter-item bg-color-default-tran">
                <p class="chapter-item-content font-poppins" @click="jump_to_chapter(chapter.title)">
                    {{chapter.title}}
                    <a class="chapter-pages">({{chapter.pages}}P)</a>
                </p>
            </div>
            `,
            data() {
                return {
                    WEB_DATA: {
                        WEB_ROOT: "<?php echo $argv[2]?>",
                        READER_: "reader",
                        MANGA_TITLE_: "manga_title",
                        CHAPTER_TITLE_: "chapter_title",
                    },
                    GET_MANGA_CHAPTER_INFO: {
                        manga_title: "<?php echo $argv[1]?>",
                        // manga_title: "不該扯上關系的女生成了我女友",
                    },
                }
            },
            methods: {
                jump_to_chapter(chapter_title) {
                    window.open(`${this.WEB_DATA.WEB_ROOT}/${this.WEB_DATA.READER_}?${this.WEB_DATA.MANGA_TITLE_}=${this.GET_MANGA_CHAPTER_INFO.manga_title}&${this.WEB_DATA.CHAPTER_TITLE_}=${chapter_title}`);
                }
            },
        }
        let vue = new Vue({
            el: "#app",
            data() {
                return {
                    GET_MANGA_CHAPTER_INFO: {
                        WEB_ROOT: "<?php echo $argv[2]?>",
                        MANGA_API: "api/get_manga",
                        MANGA_TITLE_: "manga_title",
                        TYPE_REQUEST: "GET",
                        manga_title: "<?php echo $argv[1]?>",
                        // manga_title: "不該扯上關系的女生成了我女友",
                    },
                    ABOUT_MANGA_CHAPTER: {
                        manga_chapter_info: {

                        },
                    },
                    OPEN_PAGE: {
                        WEB_ROOT: "<?php echo $argv[2]?>",
                        LOGIN_: "login",
                        SENSORA_: "sensora",
                    }
                }
            },
            created() {
                this.get_manga_chapter_info().then((manga_chapter_info) => {
                    this.ABOUT_MANGA_CHAPTER.manga_chapter_info = manga_chapter_info;
                })
            },
            mounted() {
            },
            computed: {
                display_type() {
                    if (this.ABOUT_MANGA_CHAPTER.manga_chapter_info.type !== undefined && this.ABOUT_MANGA_CHAPTER.manga_chapter_info.type.length > 0) {
                        return this.ABOUT_MANGA_CHAPTER.manga_chapter_info.type.join(" ");
                    } else {
                        return "None";
                    }
                },
                display_description() {
                    if (this.ABOUT_MANGA_CHAPTER.manga_chapter_info.description === "") {
                        return "No description available";
                    } else {
                        return this.ABOUT_MANGA_CHAPTER.manga_chapter_info.description;
                    }
                }
            },
            methods: {
                login() { 
                    // there the logging function
                    // jump to the login page
                    window.open(`${this.OPEN_PAGE.WEB_ROOT}/${this.OPEN_PAGE.LOGIN_}`);
                },
                open_sensora() {
                    window.open(`${this.OPEN_PAGE.WEB_ROOT}/${this.OPEN_PAGE.SENSORA_}`);
                },
                get_manga_chapter_info() {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: `${this.GET_MANGA_CHAPTER_INFO.WEB_ROOT}/${this.GET_MANGA_CHAPTER_INFO.MANGA_API}?${this.GET_MANGA_CHAPTER_INFO.MANGA_TITLE_}=${this.GET_MANGA_CHAPTER_INFO.manga_title}`,
                            type: this.GET_MANGA_CHAPTER_INFO.TYPE_REQUEST,
                            success: (manga_info) => {
                                resolve(manga_info);
                            },
                            error: (_, __, error) => {
                                reject(error);
                            },
                        })
                    })
                },
            },
            components: {
                "chapter-item": CHAPTER_ITEM,
            }
        });
    </script>
</html>