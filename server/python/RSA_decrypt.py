from Crypto.PublicKey import RSA
from Crypto.Cipher import PKCS1_v1_5

from utils.base642bin import base642bin

KEY_PATH = 'web/key/login/private_key.pem'
string_base64 = 'JFPL5ARMNJiVdGIoIY+638zhF5q8HZvyfl+EkaYggoiJ/ETzcfQSt/iSD/n96TwATVd7qBoUARup80jf4C1aGkzVDCIaVY2x+VFsehiwFNx7LBlrDLFfRfUqwcQx0o6SH5uDxokNaXrDcm6zGw8BLpg6FX1pps6TCRmDjwTu7tgn+y0='

def RSA_decrypt():
    bin_data = base642bin(string_base64)

    with open(KEY_PATH, 'rb') as pf:
        private_key = RSA.import_key(pf.read())
    
    cipher = PKCS1_v1_5.new(private_key)
    decrypted_data = cipher.decrypt(bin_data, None)
    decrypted_string = decrypted_data.decode('utf-8')

    print(decrypted_string)

if __name__ == '__main__':
    RSA_decrypt()
