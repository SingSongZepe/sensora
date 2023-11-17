import re

def retitle(title) -> str:
    illegal_char = r'[<>:"/\\|?,()+%!@#$^&*=;\n ]'
    return re.sub(illegal_char, '', title)
