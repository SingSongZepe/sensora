package check

import ( 
	"time"
)

func CheckTime(time_format string) bool { // true is valid, false is invalid
	layout := "2006-01-02 15:04:05"
	t, err := time.Parse(layout, time_format)
    if err != nil {
		return false
    } else {
        now := time.Now().UTC()
        if t.Before(now) || t.Equal(now) {
			return false
        } else {
			return true
        }
    }
}