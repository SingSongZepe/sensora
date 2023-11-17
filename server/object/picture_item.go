package object

// there must be Xid, not xid in first letter
type PictureItem struct {
	Xid    string
	Chid   string
	Title  string  // actually the name of the picture
}

func NewPictureItem(Xid, Chid, Title string) PictureItem {
	return PictureItem{
		Xid:   Xid,
		Chid:  Chid,
		Title: Title, 
	}
}