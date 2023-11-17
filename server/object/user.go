package object

// this object is for login, that only pass username and password
type User struct {
	// uid there not being, it generates by server
	// and the User object is just from the client
	Username string  `json:"username"`
	Password string  `json:"password"`
}

// this object is for database, uid username and password
type User_ struct {
	Uid      int     `json:"uid"`
	Username string  `json:"username"`
	Password string  `json:"password"`
}