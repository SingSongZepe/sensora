package handler

import (
	"net/http"
	"go.mongodb.org/mongo-driver/bson"
	"go.mongodb.org/mongo-driver/bson/primitive"
	"context"
	"encoding/json"

	"server/mongo_"
	"server/utils"
	"server/value"
)

// get all the title of chapter of the certain manga
// GET /api/get_chapter?manga_title=?
func GetChaptersAPIHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodGet {
		http.Error(w, "Method not supported", http.StatusBadRequest)
		return
	}
	utils.Ln("user connect to get chapters ...")
	w.Header().Set("Access-Control-Allow-Origin", "*") 
	w.Header().Set("Access-Control-Allow-Methods", "GET")
	w.Header().Set("Access-Control-Allow-Headers", "Origin, X-Requested-With, X-Extra-Header, Content-Type, Accept, Authorization")
	w.Header().Set("Access-Control-Expose-Headers", "Content-Length, Access-Control-Allow-Origin, Access-Control-Allow-Headers, Cache-Control, Content-Language, Content-Type")

	// get manga_title from the URL
	query_params := r.URL.Query()
	manga_title := query_params.Get(value.MANGA_TITLE_)
	if manga_title == "" {
		utils.Le("manga title and chapter title cannot be empty")
		http.Error(w, "manga title and chapter title cannot be empty", http.StatusBadRequest)
		return
	}

	// get connection
	mongoPool := mongo_.GetPool()
	client := mongoPool.GetConnection()
	defer mongoPool.ReleaseConnection(client)

	// get collection
	coll_manga_info := client.Database(value.SENSORA_).Collection(value.MANGA_INFO_)
	filter := bson.D{{ Key: value.TITLE_, Value: manga_title }}
	
	var result bson.M
	err := coll_manga_info.FindOne(context.TODO(), filter).Decode(&result)
	if err != nil {
		utils.Le("manga not found")
		http.Error(w, "manga not found", http.StatusBadRequest)
		return
	}

	var chapters_title []string
	for _, chapter_info := range result[value.CHAPTERS_INFO_].(primitive.A) {
		chapters_title = append(chapters_title, chapter_info.(bson.M)[value.TITLE_].(string))
	}

	json_str, err := json.Marshal(chapters_title)
	if err != nil {
		utils.Le("error parsing json string")
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	// return data to client
	w.Header().Set("Content-Type", "application/json")
	w.Write(json_str)
}
