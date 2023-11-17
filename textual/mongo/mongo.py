import pymongo

from value.strings import *

client = pymongo.MongoClient(MONGODB_URL)
db_sensora = client[SENSORA_]
coll_textual = db_sensora[TEXTUAL_]
