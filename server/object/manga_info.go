package object

// json decoder
type MangaInfo struct {
	Title        string `json:"title"`
	Xid			 string `json:"xid"`
	Author 		 string `json:"author"`
	Status 		 string `json:"status"`
	Cover        string `json:"cover"`
	Type         []string `json:"type"`
	Chapter_nums int    `json:"chapter_nums"`
	Description  string `json:"description"`
}