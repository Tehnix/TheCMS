#!/usr/bin/env python
import cgi
import cgitb; cgitb.enable() # for troubleshooting. cgitb.enable(display=0, logdir="/cgi-bin/python_errors.txt")
import zipfile
import os
import time
try:
    import MySQLdb
    PYTHON_MYSQL = True
except ImportError:
    PYTHON_MYSQL = False

class BackupScript:
    """Backup server and database, but prompts user if already
    backed up today
    """


    def __init__(self):
        pass
    
    def check_backups(self, location):
        """Check if files have been backed up today"""
        i = 0;
        self.backedUpToday = False
        look_for = str(time.strftime("%Y-%m-%d"))
        target_dir = "%s/%s" % (os.path.dirname(os.getcwd()), location)
        for base, dirs, files in os.walk(target_dir):
            for file in files:
                if look_for in file:
                    i = i + 1
                    self.backedUpToday = True
        return i
    
    def backup_server_files(self):
        """Backup all files in folder ../, 
        except for the actual backup folder !
        """
        backup_filename = "%s/_backup/%s.backup.zip" % (os.path.dirname(os.getcwd()),
                                                        time.strftime("%Y-%m-%d.%H-%M"))
        target_dir = "../"
        zip = zipfile.ZipFile(backup_filename, "w", zipfile.ZIP_DEFLATED)
        rootlen = len(target_dir) - 1
        for base, dirs, files in os.walk(target_dir):
            for file in files:
                fn = os.path.join(base, file)
                if base[rootlen:] != "/_backup":
                    zip.write(fn, fn[rootlen:])
    
    def backup_database_files(self):
        if PYTHON_MYSQL:
            return """Content-type: text/html\nPython db backup not availble at the moment..."""
        else:
            # No MySQL module, resort to PHP backup script
            return "Location: ../manage.php?action=backup_db&output=print\n"
    


