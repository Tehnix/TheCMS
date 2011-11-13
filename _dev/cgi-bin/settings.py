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

#live_path = "/Users/tehnix/Dropbox/Apps - PHP/public_html/_labs"
#dev_path = "/Users/tehnix/Dropbox/Apps - PHP/public_html/_labs/_dev/site"
handler = HandleActions()
appearance = Appearance()
form = cgi.FieldStorage()
live_path = form.getvalue("live_path", False)
dev_path = form.getvalue("dev_path", False)
excludedFiles = form.getvalue("excludedFiles", "")
excludedDirs = form.getvalue("excludedDirs", "")

if live_path and dev_path:
    handler.setSettings(live_path, dev_path, excludedFiles, excludedDirs)
    
settings = handler.getSettings()
if settings["live_path"] and settings["dev_path"]:
    get_live_path = settings["live_path"]
    get_dev_path = settings["dev_path"]
    get_excludedFiles = settings["excludedFiles"]
    get_excludedDirs = settings["excludedDirs"]
else:
    get_live_path = ""
    get_dev_path = ""
    get_excludedFiles = ""
    get_excludedDirs = ""
    
print """
<!DOCTYPE html>
<html>
<head>
	%s
</head>
<body>
%s
<div id="Content">
<form action="settings.py" method="POST">
<table id="settingsTable">
    <tr>
        <td>Live path: </td>
        <td><input style="width:350px;" name="live_path" type="text" value="%s"></td>
    </tr>
    <tr>
        <td>Dev path: </td>
        <td><input style="width:350px;" name="dev_path" type="text" value="%s"></td>
    </tr>
    <tr>
        <td>
            Excluded files:
            <br>
            <span style="color:grey;font-size:11px;">(seperate by newline)</span>
        </td>
        <td><textarea style="width:350px;height:60px;" name="excludedFiles">%s</textarea></td>
    </tr>
    <tr>
        <td>
            Excluded directories:
            <br>
            <span style="color:grey;font-size:11px;">(seperate by newline)</span>
        </td>
        <td><textarea style="width:350px;height:60px;" name="excludedDirs">%s</textarea></td>
    </tr>
    <tr>
        <td></td>
        <td style="float:right;"><input type="submit" value="Set Settings"></td>
    </tr>
</table>
</form>
</div>
</body>
</html>
""" % (appearance.headTag(), appearance.topBar(), get_live_path, get_dev_path, get_excludedFiles, get_excludedDirs)