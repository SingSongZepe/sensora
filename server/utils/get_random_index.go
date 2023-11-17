package utils

import (
	"math/rand"
	"time"
	"errors"
)

func GetRandomIndex(length int) (int, error) {
	if length <= 0 {
		return 0,
		errors.New("length must be greater than zero")
	}
	rand.Seed(time.Now().UnixNano())
	return rand.Intn(length), nil
}