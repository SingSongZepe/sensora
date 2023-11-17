package handler

import (
	// "fmt"
	"net/http"

	"server/utils"
	"server/value"
)

// GET /api/get_public_key 
func GetPublicKeyAPIHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodGet {
		http.Error(w, "Method not supported", http.StatusBadRequest)
		return
	}
	utils.Ln("user connect to get public_key ...")
	w.Header().Set("Access-Control-Allow-Origin", "*") 
	w.Header().Set("Access-Control-Allow-Methods", "POST, GET, OPTIONS, PUT, DELETE, UPDATE")
	w.Header().Set("Access-Control-Allow-Headers", "Origin, X-Requested-With, X-Extra-Header, Content-Type, Accept, Authorization")
	w.Header().Set("Access-Control-Expose-Headers", "Content-Length, Access-Control-Allow-Origin, Access-Control-Allow-Headers, Cache-Control, Content-Language, Content-Type")
	
	//
	data, err := utils.ReadFile(value.PUBLIC_KEY_PATH)
	if err != nil {
		utils.Le("Error reading public key file")
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	// send to client
    // w.Header().Set("Content-Disposition", "attachment; filename=public_key.pem")
	w.Header().Set("Content-Type", "text/plain")
	w.Write(data)
}