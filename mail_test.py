#!/usr/bin/env python3
smtp_server = "smtp.gmail.com"
port = 465  # For starttls
sender_email = "wisptech@gmail.com"
receiver_email = "chris@totalhighspeed.net"
password = 'Stick3Whistle'
message = """Subject: TEST FROM AlarmPiHat\r\nThis message is a test sent from Python."""
# Create a secure SSL context
context = ssl.create_default_context()
with smtplib.SMTP_SSL(smtp_server, port, context=context) as server:
    server.login(sender_email, password)
    server.sendmail(sender_email, receiver_email, message)
