package value

const (
	// singsongzepe
	Sslog = "SingSongLog: "
	Sszp  = "sszp"
	NOR_  = "[NOR] "
	WAR_  = "[WAR] "
	ERR_  = "[ERR] "

	PHP_       = "php"
	PY_        = "py"
	PY_VERSION = "-3.11"
	// res root
	WEB_ROOT        = "../web"
	WEB_ROOT_SERVER = "http://singsongzepe.top"
	// file relative path
	PHP_CHAPTER_PATH  = "../web/chapter/chapter.php"
	PHP_READER_PATH   = "../web/reader/reader.php"
	PHP_SENSORA_PATH  = "../web/sensora/sensora.php"
	PHP_LOGIN_PATH    = "../web/login/login.php"
	PHP_REGISTER_PATH = "../web/register/register.php"

	PY_DECRYPT_SCRIPT  = "./python/RSA/decrypt.py"
	PY_TOKEN_GENERATOR = "./python/token/generate.py"
	PY_TOKEN_CHECKER   = "./python/token/check_token.py"
	PY_SEND_VERIFY     = "./python/verify/send_verify.py"
	// public key
	PUBLIC_KEY_PATH = "./key/public_key.pem"
	// for query parameters
	MANGA_TITLE_   = "manga_title"
	CHAPTER_TITLE_ = "chapter_title"
	// db
	SENSORA_ = "sensora"
	// coll
	DD_MANGALIST_    = "dd_mangalist"
	MANGA_INFO_      = "manga_info"
	MANGA_MAP_       = "manga_map"
	USER_            = "user"
	TOKEN_           = "token"
	REGISTER_VERIFY_ = "register_verify"
	TEXTUAL_         = "textual"
	// filter
	TITLE_         = "title"
	XID_           = "xid"
	CHID_          = "chid"
	CHAPTERS_MAP_  = "chapters_map"
	CHAPTERS_INFO_ = "chapters_info"
	PICTURES_MAP_  = "pictures_map"
	AES_ENCODING_  = "aes_encoding"
	UID_           = "uid"
	USERNAME_      = "username"
	PASSWORD_      = "password"
	VERIFY_CODE_   = "verify_code"
	// for cookie
	COOKIE_VALID_PATH = "/"
	COOKIE_DOMAIN     = ".localhost" // ! important for CORS
	SIGN              = "SingSongZepe GinkoSora Forever"
)
