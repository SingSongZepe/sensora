package handler

import (
	"net/http"
	"server/utils"
	"os/exec"

	"server/value"
)

// GET /login
func LoginHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodGet {
		http.Error(w, "Method not supported", http.StatusBadRequest)
		return
	}
	utils.Ln("user connect to login ...")
	cmd := exec.Command(value.PHP_, value.PHP_LOGIN_PATH, value.WEB_ROOT_SERVER)
	output, err := cmd.Output()
	if err != nil {
		utils.Le("PHP script failed")
		http.Error(w, "PHP script failed", http.StatusInternalServerError)
		return
	}
	w.Header().Set("Content-Type", "text/html; charset=utf-8")
	w.Write(output)
}