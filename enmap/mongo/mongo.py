import pymongo

from value.strings import *

client = pymongo.MongoClient(MONGODB_URL)
db_sensora = client[SENSORA_]
coll_dd_mangalist = db_sensora[DD_MANGALIST_]
coll_manga_info  = db_sensora[MANGA_INFO_]
coll_manga_map = db_sensora[MANGA_MAP_]

