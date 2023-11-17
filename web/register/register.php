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
        <title>SingSongZepe Register</title>
        <script src="../res/js/jquery-3.7.0.min.js"></script>
        <script src="../res/js/vue.min.js"></script>
        <script src="../res/js/jsencrypt.min.js"></script>
        <link rel="stylesheet" href="../res/css/font.css" type="text/css">
        <link rel="stylesheet" href="../res/css/decorator.css" type="text/css">
        <link rel="stylesheet" href="../res/css/color.css" type="text/css">
        <link rel="stylesheet" href="../res/css/pic.css" type="text/css">
        <link rel="stylesheet" href="../register/css/register.css" type="text/css">
        <link rel="icon" href="../res/img/reSSZp.png">
    </head>
    <!-- there are many login in the file, because copy from login.html, I don't want to change them, it costs a lot of time for meaningless thing -->
    <body class="bg-pic-3 bg-pic-setting">
        <div id="app" class="login">
            <form class="login-form">
                <h1 class="login-title font-poppins fg-lcolor-sider">Register to SingSongZepe Sensora !</h1>
                <div class="login-content">
                    <div class="login-box">
                        <div class="login-user">
                            <div class="login-user-icon bg-pic-user"></div>
                            <input type="email" class="login-user-enter font-poppins bg-color-tran" placeholder="email" v-model="REGISTER.user"></input>
                        </div>
                    </div>
                    <div class="login-box">
                        <div class="login-password">
                            <div class="login-password-icon bg-pic-password"></div>
                            <input type="password" class="login-password-enter font-poppins bg-color-tran" placeholder="password" v-model="REGISTER.password"></input>
                        </div>
                    </div>
                    <div class="login-box">
                        <div class="login-password">
                            <div class="login-password-icon bg-pic-password"></div>
                            <input type="password" class="login-password-enter font-poppins bg-color-tran" placeholder="password again" v-model="REGISTER.password_confirm"></input>
                        </div>
                    </div>
                    <div class="login-box">
                        <div class="login-verify">
                            <div class="login-verify-icon bg-pic-verify"></div>
                            <input type="text" class="login-verify-enter font-poppins bg-color-tran" maxlength="6" placeholder="verify code" v-model="REGISTER.verify_code"></input>
                            <button type="button" class="login-send-verify bg-color-tran2 font-poppins" @click="send_verify()">{{SEND_VERIFY_REQUEST.SEND_VERIFY_}}</button> 
                        </div>
                    </div>
                </div>
                <div class="login-button-ctan">
                    <button type="button" class="login-button font-poppins" @click="register()">Register</button>
                </div>
            </form>
            <div class="login-message"></div>
        </div>
    </body>
    <!-- <script src="../register/js/register.js"></script> -->
    <script>
        let vue = new Vue({
            el: "#app",
            data() {
                return {
                    REGISTER: {
                        user: "",
                        password: "",
                        password_confirm: "",
                        verify_code: "",
                    },
                    MESSAGE_NOT_MATCH: {
                        MESSAGE_NO_LESS_THEN_: "password cannot less than 6 characters",
                        MESSAGE_NOT_MATCH_: "two passwords are not the same",
                        MESSAGE_VERIFY_CODE_SENT: "verify code already send to your email",
                        MESSAGE_VERIFY_CODE_SENT_FAILED: "verify code send failed please retest",
                        MESSAGE_NONE_: ""
                    },
                    GET_PUBLIC_KEY: {
                        PUBLIC_KEY_URL: "<?php echo $argv[1]?>/api/get_public_key",
                        GET_: "GET",
                    },
                    REGISTER_REQUEST: {
                        WEB_ROOT: "",
                        REGISTER_API: "api/register",
                        POST_: "POST",
                    },
                    SEND_VERIFY_REQUEST: {
                        WEB_ROOT: "<?php echo $argv[1]?>",
                        SEND_VERIFY_API: "api/send_verify",
                        POST_: "POST",
                        SEND_VERIFY_: "Send Verify",
                    },
                    OPEN_PAGE: {
                        WEB_ROOT: "<?php echo $argv[1]?>",
                        LOGIN_: "login"
                    }
                }
            },
            created() {
            },
            methods: {
                register() {
                    // encrypt and send to the server /api/register
                    let div_not_match = document.querySelector(".login-message");
                    
                    // check whether the string are valid
                    if (this.REGISTER.password.length < 6) {
                        div_not_match.textContent = this.MESSAGE_NOT_MATCH.MESSAGE_NO_LESS_THEN_;
                        setTimeout(() => {
                            div_not_match.textContent = this.MESSAGE_NOT_MATCH.MESSAGE_NONE_;
                        }, 3000);
                        console.log("error password no less than 6 characters");
                        return;
                    } else if (this.REGISTER.password != this.REGISTER.password_confirm) {
                        div_not_match.textContent = this.MESSAGE_NOT_MATCH.MESSAGE_NOT_MATCH_;
                        setTimeout(() => {
                            div_not_match.textContent = this.MESSAGE_NOT_MATCH.MESSAGE_NONE_;
                        }, 3000);
                        console.log("error password not match");
                        return;
                    } 
                    
                    // encrypted those data and use base64 encoding to get the encrypted data
                    // send the encrypted data to the server /api/register
                    this.get_public_key()
                    .then((public_key) => {
                        const encryptor = new JSEncrypt();
                        encryptor.setPublicKey(public_key);
                        const register = {
                            username: this.REGISTER.user,
                            password: this.REGISTER.password,
                            verify_code: this.REGISTER.verify_code,
                        }
                        const ciphertext = encryptor.encrypt(JSON.stringify(register));
                        
                        $.ajax({
                            url: `${this.REGISTER_REQUEST.WEB_ROOT}/${this.REGISTER_REQUEST.REGISTER_API}`,
                            type: this.REGISTER_REQUEST.POST_,
                            data: ciphertext,
                            success: (response) => {
                                window.open(`${this.WEB_ROOT}/${this.LOGIN_}`, "_self");
                            },
                            error: (_, __, error) => {
                                console.log("Request failed: " + status);
                            }
                        })
                    })
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
                            error: (_, __, error) => {
                                reject(error);
                            }
                        })
                    })
                },
                send_verify() { // request the server to send verify code to the username (email)
                    this.disable_button();
                    let div_not_match = document.querySelector(".login-message");

                    this.get_public_key()
                    .then((public_key) => {
                        const encryptor = new JSEncrypt();
                        encryptor.setPublicKey(public_key);
                        const username = {
                            username: this.REGISTER.user,
                        }
                        const ciphertext = encryptor.encrypt(JSON.stringify(username));
                        
                        $.ajax({
                            url: `${this.SEND_VERIFY_REQUEST.WEB_ROOT}/${this.SEND_VERIFY_REQUEST.SEND_VERIFY_API}`,
                            type: this.SEND_VERIFY_REQUEST.POST_,
                            data: ciphertext,
                            success: (response) => {
                                div_not_match.textContent = this.MESSAGE_NOT_MATCH.MESSAGE_VERIFY_CODE_SENT;
                                setTimeout(() => {
                                    div_not_match.textContent = this.MESSAGE_NOT_MATCH.MESSAGE_NONE_;
                                }, 3000);
                            },
                            error: (_, __, error) => {
                                div_not_match.textContent = this.MESSAGE_NOT_MATCH.MESSAGE_VERIFY_CODE_SENT_FAILED;
                                setTimeout(() => {
                                    div_not_match.textContent = this.MESSAGE_NOT_MATCH.MESSAGE_NONE_;
                                }, 3000);
                            }
                        })
                    })
                },
                disable_button() {
                    var button = document.querySelector(".login-send-verify");
                    button.disabled = true; 
                    var remaining_time = 45; 
                    button.innerHTML = remaining_time + "s";
                    var countdown = setInterval(function() {
                        remaining_time--;
                        button.innerHTML = remaining_time + "s";
                        if (remaining_time <= 0) {
                            clearInterval(countdown);
                            button.disabled = false; // 启用按钮
                            button.innerHTML = this.SEND_VERIFY_REQUEST.SEND_VERIFY_;
                        }
                    }, 1000);
                }
            },
        });
    </script>
</html>