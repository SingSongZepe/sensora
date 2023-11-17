package handler

import (
	"net/http"
	"server/value"
)

// main handler
func MainHandler(w http.ResponseWriter, r *http.Request) {
	fs := http.FileServer(http.Dir(value.WEB_ROOT))
	// return the value
	fs.ServeHTTP(w, r)
}	
