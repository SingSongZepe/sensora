package object

type Register struct {
	Username string `json:"username"`
	Password string `json:"password"`
	VerifyCode string `json:"verify_code"`
}