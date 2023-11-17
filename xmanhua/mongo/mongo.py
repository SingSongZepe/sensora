import pymongo

from value.strings import *

client = pymongo.MongoClient(MONGODB_URL)
db_sensora = client[SENSORA_]
coll_dd_mangalist = db_sensora[DD_MANGALIST_]
coll_undd_picture = db_sensora[UNDD_PICTURE_]
