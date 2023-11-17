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