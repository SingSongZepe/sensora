from Crypto.PublicKey import RSA
from Crypto.Cipher import PKCS1_v1_5

from utils.bin2base64 import bin2base64

KEY_PATH = 'web/key/login/public_key.pem'
user = 'Hello, world!'

def RSA_encrypt():
    with open(KEY_PATH, 'rb') as pf:
        public_key = RSA.import_key(pf.read())
    
    cipher = PKCS1_v1_5.new(public_key)
    encrypted_data = cipher.encrypt(user.encode())
    base64_data = bin2base64(encrypted_data)

    print(base64_data)

if __name__ == '__main__':
    RSA_encrypt()
