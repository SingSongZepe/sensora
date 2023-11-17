package handler

import (
	"context"
	"encoding/json"
	"net/http"
	"strconv"
	"go.mongodb.org/mongo-driver/bson"
	// "go.mongodb.org/mongo-driver/mongo"
	// "go.mongodb.org/mongo-driver/mongo/options"
	
	"server/mongo_"
	"server/utils"
	"server/value"

)

// /api/get_random_mangas?numbers=?
func GetRandomMangasAPIHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodGet {
		http.Error(w, "Method not supported", http.StatusBadRequest)
		return
	}
	utils.Ln("user connect in, get random mangas...")
	// for CORS
	w.Header().Set("Access-Control-Allow-Origin", "*")
	w.Header().Set("Access-Control-Allow-Methods", "POST, GET, OPTIONS, PUT, DELETE, UPDATE")
	w.Header().Set("Access-Control-Allow-Headers", "Origin, X-Requested-With, X-Extra-Header, Content-Type, Accept, Authorization")
	w.Header().Set("Access-Control-Expose-Headers", "Content-Length, Access-Control-Allow-Origin, Access-Control-Allow-Headers, Cache-Control, Content-Language, Content-Type")
	
	// get the manga info from mongodb
	mongoPool := mongo_.GetPool()
	client := mongoPool.GetConnection()
	defer mongoPool.ReleaseConnection(client)

	coll_manga_info := client.Database(value.SENSORA_).Collection(value.MANGA_INFO_)
	filter := bson.D{{}}

	cursor, err := coll_manga_info.Find(context.TODO(), filter)
	if err != nil {
		utils.Le("error connect to manga database")
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer cursor.Close(context.TODO())

	var mangas_info []interface{}
	for cursor.Next(context.TODO()) {
		var result bson.M
		err := cursor.Decode(&result)
		if err != nil {
			utils.Le("error getting manga information from database")
		}
		mangas_info = append(mangas_info, result)
	}

	// get num from the URL
	query_params := r.URL.Query()
	numbers_str := query_params.Get("numbers")
	if numbers_str == "" {
		utils.Lw("num can't be empty")
		http.Error(w, "error getting number from URL, may be you haven't provide it", http.StatusBadRequest)
		return
	}
	numbers, err := strconv.Atoi(numbers_str)
	if err != nil {
		utils.Le("error getting number from URL")
		http.Error(w, err.Error(), http.StatusBadRequest)
		return
	}
	mangas_info_selected, err := utils.GetRandomObject(mangas_info, numbers)
	if err != nil {
		utils.Le("error getting random mangas")
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	json_string, err := json.Marshal(mangas_info_selected)
	if err != nil {
		utils.Le("error parsing json string")
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	// return data to client
	w.Header().Set("Content-Type", "application/json")
	w.Write(json_string)
}
