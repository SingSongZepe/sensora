package handler

import (
	"context"
	"net/http"
	"go.mongodb.org/mongo-driver/bson"
	"encoding/json"

	"server/mongo_"
	"server/object"
	"server/utils"
	"server/value"
)

// GET /api/get_textual
func GetTextualAPIHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodGet {
		http.Error(w, "Method not supported", http.StatusBadRequest)
		return
	}
	utils.Ln("user connect to get textual ...")

	// get textual from server
	mongoPool := mongo_.GetPool()
	client := mongoPool.GetConnection()
	defer mongoPool.ReleaseConnection(client)

	coll_textual := client.Database(value.SENSORA_).Collection(value.TEXTUAL_)
	filter := bson.D{{}}

	cursor, err := coll_textual.Find(context.TODO(), filter)
	if err != nil {
		utils.Le("no textual found in database")
		http.Error(w, "no textual found in database", http.StatusInternalServerError)
		return
	}
	
	var textuals []object.Textual
	if err := cursor.All(context.TODO(), &textuals); err != nil {
		utils.Le("textual parsing failed")
		http.Error(w, "textual parsing failed", http.StatusInternalServerError)
		return
	}
	randint, err := utils.GetRandomIndex(len(textuals))
	if err != nil {
		utils.Le(err.Error())
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	textual := textuals[randint]
	json_str, err := json.Marshal(textual)
	if err != nil {
		utils.Le("textual parsing failed")
		http.Error(w, "textual parsing failed", http.StatusInternalServerError)
		return
	}
	// send to client
	w.Header().Set("Content-Type", "application/json")
	w.Write(json_str)
}