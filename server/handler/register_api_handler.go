package handler

import (
	// "fmt"
	"context"
	"encoding/json"
	"fmt"
	"io/ioutil"
	"net/http"
	"go.mongodb.org/mongo-driver/bson"
	"go.mongodb.org/mongo-driver/mongo"

	"server/mongo_"
	"server/object"
	"server/utils"
	"server/value"
)

// POST /api/login data in body
func RegisterAPIHandler(w http.ResponseWriter, r *http.Request) {
	// ! important function
	if r.Method != http.MethodPost {
		http.Error(w, "Method not supported", http.StatusBadRequest)
		return
	}
	utils.Ln("user connect to register api ...")
	w.Header().Set("Access-Control-Allow-Origin", "*") 
	w.Header().Set("Access-Control-Allow-Methods", "POST")
	w.Header().Set("Access-Control-Allow-Headers", "Origin, X-Requested-With, X-Extra-Header, Content-Type, Accept, Authorization")
	w.Header().Set("Access-Control-Expose-Headers", "Content-Length, Access-Control-Allow-Origin, Access-Control-Allow-Headers, Cache-Control, Content-Language, Content-Type")

	// there must use ioutil.ReadAll() instead of r.Body.Read()
	body, err := ioutil.ReadAll(r.Body)
	if err != nil {
		http.Error(w, "Bad Request", http.StatusBadRequest)
		return
	}

	// process
	output, err := utils.Decrypt(body)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	var register object.Register
	err = json.Unmarshal(output, &register)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	// check whether the user exist?
	// so a email can be only use once
	mongoPool := mongo_.GetPool()
	client := mongoPool.GetConnection()
	defer mongoPool.ReleaseConnection(client)
	
	coll_user := client.Database(value.SENSORA_).Collection(value.USER_)
	filter := bson.D{{Key: value.USER_, Value: register.Username}}

	var user_ object.User_
	err = coll_user.FindOne(context.TODO(), filter).Decode(&user_)
	if err == mongo.ErrNoDocuments { // not found user  // user_ is nil
		// check verify code
		// verify item
		// username
		// verify_code
		coll_register_verify := client.Database(value.SENSORA_).Collection(value.REGISTER_VERIFY_)
		filter := bson.D{{Key: value.USERNAME_, Value: register.Username }, {Key: value.VERIFY_CODE_, Value: register.VerifyCode }}
		
		var verify object.Verify
		err = coll_register_verify.FindOne(context.TODO(), filter).Decode(&verify)
		if err == mongo.ErrNoDocuments { // not found
			utils.Le("verify code not match")
			http.Error(w, "verify code not match", http.StatusUnauthorized)
			return
		} else if err != nil {
			utils.Le("verify code error")
			http.Error(w, "verify code error", http.StatusInternalServerError)
			return
		}
		// verify code found 
		// do register
		uid := utils.UidGenerator() // rand generator from 1000000000 to 2147483647
		user__bson, err := bson.Marshal(object.User_{
			Uid: uid,
			Username: register.Username,
			Password: register.Password,
		})
		if err != nil {
			utils.Le("user data generation failed")
			http.Error(w, "user data generation failed", http.StatusInternalServerError)
			return
		}
		// insert user__bson to database
		_, err = coll_user.InsertOne(context.TODO(), user__bson)
		if err != nil {
			utils.Le("error inserting user")
			http.Error(w, "error inserting user", http.StatusInternalServerError)
			return
		}
		// then delete the token
		filter = bson.D{{Key: value.USERNAME_, Value: register.Username}}
		_, err = coll_register_verify.DeleteOne(context.TODO(), filter)
		if err != nil {
			utils.Le(fmt.Sprintf("error deleting token: %s", register.Username))
			// TODO don't return
			// it is okay, the owner can clean whenever
		}
		
		w.WriteHeader(http.StatusOK)
		w.Write([]byte("Register successfully"))
		return

	} else if err != nil {
		utils.Le("user find error")
		http.Error(w, "user find error", http.StatusInternalServerError)
		return
	}
	utils.Le("user found, cannot register user")
	http.Error(w, "user found, cannot register user", http.StatusInternalServerError)
}