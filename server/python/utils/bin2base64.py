import base64

def bin2base64(bin):
    return base64.b64encode(bin).decode('utf-8')
