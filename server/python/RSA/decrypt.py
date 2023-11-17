import sys
from Crypto.PublicKey import RSA
from Crypto.Cipher import PKCS1_v1_5

from utils.base642bin import base642bin

PRIVATE_KEY_PATH = './key/private_key.pem'

if __name__ == '__main__':
    bin_data = base642bin(sys.argv[1])

    with open(PRIVATE_KEY_PATH, 'rb') as pf:
        private_key = RSA.import_key(pf.read())
    
    cipher = PKCS1_v1_5.new(private_key)
    decrypted_data = cipher.decrypt(bin_data, None)
    print(decrypted_data.decode('utf-8'), end='')

