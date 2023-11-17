<?php header('Content-Type: text/html; charset=UTF-8');
    // php use to send will WEB_ROOT_SERVER
    // argv[1] is WEB_ROOT_SERVER
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
        <meta charset="utf-8"/>
        <title>SingSongZepe Manga</title>
        <script src="../res/js/jquery-3.7.0.min.js"></script>
        <script src="../res/js/vue.min.js"></script>
        <link rel="stylesheet" href="../res/css/font.css" type="text/css">
        <link rel="stylesheet" href="../res/css/decorator.css" type="text/css">
        <link rel="stylesheet" href="../res/css/color.css" type="text/css">
        <link rel="stylesheet" href="../res/css/pic.css" type="text/css">
        <link rel="stylesheet" href="../sensora/css/sensora.css" type="text/css">
        <link rel="icon" href="../res/img/reSSZp.png">
    </head>
    <body class="bg-pic bg-pic-setting">
        <div id="app" class="bg-color-default-tran">
            <div class="header bg-color-default-tran2-5">
                <div class="sszp fg-lcolor-sider">
                    <h1 class="font-times fg-lcolor-sider" @click="open_sszp()">The Sensora !</h1>
                </div>
                <div class="login font-arial">
                    <div class="">
                        <a class="hover-underline" @click="login()">login</a>
                    </div>
                </div>
            </div>
            <div class="main bg-color-default-tran2">
                <div class="manga-list" >
                    <div class="manga-list-title">
                        <p class="fg-color-text-art">Hello World! Welcome to SingSongZepe Manga</p>
                    </div>
                    <!-- v-for="manga_info in manga_infos" v-bind:manga_info="manga_info" -->
                    <book-item v-for="manga_info in about_mangas.manga_infos" v-bind:manga_info="manga_info"></book-item>
                </div>
                <!-- other info -->
                <div class="tag-list"> 
                    
                </div>
            </div>
            <div class="footer bg-color-default-tran2">
                <div class="textual">
                    <h3 class="textual-text fg-lcolor-sider font-poppins" @click="fresh_textual()">
                        {{display_textual}}
                    </h3>
                    <p class="textual-author fg-lcolor-sider font-poppins">
                        {{display_author}}
                    </p>
                    <p class="textual-time font-poppins">
                        {{display_time}}
                    </p>
                </div>
            </div>
        </div>
    </body>
    <script src="../sensora/js/sensora.js"></script>
    <script>
        // components template
        const BOOK_ITEM = {
            props: ['manga_info'],
            data() {
                return {
                    web_data: {
                        WEB_ROOT: "<?php echo $argv[1]?>",
                        CHAPTER_: "chapter",        
                        MANGA_TITLE_: "manga_title",
                    }
                }
            },
            // book item template
            // there in the template maybe title, but it is hard to repair
            template: `
            <div class="manga-item-content">
                <div class="manga-pic" :style="{backgroundImage: 'url(' + manga_info.cover + ')'}"></div>
                <div class="manga-text-ctan">
                    <a class="manga-title" @click="jump_to_manga(manga_info.title)">{{manga_info.title}}</a>
                    <a class="manga-xid">Xid: {{manga_info.xid}}</a>
                    <a class="manga-status">Manga Status: {{manga_info.status}}</a>
                    <a class="manga-type">Type: {{display_type}}</a>
                </div>
            </div>
            `,
            methods: {
                log_hello_world() {
                    console.log("Hello, World!");
                },
                jump_to_manga(manga_title) {
                    console.log("Jump to manga: " + manga_title);
                    window.open(`${this.web_data.WEB_ROOT}/${this.web_data.CHAPTER_}?${this.web_data.MANGA_TITLE_}=${manga_title}`, "_blank");
                }
            },
            computed: {
                display_type() {
                    if (this.manga_info.type.length > 0) {
                        return this.manga_info.type.join(" ");
                    } else {
                        return "None";
                    }
                }
            },
        };
        let vue = new Vue({
            el: "#app",
            data() {
                return {
                    req: { // use to get random mangas
                        WEB_ROOT: "<?php echo $argv[1]?>",
                        GET_RANDOM_MANGAS_: "api/get_random_mangas",
                        NUMBERS_: "numbers",
                        NUMBERS: 6,  // there how many mangas you want get
                        LOGIN_: "login",
                    },
                    open_website: {
                        URL_SSZP: "http://singsongzepe.top"
                    },
                    about_mangas: {
                        manga_infos: [
                        ]
                    },
                    GET_TEXTUAL: {
                        WEB_ROOT: "<?php echo $argv[1]?>",
                        GET_RANDOM_TEXTUAL: "api/get_textual",
                        TYPE_REQUEST: "GET",
                    },
                    TEXTUAL: {
                        textual: '',
                        author: '',
                        time: '',
                        TEXTUAL_: 'textual',
                        AUTHOR_: 'author',
                        TIME_: 'time',
                    }
                };
            },
            created() {
                // get random mangas from the website
                this.get_random_mangas().then((data) => {
                    this.about_mangas.manga_infos = data;  // there the data is the mangas
                });
                this.fresh_textual();
            },
            mounted() {
            },
            computed: {
                display_textual() {
                    if (this.TEXTUAL.textual == "") {
                        return "盖客子游余，冯长江而东游，御清风而北上，驾鹤如仙，雕阖印帷，凡子不羡东山，亦哭然而富识。时二十三年五月七日，泪中洛阳，潇潇穹望，不曾望穿。";
                    } else {
                        return this.TEXTUAL.textual;
                    }
                },
                display_author() {
                    if (this.TEXTUAL.author == "") {
                        return "from 《东里序 离》 SingSongZepe";
                    } else {
                        return "from " + this.TEXTUAL.author;
                    }
                },
                display_time() {
                    if (this.TEXTUAL.time == "") {
                        return "None";
                    } else {
                        return this.TEXTUAL.time;
                    }
                }
            },
            methods: {
                get_random_mangas() { // the get random mangas method
                    return new Promise((resolve, reject) => {
                        $.get(`${this.req.WEB_ROOT}/${this.req.GET_RANDOM_MANGAS_}?${this.req.NUMBERS_}=${this.req.NUMBERS}`, (data) => {
                            // get manga_infos object
                            // the exact value, you can see the results
                            // there data is already the jsonObject
                            resolve(data);
                        }).fail((_, __, err) => { 
                            reject(err);
                        }); 
                    });
                },
                login() { // there the logging function
                    // jump to the login page
                    window.open(`${this.req.WEB_ROOT}/${this.req.LOGIN_}`);
                },
                open_sszp() {
                    window.open(this.open_website.URL_SSZP, "_blank")
                },
                get_textual() { // get textual from the server
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: `${this.GET_TEXTUAL.WEB_ROOT}/${this.GET_TEXTUAL.GET_RANDOM_TEXTUAL}`,
                            type: this.GET_TEXTUAL.TYPE_REQUEST,
                            success: (textual) => {
                                resolve(textual);
                            },
                            error: (_, __, error) => {
                                reject(error);
                            }
                        })
                    })
                },
                fresh_textual() { // request the server to get textual
                    this.get_textual()
                    .then((textual) => {
                        this.TEXTUAL.textual = textual[this.TEXTUAL.TEXTUAL_];
                        this.TEXTUAL.author = textual[this.TEXTUAL.AUTHOR_];
                        this.TEXTUAL.time = textual[this.TEXTUAL.TIME_];
                    }).catch((_) => {
                        console.log("Failed to get textual from server");
                    })
                }
            },
            components: {
                "book-item": BOOK_ITEM,
            }
        });
    </script>
</html>
