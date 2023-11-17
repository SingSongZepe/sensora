package handler

import (
	"encoding/json"
	"io/ioutil"
	"net/http"
	"context"
	"os/exec"
	"go.mongodb.org/mongo-driver/bson"

	"server/mongo_"
	"server/object"
	"server/utils"
	"server/value"
)

// POST /api/send_verify  user in body
// username is the email of the user
// verify
func SendVerifyAPIHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodPost {
		http.Error(w, "Method not supported", http.StatusBadRequest)
		return
	}
	utils.Ln("user connect to send verify ...")
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
		http.Error(w, "User Account Error", http.StatusInternalServerError)
		return
	}
	var username object.Username
	err = json.Unmarshal(output, &username)
	if err != nil {
		http.Error(w, "User verify Error", http.StatusBadRequest)
		return
	}

	// check whether the verify item is exists or not
	mongoPool := mongo_.GetPool()
	client := mongoPool.GetConnection()
	defer mongoPool.ReleaseConnection(client)

	coll_register_verify := client.Database(value.SENSORA_).Collection(value.REGISTER_VERIFY_)
	filter := bson.D{{Key: value.USERNAME_, Value: username.Username}}

	_, err = coll_register_verify.DeleteOne(context.TODO(), filter)
	if err != nil {
		utils.Le("Error deleting verify item")
		http.Error(w, "Error deleting verify item", http.StatusInternalServerError)
		return
	}

	// use python script to send a email to the user and append the verify item to database
	// the python script will send a verify message to the username, and then save the verify item to the database
	cmd := exec.Command(value.PY_, value.PY_VERSION, value.PY_SEND_VERIFY, username.Username) // username is email
	_, err = cmd.Output()
	if err != nil {
		http.Error(w, "send verify failed", http.StatusInternalServerError)
		return
	}
	w.WriteHeader(http.StatusOK)
	w.Write([]byte("send verify successfully"))
}
