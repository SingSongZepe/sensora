package handler

import (
	// "fmt"
	"context"
	"encoding/json"
	"net/http"
	"go.mongodb.org/mongo-driver/bson"
	"go.mongodb.org/mongo-driver/bson/primitive"

	"server/mongo_"
	"server/utils"
	"server/value"
	"server/object"
	"server/check"
)

// GET /api/get_pictures?manga_title=?&chapter_title=?
func GetPicturesAPIHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodGet {
		http.Error(w, "Method not supported", http.StatusBadRequest)
		return
	}
	utils.Ln("user connect to get pictures ...")
	w.Header().Set("Access-Control-Allow-Origin", "*") 
	w.Header().Set("Access-Control-Allow-Methods", "POST, GET, OPTIONS, PUT, DELETE, UPDATE")
	w.Header().Set("Access-Control-Allow-Headers", "Origin, X-Requested-With, X-Extra-Header, Content-Type, Accept, Authorization")
	w.Header().Set("Access-Control-Expose-Headers", "Content-Length, Access-Control-Allow-Origin, Access-Control-Allow-Headers, Cache-Control, Content-Language, Content-Type")
	
	// ! cookie checker version
	// check user (token) info
	token_str, err := r.Cookie(value.TOKEN_)
	if err != nil {
		utils.Ln("No cookie found!")
		http.Error(w, "error method not allowed", http.StatusBadRequest)
		return
	}
	check_token, err := check.CheckToken(token_str.Value)
	if err != nil || !check_token {
		utils.Ln(err.Error())
		http.Error(w, err.Error(), http.StatusBadRequest)
		return
	}

	// get manga_title from the URL
	query_params := r.URL.Query()
	manga_title := query_params.Get(value.MANGA_TITLE_)
	chapter_title := query_params.Get(value.CHAPTER_TITLE_)
	if manga_title == "" || chapter_title == "" {
		utils.Le("manga title and chapter title cannot be empty")
		http.Error(w, "manga title and chapter title cannot be empty", http.StatusBadRequest)
		return
	}

	// get the data info from mongodb
	mongoPool := mongo_.GetPool()
	client := mongoPool.GetConnection()
	defer mongoPool.ReleaseConnection(client)

	coll_manga_map := client.Database(value.SENSORA_).Collection(value.MANGA_MAP_)
	filter := bson.D{{Key: value.TITLE_, Value: manga_title}}

	var result bson.M
	err = coll_manga_map.FindOne(context.TODO(), filter).Decode(&result)
	if err != nil {
		utils.Le("manga not found")
		http.Error(w, "manga not found", http.StatusBadRequest)
		return
	}
	var pictures[]object.PictureItem
	// find chapter_title and append all its pictures to the array
	// 
	for _, chapter_map := range result[value.CHAPTERS_MAP_].(primitive.A) {
		if chapter_map.(bson.M)[value.TITLE_].(string) == chapter_title {
			for _, picture_map := range chapter_map.(bson.M)[value.PICTURES_MAP_].(primitive.A) {
				pictures = append(pictures, object.NewPictureItem(
					result[value.XID_].(string), // xid
					chapter_map.(bson.M)[value.CHID_].(string), // chid
					picture_map.(bson.M)[value.AES_ENCODING_].(string), // title
				))
			}
			break
		}
	}
	
	json_str, err := json.Marshal(pictures)
	if err != nil {
		utils.Le("error parsing json string")
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	// return data to client
	w.Header().Set("Content-Type", "application/json")
	w.Write(json_str)
}

// use method of pipeline to get them
// in Chinese 聚合管道
// pipeline := []bson.M{
// 	{
// 		"$match": bson.M{
// 			"title": "不該扯上關系的女生成了我女友",
// 		},
// 	},
// 	{
// 		"$project": bson.M{
// 			"manga_title": "$title",
// 			"chapter_maps": bson.M{
// 				"$filter": bson.M{
// 					"input": "$chapters_map",
// 					"as": "chapter",
// 					"cond": bson.M{
// 						"$eq": []interface{}{"$$chapter.title", "第1話"},
// 					},
// 				},
// 			},
// 		},
// 	},
// 	{
// 		"$unwind": "$chapter_maps",
// 	},
// 	{
// 		"$project": bson.M{
// 			"_id": 0,
// 			"manga_title": 1,
// 			"chapter_title": "$chapter_maps.title",
// 			"picture_maps": "$chapter_maps.pictures_map",
// 		},
// 	},
// }