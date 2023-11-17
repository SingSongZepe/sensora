# token generator
import sys
from cryptography.fernet import Fernet
import base64
import json
from datetime import datetime

AES_USER_TOKEN_PATH = './key/AES_USER_TOKEN.key'
UID_ = 'uid'
TIME_ = 'time'
time_format6 = '{} {}-{} {}:{}.{}' # 2003 11-10 11:10.09
SIGN_ = 'sign'
SIGN = 'SingSongZepe GinkoSora Forever'

def load_fernet() -> Fernet:
    return Fernet(open(AES_USER_TOKEN_PATH, 'rb').read())

def token_generate(token_str: str, fernet: Fernet):
    return base64.b64encode(fernet.encrypt(token_str.encode())).decode()

if __name__ == '__main__':
    uid = sys.argv[1]
    now = datetime.now()
    token_str = json.dumps({
        UID_: uid,
        TIME_: time_format6.format(now.year, now.month, now.day, now.hour, now.minute, now.second),
        SIGN_: SIGN, # SingSongZepe GinkoSora Forever
    })
    # there you can't append /r/n to the tail of string, invalid bytes in token
    print(token_generate(token_str, load_fernet()), end='')
