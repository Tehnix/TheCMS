#!/usr/bin/env python
import cgi
import cgitb; cgitb.enable() # for troubleshooting. cgitb.enable(display=0, logdir="/cgi-bin/python_errors")
import time
import os
try:
	import MySQLdb
	python_mysql = True
except ImportError:
	python_mysql = False

# Declare content type (header)
print "Content-type: text/html"
print 

form = cgi.FieldStorage()
msg = form.getvalue("test", "Nothing :( !")
#ad as
print """
<html>

<head>
    <title>CGI test...</title>
</head>

<body>
    <h1>Testing the shit out of CGI !</h1>
    hello world in python!
    <br>
"""
print os.path.abspath(os.getcwd())
print "<br>"
print os.path.dirname(os.getcwd())
print "<br>"
print time.strftime("%Y-%m-%d-%H:%M.backup.zip")
test_list = ["hey!", "world!", "Lists", "work", "in", "cgi !!!", "....", "Awesome", ":D"]
for item in test_list:
    print item + " "
    
try:
    print """
    <br>
    from : %s
    <br>
    value : %s
    <br>
    """ % ("test", cgi.escape(msg))
    for name in form:
        print """
        Input : %s Value : %s
        """ % (name, form[name].value)
except KeyError:
    pass


print """
</body>

</html>
"""
