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