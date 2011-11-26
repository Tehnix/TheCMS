#!/usr/bin/env python
import cgi
import cgitb; cgitb.enable() # cgitb.enable(display=0, logdir="/cgi-bin/python_errors.txt")

from backup import *

# Get form values
form = cgi.FieldStorage()
backupType = form.getvalue("backupType", False)
runValue = form.getvalue("run", False)

backupscript = BackupScript()

# Send appropriate respond
if backupType == "server":
    print "Content-type: text/html\n"
    # Check if already backed up today
    if backupscript.check_backups("_backup/") == 1:
        times_backed_up = "once"
    else:
        times_backed_up = "more than once"
    if runValue == "check":
        if backupscript.backedUpToday is False:
            print """runBackupScript"""
        else:
            print """The website has already been backed up %s today. Continue?
                     <br>
                     <input class="new_backup_btn" type="button" value="Yes">
                     <input class="reset_backup_btn" type="button" value="No"> 
                  """ % (times_backed_up)
    elif runValue == "runBackupScript":
        backupscript.backup_server_files()
        print """The website has been backed up"""
elif backupType == "database":
    # Check if already backed up today
    if backupscript.check_backups("_backup/database/") == 1:
        times_backed_up = "once"
    else:
        times_backed_up = "more than once"
    if runValue == "check":
        if backupscript.backedUpToday is False:
            print "Content-type: text/html\n"
            print """runBackupScript"""
        else:
            print "Content-type: text/html\n"
            print """The website has already been backed up %s today. Continue?
                     <br>
                     <input class="new_backup_btn" type="button" value="Yes">
                     <input class="reset_backup_btn" type="button" value="No"> 
                  """ % (times_backed_up)
    elif runValue == "runBackupScript":
        print backupscript.backup_database_files()


