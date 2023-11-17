package main

import (
	"server/utils"
	"server/handler"
	"net/http"
)

func main() {
	utils.Ln("Hello Sensora!")
	// ! html handler
		// for reading
	http.HandleFunc("/sensora", handler.SensoraHandler)
	http.HandleFunc("/chapter", handler.ChapterHandler)
	http.HandleFunc("/reader", handler.ReaderHandler)
		//
	http.HandleFunc("/login", handler.LoginHandler)
	http.HandleFunc("/register", handler.RegisterHandler)
	// ! api handler
	http.HandleFunc("/api/get_random_mangas", handler.GetRandomMangasAPIHandler)
	http.HandleFunc("/api/get_manga", handler.GetMangaAPIHandler)
	http.HandleFunc("/api/get_chapters", handler.GetChaptersAPIHandler)
	http.HandleFunc("/api/get_pictures", handler.GetPicturesAPIHandler)
	http.HandleFunc("/api/get_picture_item", handler.GetPictureItemAPIHandler)
		// for logging
	http.HandleFunc("/api/login", handler.LoginAPIHandler)
	http.HandleFunc("/api/register", handler.RegisterAPIHandler)
	http.HandleFunc("/api/get_public_key", handler.GetPublicKeyAPIHandler)
	http.HandleFunc("/api/send_verify", handler.SendVerifyAPIHandler)
		// attach
	http.HandleFunc("/api/get_textual", handler.GetTextualAPIHandler)

	// ! main handler
	http.HandleFunc("/", handler.MainHandler)
	http.ListenAndServe(":8080", nil)
}
