# singsongzepe
singsongzepe = 'SingSongZepe'
sszp = 'sszp' # use as decorator

# sslog
sslog = 'SingSongLog: '

# mongodb
MONGODB_URL = 'mongodb://localhost:27017/'
    # db
SENSORA_ = 'sensora'
    # coll
DD_MANGALIST_ = 'dd_mangalist'
UNDD_PICTURE_ = 'undd_picture'

TITLE_ = 'title'
XID_ = 'xid'
AUTHOR_ = 'author'
STATUS_ = 'status'
COVER_ = 'cover'
TYPE_ = 'type'
CHAPTER_NUMS_ = 'chapter_nums'
CHAPTERS_ = 'chapters'
CHID_ = 'chid'
CHIDS_ = 'chids'
INDEX_ = 'index'
PAGES_ = 'pages'
DD_CHIDS_ = 'dd_chids'
DESCRIPTION_ = 'description'
    # update set
sSET_ = '$set'

# for downloading
d_chapter_thread_num = 5
d_picture_thread_num = 5
xmanhua_root_url = 'https://xmanhua.com/'
chapter_image = 'chapterimage.ashx'
dp = '../td'

User_Agent_ = 'User-Agent'
Referer_ = 'Referer'
Connection_ = 'Connection'
Cookie_ = 'Cookie'
Host_ = 'Host'
User_Agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36 Edg/112.0.1722.68'
Referer = 'https://xmanhua.com/'
Connection = 'keep-alive'
Cookie = "MANGABZ_MACHINEKEY=d7f5a021-33de-4fef-b08f-d4f60316bec4; mangabzcookieenabletest=1; _ga=GA1.1.342123945.1697067486; xmanhua_lang=1; _ga_RV4ME3C1XE=GS1.1.1697125110.6.1.1697127113.0.0.0; firsturl=https%3A%2F%2Fxmanhua.com%2Fm11184%2F; mangabzimgcooke=271588%7C2%2C10344%7C2%2C11184%7C16; image_time_cookie=271588|638327527137086157|2,11184|638327542520044879|2; mangabzimgpage=271588|1:1,10344|1:1,11184|1:1; ComicHistoryitem_zh=History=19224,638199544756985753,167697,-1,1,0,2,1|31224,638199557615220446,281722,6,1,0,2,1|10913,638199557697010811,130113,5,1,0,2,1|73,638327527135324367,271588,1,1,0,2,592|207,638202156070253469,26094,1,1,0,2,3|29139,638203681663400176,261009,1,1,0,2,18|28764,638203704238854163,235842,6,1,0,2,1|530,638203704875941319,170509,6,1,0,2,98|724,638203705088180326,290864,1,1,0,2,65|5783,638203706499952437,289973,5,1,0,2,41|1337,638203707348395402,106323,4,1,0,2,30|708,638203707777110807,153749,8,1,0,2,49|1081,638204387201227654,179198,3,1,0,2,45|762,638205605973257680,291984,4,1,0,2,193|27147,638205607011449104,289811,4,1,0,2,17|13260,638205628799899601,140763,8,1,0,2,1|11514,638205631267522268,134105,4,1,0,2,1|293,638206157749517604,22925,3,1,0,2,1|134,638217683968498967,13252,14,1,0,2,68|87,638327542520004848,11184,1,1,0,2,61&ViewType=1&OrderBy=2; readhistory_time=1-87-11184-1"
Host = Referer

verify = True

# status
status_serialization = '連載中'
status_finished = '已完結'

# pattern
PATTERN_XMANHUA_COUNT = 'XMANHUA_IMAGE_COUNT\s?=\s?(\d+)'
PATTERN_XMANHUA_CID = 'XMANHUA_CID\s?=\s?(\d+)'
PATTERN_XMANHUA_MID = 'XMANHUA_MID\s?=\s?(\d+)'
PATTERN_XMANHUA_VIEWSIGN_DT = 'mXMANHUA_VIEWSIGN_DT\s?=\s?(\d+)'
PATTERN_XMANHUA_VIEWSIGN = 'mXMANHUA_VIEWSIGN\s?=\s?(\d+)'

# for construction of params
CID_ = 'cid'
MID_ = 'mid'
_DT_ = '_dt'
_SIGN_ = '_sign'
_CID_ = '_cid'
KEY_ = 'key' 
PAGE_ = 'page'

# ashx_headers
ashx_headers = {
    User_Agent_: User_Agent,
    Referer_: Referer,
    Connection_: Connection,
}
# download
picture_headers = {
    User_Agent_: User_Agent,
    Referer_: Referer,
    Cookie_: Cookie,
    Connection_: Connection,
}

