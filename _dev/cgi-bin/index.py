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

settings = handler.getSettings()
if settings["live_path"] and settings["dev_path"]:
    comparison = CompareDirectories(settings)
    comparison.compare(settings["live_path"], settings["dev_path"])
    changes = ChangeFiles(comparison)
    if action:
        if action == "update_all":
            changes.updateAll()
        elif action == "restore_all":
            changes.restoreAll()
        comparison = CompareDirectories(settings)
        comparison.compare(settings["live_path"], settings["dev_path"])
        changes = ChangeFiles(comparison)
    changedFiles = changes.countChangedFiles()
    if changedFiles > 0:
        if changedFiles == 1:
            changedFiles = """<tr><td></td><td>%s file has been changed</td></tr>""" % changedFiles
        else:
            changedFiles = """<tr><td></td><td>%s files have been changed</td></tr>""" % changedFiles
    else:
        changedFiles = """<tr><td></td><td>All files are up to date !</td></tr>"""
    addedDirs = changes.countAddedDirs()
    if addedDirs > 0:
        if addedDirs == 1:
            addedDirs = """<tr><td></td><td>%s directory has been added</td></tr>""" % addedDirs
        else:
            addedDirs = """<tr><td></td><td>%s directories have been added</td></tr>""" % addedDirs
    else:
        addedDirs = ""
    addedFiles = changes.countAddedFiles()
    if addedFiles > 0:
        if addedFiles == 1:
            addedFiles = """<tr><td></td><td>%s file has been added</td></tr>""" % addedFiles
        else:
            addedFiles = """<tr><td></td><td>%s files have been added</td></tr>""" % addedFiles
    else:
        addedFiles = ""
    removedDirs = changes.countRemovedDirs()
    if removedDirs > 0:
        if removedDirs == 1:
            removedDirs = """<tr><td></td><td>%s directory has been removed</td></tr>""" % removedDirs
        else:
            removedDirs = """<tr><td></td><td>%s directories have been removed</td></tr>""" % removedDirs
    else:
        removedDirs = ""
    removedFiles = changes.countRemovedFiles()
    if removedFiles > 0:
        if removedFiles == 1:
            removedFiles = """<tr><td></td><td>%s file has been removed</td></tr>""" % removedFiles
        else:
            removedFiles = """<tr><td></td><td>%s files have been removed</td></tr>""" % removedFiles
    else:
        removedFiles = ""
    updateForm = ""
    if changes.countAll() > 0:
        updateForm = """
                        <tr>
                            <td></td>
                            <td>
                                <form action="" method="POST">
                                <input type="hidden" name="action" value="restore_all">
                                <input type="submit" value="Restore All !"
                                </form>
                                <form action="" method="POST">
                                <input type="hidden" name="action" value="update_all">
                                <input type="submit" value="Update All !"
                                </form>
                        </tr>
                        """
else:
    changedFiles = "<tr><td>Settings are not set, or no data available!</td></tr>"
    addedDirs = ""
    addedFiles = ""
    removedDirs = ""
    removedFiles = ""
    updateForm = ""
    
print """
<!DOCTYPE html>
<html>
<head>
	%s
</head>
<body>
%s
<div id="Content">
<table>
%s
%s
%s
%s
%s
%s
</table>
</div>
</body>
</html>
""" % (appearance.headTag(), appearance.topBar(), changedFiles, addedDirs, addedFiles, removedDirs, removedFiles, updateForm)