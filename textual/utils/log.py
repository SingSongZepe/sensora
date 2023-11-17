import typing

from value.strings import sslog

def ln(msg: typing.Optional[str] = None):
    print(sslog + '[NOR] ' + msg)

def lw(msg: typing.Optional[str] = None):
    print(sslog + '[WAR] ' + msg)

def le(msg: typing.Optional[str] = None):
    print(sslog + '[ERR] ' + msg)
