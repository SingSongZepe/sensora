# decrypt
import sys
from cryptography.fernet import Fernet
import base64
import json

AES_USER_TOKEN_PATH = './key/AES_USER_TOKEN.key'
UID_ = 'uid'
UID_ = 'uid'
TIME_ = 'time'
time_format6 = '{} {}-{} {}:{}.{}' # 2003 11-10 11:10.09
SIGN_ = 'sign'
SIGN = 'SingSongZepe GinkoSora Forever'

# object.Token
# uid
# time
# sign

def load_fernet() -> Fernet:
    return Fernet(open(AES_USER_TOKEN_PATH, 'rb').read())

def token_decrypt(token_str: str, fernet: Fernet):
    return fernet.decrypt(base64.b64decode(token_str.encode())).decode()

# print true or false
if __name__ == '__main__':
    token_str = sys.argv[1]
    token = json.loads(token_decrypt(token_str, load_fernet()))
    if token[SIGN_] != SIGN:
        print(False)
    else: 
        print(True)
    print(token[UID_])
    