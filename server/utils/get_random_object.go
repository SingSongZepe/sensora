package utils

import (
	"math/rand"
	"time"
	"errors"
)

func GetRandomObject(objects []interface{}, num int) ([]interface{}, error) {
	rand.Seed(time.Now().UnixNano())
	// if num equals to 
	if num <= 0 {
		return nil, 
		errors.New("num must be greater than zero, for example: 1 2 4...")
	}	
	if num >= len(objects) {
		return objects, nil
	}
	randomObjects := make([]interface{}, 0)
	for i := 0; i < num; i++ {
		randomIndex := rand.Intn(len(objects))
		randomObject := objects[randomIndex]
		randomObjects = append(randomObjects, randomObject)
		// the ... is
		objects = append(objects[:randomIndex], objects[randomIndex+1:]...)
	}
	return randomObjects, nil
}