import threading

class sszpThread:
    def __init__(self, func):
        self.thread = threading.Thread(target=func)
    def start(self):
        self.thread.start()
    def join(self):
        self.thread.join()