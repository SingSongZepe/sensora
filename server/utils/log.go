package utils

import (
	"fmt"
	"server/value"
)

func Ln(msg string) {
	fmt.Println(value.Sslog + value.NOR_ + msg)
}
func Lw(msg string) {
	fmt.Println(value.Sslog + value.WAR_ + msg)
}
func Le(msg string) {
	fmt.Println(value.Sslog + value.ERR_ + msg)
}
