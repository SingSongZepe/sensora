from cryptography.fernet import Fernet

AES_PICTURE_KEY_PATH = 'key/AES_USER_TOKEN.key'

def generate_key():
    key = Fernet.generate_key()
    with open(AES_PICTURE_KEY_PATH, 'wb') as key_file:
        key_file.write(key)

if __name__ == '__main__':
    generate_key()
