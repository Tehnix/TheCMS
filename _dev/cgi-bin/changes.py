#!/usr/bin/env python
# -*- coding: utf-8 -*-
import cgi
import cgitb; cgitb.enable() # for troubleshooting. cgitb.enable(display=0, logdir="/cgi-bin/python_errors")
from handle import *

# Declare content type (header)
print "Content-type: text/html"
print "Pragma-directive: no-cache"
print "Cache-directive: no-cache"
print "Cache-control: no-cache"
print "Pragma: no-cache"
print "Expires: 0"
print 

handler = HandleActions()
appearance = Appearance()
form = cgi.FieldStorage()
action = form.getvalue("action", False)
filename = form.getvalue("filename", False)

settings = handler.getSettings()
if settings["live_path"] and settings["dev_path"]:
    comparison = CompareDirectories(settings)
    comparison.compare(settings["live_path"], settings["dev_path"])
    changes = ChangeFiles(comparison)
    changedDirs = changes.printChangedDirs()
    changedFiles = changes.printChangedFiles()
    if action and filename:
        if action == "update_all":
            changes.updateAll()
        elif action == "restore_all":
            changes.restoreAll()
        elif action == "alter_file":
            changes.saveChangedFile(filename)
        elif action == "restore_file":
            changes.restoreChangedFile(filename)
        elif action == "add_dir_movedFiles":
            changes.addDir(filename)
        elif action == "add_dir_live_movedFiles":
            changes.addDir(filename, "restore")
        elif action == "remove_dir_dev_movedFiles":
            changes.removeDir(filename)
        elif action == "remove_dir_live_movedFiles":
            changes.removeDir(filename, "restore")
        elif action == "add_file_movedFiles":
            changes.addFile(filename)
        elif action == "add_file_live_movedFiles":
            changes.addFile(filename, "restore")
        elif action == "remove_file_movedFiles":
            changes.removeFile(filename)
        comparison = CompareDirectories(settings)
        comparison.compare(settings["live_path"], settings["dev_path"])
        changes = ChangeFiles(comparison)
        changedDirs = changes.printChangedDirs()
        changedFiles = changes.printChangedFiles()
else:
    changedDirs = ""
    changedFiles = ""
    
print """
<!DOCTYPE html>
<html>
<head>
	%s
</head>
<body>
%s
<div id="Content">
%s

%s
</div>
</body>
</html>
""" % (appearance.headTag(), appearance.topBar(), changedDirs, changedFiles)