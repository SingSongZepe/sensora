import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

from value.strings import *

class SMTP:
    def __init__(self, recever, body):
        self.sender_email = SENDER
        self.receiver_email = recever
        self.subject = SUBJECT
        self.body = body

    def run(self) -> bool:
        message = MIMEMultipart()
        message[FROM_] = self.sender_email
        message[TO_] = self.receiver_email
        message[SUBJECT_] = self.subject

        message.attach(MIMEText(self.body, PlAIN_))

        try:
            smtp_server =  SMTP_SERVER
            smtp_port = 25 

            smtp_username = self.sender_email 
            smtp_password = SMTP_PASSWORD

            with smtplib.SMTP(smtp_server, smtp_port) as server:
                server.starttls()
                server.login(smtp_username, smtp_password)
                server.send_message(message)
                return True
        except Exception as e:
            return False

