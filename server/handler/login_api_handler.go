package handler

import (
	// "fmt"
	"context"
	"io/ioutil"
	"net/http"
	"time"
	"encoding/json"
	"go.mongodb.org/mongo-driver/bson"
	"go.mongodb.org/mongo-driver/mongo"

	"server/mongo_"
	"server/object"
	"server/utils"
	"server/value"
	// "server/value"
)

// POST /api/login data in body
func LoginAPIHandler(w http.ResponseWriter, r *http.Request) {
	// ! important function
	if r.Method != http.MethodPost {
		http.Error(w, "Method not supported", http.StatusBadRequest)
		return
	}
	utils.Ln("user connect to login api")
	w.Header().Set("Access-Control-Allow-Origin", "*")
	w.Header().Set("Access-Control-Allow-Methods", "POST")
	w.Header().Set("Access-Control-Allow-Headers", "Origin, X-Requested-With, X-Extra-Header, Content-Type, Accept, Authorization")
	w.Header().Set("Access-Control-Expose-Headers", "Content-Length, Access-Control-Allow-Origin, Access-Control-Allow-Headers, Cache-Control, Content-Language, Content-Type")

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
	var user object.User
	err = json.Unmarshal(output, &user)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	// check user in database
	mongoPool := mongo_.GetPool()
	client := mongoPool.GetConnection()
	defer mongoPool.ReleaseConnection(client)

	coll_user := client.Database(value.SENSORA_).Collection(value.USER_)
	filter := bson.D{{Key: value.USERNAME_, Value: user.Username}, {Key: value.PASSWORD_, Value: user.Password}}

	var result object.User_
	err = coll_user.FindOne(context.TODO(), filter).Decode(&result)
	if err == mongo.ErrNoDocuments {
		utils.Le("login failed, username or password is not correct")
		http.Error(w, "login failed, username or password is not correct", http.StatusUnauthorized) // 401
		return
	} else if err != nil { //
		utils.Le("login failed, unknown error")
		http.Error(w, "login failed, unknown error", http.StatusInternalServerError) // 500
		return
	}

	// find token (or generate token, if not found in database) and then set it to client
	coll_token := client.Database(value.SENSORA_).Collection(value.TOKEN_)
	filter = bson.D{{Key: value.UID_, Value: result.Uid}}

	var result_ object.Token_
	err = coll_token.FindOne(context.TODO(), filter).Decode(&result_)
	if err == mongo.ErrNoDocuments { // not found token
		// token need
		// Uid
		result_, err = utils.TokenGenerator(result.Uid)
		if err != nil {
			utils.Le("error generating token")
			http.Error(w, "error generating token", http.StatusInternalServerError)
			return
		}
		// append this token to collection
		token_bson, err := bson.Marshal(result_)
		if err != nil {
			utils.Le("error saving token")
			http.Error(w, "error saving token", http.StatusInternalServerError)
			return
		}
		_, err = coll_token.InsertOne(context.TODO(), token_bson)
		if err != nil {
			utils.Le("error saving token")
			http.Error(w, "error saving token", http.StatusInternalServerError)
			return
		}
	} else if err != nil { // found token
		utils.Ln("token, unknown error")
		http.Error(w, "login failed, unknown error", http.StatusInternalServerError) // 500
		return
	}

	// found token (or generated token)
	// result object.Token_
	cookie := &http.Cookie{
		Name:    value.TOKEN_,
		Value:   result_.TokenStr,
		Expires: time.Now().Add(24 * time.Hour),
		Path:    value.COOKIE_VALID_PATH,
		Domain:  value.COOKIE_DOMAIN,
	}
	http.SetCookie(w, cookie)
	// w.Write([]byte("Login Successfully"))
	http.Redirect(w, r, "/sensora", http.StatusSeeOther)
	// w.Header().Set("Location", value.WEB_ROOT_SERVER + "/sensora")
	// w.WriteHeader(http.StatusFound)
}
