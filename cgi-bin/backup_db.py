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

# Get form values
form = cgi.FieldStorage()
run_value = form.getvalue("run", "no value set")

# Check if there already is a backup for this day
i = 0;
times_backed_up = ""
backed_up_today = False
look_for = str(time.strftime("%Y-%m-%d"))
target_dir = '../_backup/database/'
for base, dirs, files in os.walk(target_dir):
	for file in files:
		if look_for in file:
			i = i + 1
			backed_up_today = True
# Construct sentence based on number on times already backed up
if i == 1:
	times_backed_up = "once"
else:
	times_backed_up = "more than once"


if backed_up_today and run_value == "no":
	# Declare content type (header)
	print "Content-type: text/html"
	print 
	
	print """The database has already been backed up %s today, are you sure you want to continue?
			<br>
			<input class="new_backup_btn" type="button" value="Yes"> <input class="reset_backup_btn" type="button" value="No"> 
			""" % (times_backed_up)
elif backed_up_today is False or run_value == "yes":
	if python_mysql:
		# Declare content type (header)
		print "Content-type: text/html"
		print 
		
		print """The database has been backed up successfully !"""
	else:
		print "Location: ../manage.php?action=backup_db&output=print"
		print 
elif run_value == "reset" or run_value == "no value set":
	# Declare content type (header)
	print "Content-type: text/html"
	print 
	
	print """<input class="old_backup_btn" type="button" value="Backup database !">"""