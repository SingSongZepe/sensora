<?php header("Content-Type: text/html; charset=UTF-8"); 
    // use the utf-8 encoding
    // this file will receive two params
    // - manga_title
    // - chapter_title
    // - WEB_ROOT_SERVER 
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
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SingSongZepe Reader</title>
        <script src="../res/js/jquery-3.7.0.min.js"></script>
        <script src="../res/js/vue.min.js"></script>
        <link rel="stylesheet" href="../res/css/font.css" type="text/css">
        <link rel="stylesheet" href="../res/css/decorator.css" type="text/css">
        <link rel="stylesheet" href="../res/css/color.css" type="text/css">
        <link rel="stylesheet" href="../res/css/pic.css" type="text/css">
        <link rel="stylesheet" href="../reader/css/reader.css" type="text/css">
        <link rel="icon" href="../res/img/reSSZp.png">
    </head>
    <body class="bg-pic bg-pic-setting">
        <div id="app" class="bg-color-default-tran">
            <div class="header bg-color-default-tran2-5">
                <div class="sszp fg-lcolor-sider">
                    <h1 class="font-times" @click="open_sensora()">The Sensora Reader !</h1>
                </div>
                <div class="login font-arial">
                    <div class="">
                        <a class="hover-underline" @click="login()">login</a>
                    </div>
                </div>
            </div>
            <div class="main bg-color-default-tran">
                <div class="img-ctan">
                    <img class="img" :src="READER.picture_url" @click="toggle_pic($event)" alt="manga pictures"></img>
                </div>
            </div>
            <div class="footer bg-color-default-tran">
                <div class="pages-ctan" @click="pages_expander()">
                    <p class="pages font-poppins fg-lcolor-sider">
                        Pages:
                        <a>{{READER.current_page}} / {{READER.total_page}}P</a>
                    </p>
                    <div class="pages-list" style="display: none;">
                        <page-item v-for="(_, page_index) in READER.pictures" v-bind:page_index="page_index"></page-item>
                    </div>
                </div>
                <div class="chapter-info">
                    <h2 class="info-text fg-lcolor-sider font-poppins">{{GET_PICTURES.manga_title}} {{GET_PICTURES.chapter_title}}</h2>
                </div>
                <div class="chapter-list-ctan bg-color-default-tran3" @click="chapter_expander()">
                    <a class="chapter-list-expander font-poppins fg-lcolor-sider">See Chapters</a>
                    <div class="chapter-list bg-color-default-tran" style="display: none">
                        <chapter-item v-for="(_, chapter_index) in READER.chapters" v-bind:chapter_index="chapter_index"></chapter-item>
                    </div>
                </div>
                <div class="next-ctan">
                    <div class="last  bg-color-default-tran3" @click="last_chapter()"></div>
                    <dix class="next  bg-color-default-tran3" @click="next_chapter()"></dix>
                </div>
            </div>
        </div>
    </body>
    <!-- <script src="../res/reader/js/read.js"></script> -->
    <script>
        const PAGE_LIST_ITEM = {
            props: ["page_index"],
            data() {
                return {
                }
            },
            template: `
            <a class="pages-list-item font-poppins" @click="set_picture()">Page {{page_index + 1}} / {{$parent.READER.total_page}}</a>
            `,
            methods: {
                set_picture() {
                    this.$parent.READER.current_page = this.page_index + 1;
                    this.$parent.READER.picture_url = this.$parent.pic_url_constructor(this.$parent.READER.pictures[this.page_index]);
                }
            }
        };
        const CHAPTER_LIST_ITEM = {
            props: ["chapter_index"],
            data() {
                return {
                }
            },
            template: `
            <a class="chapter-list-item" @click="load_in()">{{$parent.READER.chapters[chapter_index]}}</a>
            `,
            methods: {
                load_in() {
                    this.$parent.GET_PICTURES.chapter_title = this.$parent.READER.chapters[this.chapter_index];
                    this.$parent.READER.current_chapter_index = this.chapter_index;
                    this.$parent.load_in();
                }
            }
        };
        let vue = new Vue({
            el: "#app",
            data() {
                return {
                    GET_PICTURES: {
                        WEB_ROOT: "<?php echo $argv[3]?>",
                        // WEB_ROOR: "http://localhost:8080",
                        PICTURES_API: "api/get_pictures",
                        PICTURE_ITEM_API: "api/get_picture_item",
                        CHAPTERS_API: "api/get_chapters", // get chapters title to renew the pictures
                        // filter
                        MANGA_TITLE_: "manga_title",
                        CHAPTER_TITLE_: "chapter_title",
                        XID_: "xid",
                        CHID_: "chid",
                        TITLE_: "title",
                        PAGE_: "page",
                        TYPE_REQUEST: "GET",
                        manga_title: "<?php echo $argv[1]?>",
                        chapter_title: "<?php echo $argv[2]?>",
                        // manga_title: "不該扯上關系的女生成了我女友",
                        // chapter_title: "第1話",
                    },
                    OPEN_PAGE: {
                        WEB_ROOT: "<?php echo $argv[3]?>",
                        // WEB_ROOR: "http://localhost:8080",
                        LOGIN_: "login",
                        SENSORA_: "sensora",
                    },
                    READER: {
                        total_page: 0,     // show to user, so index from 1
                        current_page: 0,   // 
                        picture_url: "",
                        pictures: [],
                        total_chapter_count: 0,  // total number of chapters
                        current_chapter_index: 0, // index from 0
                        chapters: [],
                    },
                    TOGGLE: {
                        img_width: 0, // the width of the img
                    }       
                }
            },
            created() {
                // toggle
                this.TOGGLE.img_width = document.querySelector(".img").offsetWidth;
                // request all the chapters
                this.load_in_chapters();
                // request for picture data
                this.load_in();
            },
            mounted() {
            },
            computed: {
            },
            methods: {
                // open web page
                login() {
                    // call login method
                    window.open(`${this.OPEN_PAGE.WEB_ROOT}/${this.OPEN_PAGE.LOGIN_}`);
                },
                open_sensora() {
                    window.open(`${this.OPEN_PAGE.WEB_ROOT}/${this.OPEN_PAGE.SENSORA_}`);
                },
                // get pictures
                get_pictures() {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: `${this.GET_PICTURES.WEB_ROOT}/${this.GET_PICTURES.PICTURES_API}?${this.GET_PICTURES.MANGA_TITLE_}=${this.GET_PICTURES.manga_title}&${this.GET_PICTURES.CHAPTER_TITLE_}=${this.GET_PICTURES.chapter_title}`,
                            type:   this.GET_PICTURES.TYPE_REQUEST,
                            // cookie ?
                            // xhrFields: {
                            //     withCredentials: true
                            // },
                            success: (pictures) => {
                                resolve(pictures);
                            },
                            error: (xhr, status, err) => {
                                reject(err);
                            },
                        });
                    }); 
                },
                load_in() { // load from server pictures relative parameters of vue object
                    this.get_pictures().then((pictures) => {
                        if (pictures.length > 0) {
                            this.READER.pictures = pictures;
                            this.READER.picture_url = this.pic_url_constructor(pictures[0]);
                            this.READER.current_page = 1;
                            this.READER.total_page = pictures.length;
                            // this.store_picture();
                        }
                    });
                },
                // store_picture() { // load picture to local storage
                //     // pictures from this.READER.pictures
                //     this.READER.pictures.forEach((picture) => {
                //         $.ajax({
                //             url: this.pic_url_constructor(picture),
                //             method: this.GET_PICTURES.TYPE_REQUEST,
                //             xhrFields: {
                //                 responseType: "blob",
                //             },
                //             success: (data) => {
                //                 localStorage.setItem(this.pic_url_constructor(picture), data);
                //             },
                //             error: (_, __, error) => {
                //                 console.log(error);
                //             }
                //         })
                //     })
                // },
                // toggle chapters
                load_in_chapters() { // load from server chapters info relative parameters of vue object
                    this.get_chapters().then((chapters) => {
                        this.READER.chapters = chapters;
                        this.READER.total_chapter_count = chapters.length;
                        chapters.forEach((chapter, idx) => {
                            if (chapter == this.GET_PICTURES.chapter_title) {
                                this.READER.current_chapter_index = idx;
                            }
                        });
                    });
                },
                get_chapters() {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: `${this.GET_PICTURES.WEB_ROOT}/${this.GET_PICTURES.CHAPTERS_API}?${this.GET_PICTURES.MANGA_TITLE_}=${this.GET_PICTURES.manga_title}`,
                            type: this.GET_PICTURES.TYPE_REQUEST,
                            success: (chapters) => {
                                resolve(chapters);
                            },
                            error: (_, __, err) => {
                                reject(err);
                            }
                        })
                    })
                },
                last_chapter() {
                    if (this.READER.current_chapter_index > 0) {
                        this.READER.current_chapter_index -= 1;
                        this.GET_PICTURES.chapter_title = this.READER.chapters[this.READER.current_chapter_index]
                        this.load_in(); // reload pictures from the server
                    } else {
                        alert("already the first one chapter of the manga");
                    }  
                },
                next_chapter() {
                    if (this.READER.current_chapter_index < this.READER.total_chapter_count - 1) {
                        this.READER.current_chapter_index += 1;
                        this.GET_PICTURES.chapter_title = this.READER.chapters[this.READER.current_chapter_index]
                        this.load_in(); // reload pictures from the server
                    } else {
                        alert("already the last chapter of the manga");
                    }
                },
                // toggle picture
                toggle_pic(eve) {
                    if (eve.offsetX < this.TOGGLE.img_width / 2) {
                        this.last();
                    } else {
                        this.next();
                    }
                },
                next() {
                    if (this.READER.current_page < this.READER.total_page) {
                        this.READER.current_page += 1;
                        this.READER.picture_url = this.pic_url_constructor(this.READER.pictures[this.READER.current_page - 1]);
                    } else {
                        alert("already the last one page of manga chapter");
                    }
                },
                last() {
                    if (this.READER.current_page > 1) {
                        this.READER.current_page -= 1;
                        this.READER.picture_url = this.pic_url_constructor(this.READER.pictures[this.READER.current_page - 1]);
                    } else {
                        alert("already the first one page of manga chapter");
                    }
                },
                // utils function
                pic_url_constructor(picture) { // dict Xid, Chid, Title
                    return `${this.GET_PICTURES.WEB_ROOT}/${this.GET_PICTURES.PICTURE_ITEM_API}?${this.GET_PICTURES.XID_}=${picture.Xid}&${this.GET_PICTURES.CHID_}=${picture.Chid}&${this.GET_PICTURES.TITLE_}=${picture.Title}`;
                },
                chapter_expander() { // chapter expander
                    let div_chapter_list = document.querySelector(".chapter-list");
                    if (div_chapter_list.style.display == "none") {
                        div_chapter_list.style.display = "flex";
                        setInterval(() => {
                            div_chapter_list.style.display = "none";
                        }, 5000);
                    } else {
                        div_chapter_list.style.display = "none";
                    }
                },
                pages_expander() {
                    let div_pages_list = document.querySelector(".pages-list");
                    if (div_pages_list.style.display == "none") {
                        div_pages_list.style.display = "flex";
                        setInterval(() => {
                            div_pages_list.style.display = "none";
                        }, 5000);
                    } else {
                        div_pages_list.style.display = "none";
                    }
                }
            },
            components: {
                "chapter-item":CHAPTER_LIST_ITEM,
                "page-item": PAGE_LIST_ITEM,
            }
        });
    </script>
</html>