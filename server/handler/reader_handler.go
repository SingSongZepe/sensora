package handler

import (
	"net/http"
	"os/exec"

	"server/utils"
	"server/value"
)

// GET /api/reader?manga_title=?&chapter_title=?
func ReaderHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodGet {
		http.Error(w, "Method not supported", http.StatusBadRequest)
		return
	}
	utils.Ln("user connect to get sensora reader ...")
	w.Header().Set("Access-Control-Allow-Origin", "*") 
	w.Header().Set("Access-Control-Allow-Methods", "GET")
	w.Header().Set("Access-Control-Allow-Headers", "Origin, X-Requested-With, X-Extra-Header, Content-Type, Accept, Authorization")
	w.Header().Set("Access-Control-Expose-Headers", "Content-Length, Access-Control-Allow-Origin, Access-Control-Allow-Headers, Cache-Control, Content-Language, Content-Type")
	
	// get manga_title from the URL
	query_params := r.URL.Query()
	manga_title := query_params.Get(value.MANGA_TITLE_)
	chapter_title := query_params.Get(value.CHAPTER_TITLE_)
	if manga_title == "" || chapter_title == "" {
		utils.Le("manga title and chapter title cannot be empty")
		http.Error(w, "manga title and chapter title cannot be empty", http.StatusBadRequest)
		return
	}
	cmd := exec.Command(value.PHP_, value.PHP_READER_PATH, manga_title, chapter_title, value.WEB_ROOT_SERVER)
	output, err := cmd.Output()
	if err != nil {
		utils.Le("PHP script output failed")
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	// send to client
	w.Header().Set("Content-Type", "text/html; charset=utf-8")
	w.Write(output)
}