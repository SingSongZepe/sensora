package handler

import (
	// "fmt"
	"fmt"
	"net/http"

	"server/utils"
	"server/value"
)

// GET /api/get_picture_item?xid=?&chid=?&title=?
func GetPictureItemAPIHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodGet {
		http.Error(w, "Method not supported", http.StatusBadRequest)
		return
	}
	utils.Ln("user connect to get sensora reader ...")
	w.Header().Set("Access-Control-Allow-Origin", "*") 
	w.Header().Set("Access-Control-Allow-Methods", "GET")
	w.Header().Set("Access-Control-Allow-Headers", "Origin, X-Requested-With, X-Extra-Header, Content-Type, Accept, Authorization")
	w.Header().Set("Access-Control-Expose-Headers", "Content-Length, Access-Control-Allow-Origin, Access-Control-Allow-Headers, Cache-Control, Content-Language, Content-Type")
	
	// get query parameters
	query_params := r.URL.Query()
	xid := query_params.Get(value.XID_)
	chid := query_params.Get(value.CHID_)
	title := query_params.Get(value.TITLE_)

	http.ServeFile(w, r, fmt.Sprintf("../manga/%s/%s/%s", xid, chid, title))
}