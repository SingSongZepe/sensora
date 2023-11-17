# basic object use to store in xmanhua.sszp
from dataclasses import dataclass
import typing

from object.chapter import Chapter

@dataclass
class Downloaded:
    title: str  # 鬼滅之刃 from website
    xid: str    # 73xm
    author: str # 吾峠呼世晴 
    status: str # 已完結 or 連載中
    cover: str  # https://cover.xmanhua.com/1/73/20191206092901_360x480_82.jpg 
    type: typing.List[str]   # 熱血 冒險
    chapter_nums: int
    chapters: typing.List[Chapter]
    chids: typing.List[str]
    dd_chids: typing.List[str]
    description: str  # 時值日本大正時期。 傳說太陽下山后，惡鬼出沒吃人。亦有獵鬼人斬殺惡鬼、保護人們。 賣炭少年·炭治郎，他那平凡而幸福的日常生活，在家人遭到惡鬼襲擊的那一天發生劇變。母親與四個弟妹慘遭殺害，而與他一起生還的妹妹：禰豆子亦異變成兇暴的鬼。 在獵鬼人的指引下，立志成為獵鬼人的炭治郎與變成鬼卻尚存理智的的禰豆子二人踏上了旅程。通過艱苦的劍術修行與賭命試煉，炭治郎成為了鬼獵人組織“鬼殺隊”的一員。 為了讓妹妹禰豆子變回人類，為了討伐殺害家人的惡鬼，為了斬斷悲傷的連鎖，少年與鬼的戰斗不曾停歇 
    

