package handler

import (
	"net/http"
	"context"
	"go.mongodb.org/mongo-driver/bson"
	"encoding/json"

	"server/mongo_"
	"server/utils"
	"server/value"
)

// GET /api/get_manga?manga_title=?
func GetMangaAPIHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodGet {
		http.Error(w, "Method not supported", http.StatusBadRequest)
		return
	}
	utils.Ln("user connect to get manga api ...")
	// for CORS
	w.Header().Set("Access-Control-Allow-Origin", "*")
	w.Header().Set("Access-Control-Allow-Methods", "POST, GET, OPTIONS, PUT, DELETE, UPDATE")
	w.Header().Set("Access-Control-Allow-Headers", "Origin, X-Requested-With, X-Extra-Header, Content-Type, Accept, Authorization")
	w.Header().Set("Access-Control-Expose-Headers", "Content-Length, Access-Control-Allow-Origin, Access-Control-Allow-Headers, Cache-Control, Content-Language, Content-Type")
	
	// get manga_title from the URL
	query_params := r.URL.Query()
	manga_title := query_params.Get("manga_title")
	if manga_title == "" {
		utils.Le("manga title cannot be empty")
		http.Error(w, "manga title cannot be empty", http.StatusBadRequest)
		return
	}
	mongoPool := mongo_.GetPool()
	client := mongoPool.GetConnection()
	defer mongoPool.ReleaseConnection(client)

	coll_manga_info := client.Database(value.SENSORA_).Collection(value.MANGA_INFO_)
	filter := bson.D{{Key: value.TITLE_, Value: manga_title}}

	var result bson.M
	err := coll_manga_info.FindOne(context.TODO(), filter).Decode(&result)
	if err != nil {
		utils.Lw("manga not found in database")
		http.Error(w, "manga not found in database", http.StatusOK)
		return
	}
	json_string, err := json.Marshal(result)
	if err != nil {
		utils.Le("error parsing json string")
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	// return data to client
	w.Header().Set("Content-Type", "application/json")
	w.Write(json_string)
}