import pymongo

MONGODB_URL = 'mongodb://localhost:27017'
SENSORA_ = 'sensora'
REGISTER_VERIFY_ = 'register_verify'

client = pymongo.MongoClient(MONGODB_URL)
db_sensora = client[SENSORA_]
coll_register_verify = db_sensora[REGISTER_VERIFY_]
