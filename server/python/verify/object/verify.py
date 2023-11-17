from dataclasses import dataclass

@dataclass
class Verify:
    username: str
    verify_code: str
    