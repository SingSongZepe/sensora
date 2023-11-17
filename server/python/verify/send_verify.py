import sys
import random

from mongo.mongo import *
from value.strings import *
from object.verify import Verify
from smtp import SMTP

if __name__ == '__main__':
    username = sys.argv[1] # get email

    verify_code = str(random.randrange(100000, 1000000))
    body = body_format.format(verify_code)
    
    smtp = SMTP(username, body)
    if smtp.run():
        # then append the verify item to mongodb
        # if append failed, then print False
        # if successful, then print True
        verify = Verify(
            username=username,
            verify_code=verify_code
        )
        if coll_register_verify.insert_one(verify.__dict__):
            print(True)
        else:
            print(False)
    else: # fail to send # print False
        print(False)
