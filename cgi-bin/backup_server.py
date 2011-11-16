#!/usr/bin/env python
import cgi
import cgitb; cgitb.enable() # for troubleshooting. cgitb.enable(display=0, logdir="/cgi-bin/python_errors")
import zipfile
import os
import time

# Declare content type (header)
print "Content-type: text/html"
print 

def backupServerFiles():
    """Backup all files in folder ../, except for the actual backup folder !"""
    backup_filename =  os.path.dirname(os.getcwd()) + "/_backup/" + time.strftime("%Y-%m-%d.%H-%M.backup.zip")
    target_dir = '../'
    zip = zipfile.ZipFile(backup_filename, 'w', zipfile.ZIP_DEFLATED)
    rootlen = len(target_dir) - 1
    for base, dirs, files in os.walk(target_dir):
        for file in files:
            fn = os.path.join(base, file)
            if base[rootlen:] != "/_backup":
                zip.write(fn, fn[rootlen:])

# Get form values
form = cgi.FieldStorage()
run_value = form.getvalue("run", "no value set")

# Check if there already is a backup for this day
i = 0;
times_backed_up = ""
backed_up_today = False
look_for = str(time.strftime("%Y-%m-%d"))
target_dir = os.path.dirname(os.getcwd()) + "/_backup/"
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
    print """The website has already been backed up %s today, are you sure you want to continue?
            <br>
            <input class="new_backup_btn" type="button" value="Yes"> <input class="reset_backup_btn" type="button" value="No"> 
            """ % (times_backed_up)
elif backed_up_today is False or run_value == "yes":
    backupServerFiles()
    print """The website has been backed up successfully !"""
elif run_value == "reset" or run_value == "no value set":
    print """<input class="old_backup_btn" type="button" value="Backup website !">"""