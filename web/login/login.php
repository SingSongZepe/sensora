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
        <title>SingSongZepe Login</title>
        <script src="../res/js/jquery-3.7.0.min.js"></script>
        <script src="../res/js/vue.min.js"></script>
        <script src="../res/js/jsencrypt.min.js"></script>
        <link rel="stylesheet" href="../res/css/font.css" type="text/css">
        <link rel="stylesheet" href="../res/css/decorator.css" type="text/css">
        <link rel="stylesheet" href="../res/css/color.css" type="text/css">
        <link rel="stylesheet" href="../res/css/pic.css" type="text/css">
        <link rel="stylesheet" href="../login/css/login.css" type="text/css">
        <link rel="icon" href="../res/img/reSSZp.png">
    </head>
    <body class="bg-pic-2 bg-pic-setting">
        <div id="app" class="login">
            <form class="login-form">
                <h1 class="login-title font-poppins fg-lcolor-sider">Login to SingSongZepe Sensora !</h1>
                <div class="login-content">
                    <div class="login-box">
                        <div class="login-user">
                            <div class="login-user-icon bg-pic-user"></div>
                            <input type="email" class="login-user-enter font-poppins bg-color-tran" placeholder="email" v-model="LOGIN.user"></input>
                        </div>
                    </div>
                    <div class="login-box">
                        <div class="login-password">
                            <div class="login-password-icon bg-pic-password font-poppins"></div>
                            <input type="password" class="login-password-enter font-poppins bg-color-tran" placeholder="password" v-model="LOGIN.password"></input>
                            <div class="login-eye-icon bg-pic-eye-close" @click="eye_toggle()">
                        </div>
                    </div>
                </div>
                <div class="login-check">
                    <div class="login-check-remember">
                        <input type="checkbox" class="login-check-remember-input">
                        <p class="login-check-remember-text font-poppins">Remember me</p>
                    </div>
                    <div class="login-check-forget">
                        <p class="login-check-forget-text font-poppins fg-color-default-tran3" @click="open_forget()">Forget Password?</p>
                    </div>
                </div>
                <div class="login-button-ctan">
                    <button type="button" class="login-button font-poppins" @click="login()">Login</button>
                </div>
                <div class="login-register">
                    <p class="login-register-text font-poppins">
                        Don't have an account?
                        <a @click="open_register()" class="hover-underline fg-color-white">Register</a>
                    </p>
                </div>
            </form>
            <div class="login-not-fit">
            </div>
        </div>
    </body>
    <script src="../login/js/login.js"></script>
    <script>
        let vue = new Vue({
            el: "#app",
            data() {
                return {
                    EYE_TOGGLE: {
                        eye_closed: true,
                        CLASS_EYE: "bg-pic-eye",
                        CLASS_EYE_CLOSED: "bg-pic-eye-close",
                        TYPE_EYE: "password",
                        TYPE_EYE_CLOSED: "text",
                    },
                    LOGIN: {
                        user: "",
                        password: "",
                    },
                    OPEN_PAGE: {
                        WEB_ROOT: "<?php echo $argv[1]?>",
                        REGISTER_: "register",
                        FORGET_: "forget",
                        SENSORA_: "sensora",
                    },
                    LOGIN_REQUEST: {
                        WEB_ROOT: "<?php echo $argv[1]?>",
                        LOGIN_API: "api/login",
                        POST_: "POST",
                    },
                    MESSAGE_NOT_FIT: {
                        MESSAGE_NO_LESS_THEN_: "password cannot less than 6 characters",
                        MESSAGE_LOGIN_FAILED: "cause username or password is invalid, login failed",
                        MESSAGE_NONE_: "",
                    },
                    GET_PUBLIC_KEY: {
                        PUBLIC_KEY_URL: "<?php echo $argv[1]?>/api/get_public_key",
                        GET_: "GET",
                    },
                }
            },
            created() {
            },
            methods: {
                log_hello_world() {
                    console.log("Hello, World!");
                },
                login() {
                    // encrypt and send to the server /api/login
                    let div_not_fit = document.querySelector(".login-not-fit");

                    if (this.LOGIN.password.length < 6) {
                        div_not_fit.textContent = this.MESSAGE_NOT_FIT.MESSAGE_NO_LESS_THEN_;
                        setTimeout(() => {
                            div_not_fit.textContent = this.MESSAGE_NOT_FIT.MESSAGE_NONE_;
                        }, 3000);
                        console.log("error password no less than 6 characters");
                        return;
                    }
                    // send to the server /api/login
                    this.get_public_key()
                    .then((public_key) => {
                        const encryptor = new JSEncrypt();
                        encryptor.setPublicKey(public_key);
                        const login = {
                            username: this.LOGIN.user,
                            password: this.LOGIN.password,
                        }
                        const ciphertext = encryptor.encrypt(JSON.stringify(login));
                        
                        $.ajax({
                            url: `${this.LOGIN_REQUEST.WEB_ROOT}/${this.LOGIN_REQUEST.LOGIN_API}`,
                            type: this.LOGIN_REQUEST.POST_,
                            data: ciphertext,
                            success: (response) => {
                                window.open(`${this.OPEN_PAGE.WEB_ROOT}/${this.OPEN_PAGE.SENSORA_}`, "_self");
                            },
                            error: (_, __, error) => {
                                div_not_fit.textContent = this.MESSAGE_NOT_FIT.MESSAGE_LOGIN_FAILED;
                                setTimeout(() => {
                                    div_not_fit.textContent = this.MESSAGE_NOT_FIT.MESSAGE_NONE_;
                                }, 3000);
                            }
                        })
                    })
                },
                eye_toggle() {
                    if (!this.EYE_TOGGLE.eye_closed) {
                        this.EYE_TOGGLE.eye_closed = true;
                        // eye icon
                        let div_eye = document.querySelector(".login-eye-icon");
                        div_eye.classList.remove(this.EYE_TOGGLE.CLASS_EYE);
                        div_eye.classList.add(this.EYE_TOGGLE.CLASS_EYE_CLOSED);
                        // for input
                        let input_pwd = document.querySelector(".login-password-enter");
                        input_pwd.type = this.EYE_TOGGLE.TYPE_EYE;
                    } else {
                        this.EYE_TOGGLE.eye_closed = false;
                        // eye icon
                        let div_eye = document.querySelector(".login-eye-icon");
                        div_eye.classList.remove(this.EYE_TOGGLE.CLASS_EYE_CLOSED);
                        div_eye.classList.add(this.EYE_TOGGLE.CLASS_EYE);
                        // for input
                        let input_pwd = document.querySelector(".login-password-enter");
                        input_pwd.type = this.EYE_TOGGLE.TYPE_EYE_CLOSED;
                    }
                },
                open_register() {
                    window.open(`${this.OPEN_PAGE.WEB_ROOT}/${this.OPEN_PAGE.REGISTER_}`);
                },
                open_forget() {
                    window.open(`${this.OPEN_PAGE.WEB_ROOT}/${this.OPEN_PAGE.FORGET_}`);
                },
                get_public_key() {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: this.GET_PUBLIC_KEY.PUBLIC_KEY_URL,
                            type: this.GET_PUBLIC_KEY.GET_,
                            dataType: "text",
                            success: (public_key) => {
                                resolve(public_key);
                            },
                            error: error => {
                                reject(error);
                            }
                        })
                    })
                }
            },
        });
    </script>
</html>