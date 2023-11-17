## main function
import json

from xmanhua import XMANHUA

if __name__ == '__main__':
    with open('file/mangalist.sszp', 'r', encoding='utf-8') as pf:
        mangalist = json.loads(pf.read())
    xmanhua = XMANHUA(mangalist)
    xmanhua.run()
