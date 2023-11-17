// a token object
package object

// token object
type Token struct {
	Uid       int 	 `json:"uid"`
	Time      string `json:"time"`  // 2023-10-25 10:10.05
	Sign      string `json:"sign"`  // SingSongZepe GinkoSora Forever	
}

type Token_ struct {
	Uid      int 	 `json:"uid"`    // 1
	TokenStr string  `json:"token"`  // CALKJFLALFNANEWLFJLE==
}
