# this function help to generate two keys and place it in directory /web/key
# for login use RSA to encrypt the user string and password
from cryptography.hazmat.primitives import serialization
from cryptography.hazmat.primitives.asymmetric import rsa
from os.path import join as j
import os

from value.strings import *

if __name__ == '__main__':
    if (os.path.exists(j(KEY_PATH, PRIVATE_KEY_)) and os.path.exists(j(KEY_PATH, PUBLIC_KEY_))):
        print('SingSongLog: [WAR] key files all already exists')
        exit(0)

    private_key = rsa.generate_private_key(
        public_exponent=65537,
        key_size=1048,
    )
    public_key = private_key.public_key()

    pem = private_key.private_bytes(
        encoding=serialization.Encoding.PEM,
        format=serialization.PrivateFormat.PKCS8,
        encryption_algorithm=serialization.NoEncryption()
    )
    with open(j(KEY_PATH, PRIVATE_KEY_), 'wb') as pf:
        pf.write(pem)
    
    pem = public_key.public_bytes(
        encoding=serialization.Encoding.PEM,
        format=serialization.PublicFormat.SubjectPublicKeyInfo,
    )
    with open(j(KEY_PATH, PUBLIC_KEY_), 'wb') as pf:
        pf.write(pem)
