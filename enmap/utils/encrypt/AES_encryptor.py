from cryptography.fernet import Fernet
import base64
import os

from value.strings import *

def load_fernet() -> Fernet:
    return Fernet(open(AES_PICTURE_KEY_PATH, 'rb').read())

def encrypt_file_name(file_name, fernet: Fernet):
    return base64.b64encode(fernet.encrypt(file_name.encode())).decode()
