#!/usr/bin/env python
# -*- coding: utf-8 -*-
import cgi
import cgitb; cgitb.enable() # for troubleshooting. cgitb.enable(display=0, logdir="/cgi-bin/python_errors")
import sys
import os
from os.path import join, abspath, isdir
import difflib
import filecmp
import shutil
import operator
try:
    import sqlite3
    using_sqlite = True
except ImportError:
    using_sqlite = False
    try:
        import cPickle as pickle
    except ImportError:
        import pickle

class CompareDirectories:
    """Compares a two directories against each other, and if changes
       are found, checks what the changes are. """
    
    def __init__(self, settings):
        """Initialize all the processes needed to compare the files"""
        self.settings = settings
        self.always_excluded = [".DS_Store"]
        self.excluded_files = []
        self.excluded_dirs = []
        self.excludeFiles()
        self.excludeDirs()
    
    def excludeFiles(self):
        files = self.settings["excludedFiles"]
        for filename in files.splitlines():
            self.excluded_files.append(filename)
    
    def excludeDirs(self):
        dirs = self.settings["excludedDirs"]
        for directory in dirs.splitlines():
            self.excluded_dirs.append(directory)
    
    def compare(self, directoryBefore, directoryAfter):
        self.directoryBefore = directoryBefore
        self.directoryAfter = directoryAfter
        # Walk the directories to get a list of dirnames
        self.before_dir_list = self.walkDirs(directoryBefore)
        self.after_dir_list = self.walkDirs(directoryAfter)
        # Detect what dirs are in both before and after
        self.present_dirs = {}
        self.present_dirs[self.directoryBefore] = self.directoryAfter
        # Detect what dirs are removed
        self.removed_not_present_dirs = []
        # Detect what dirs are added
        self.added_not_present_dirs = []
        # The changed files
        self.changed_files = {}
        self.removed_files = {}
        self.added_files = {}
        # The changes performed on files that are not the same as
        # their complementary former files
        self.diff_unchanged = {}
        self.diff_before = {}
        self.diff_after = {}
        self.diff_change = {}
        # Find changes in directories
        self.compareDirs()
        # Find changed files
        self.checkFiles()
        # Find the changes in the files
        self.compareFiles()
    
    def walkDirs(self, check_dir):
        """Walk the dirs to get a directory tree"""
        dir_list = []
        for root, dirs, files in os.walk(check_dir):
            for dir in dirs:
                path = abspath(join(root, dir))
                if path[len(check_dir):] not in self.excluded_dirs:
                    dir_list.append(path)
        return dir_list
    
    def compareDirs(self):
        """Compare the directorires to find added/removed directories"""
        before_dirs = self.before_dir_list
        after_dirs = self.after_dir_list
        # New directories
        for item in after_dirs:
            tmp_item = self.directoryBefore + item[len(self.directoryAfter):]
            if tmp_item in before_dirs:
                self.present_dirs[tmp_item] = item
            else:
                self.added_not_present_dirs.append(item)
        # Removed directories
        for item in before_dirs:
            tmp_item = self.directoryAfter + item[len(self.directoryBefore):]
            if (tmp_item not in after_dirs 
                and self.directoryAfter[:-len(self.directoryAfter.split("/")[-1])-1] not in item):
                self.removed_not_present_dirs.append(item)
    
    def checkFiles(self):
        """Check if there has been a change in the files in the directories"""
        for dirs in self.present_dirs:
            compare = filecmp.dircmp(dirs, self.present_dirs[dirs])
            if compare.diff_files:
                # Files that have been changed
                for filename in compare.diff_files:
                    relative_before_filename = dirs[len(self.directoryBefore):] + "/" + filename
                    if relative_before_filename not in self.excluded_files and filename not in self.always_excluded:
                        self.changed_files[dirs + "/" + filename] = self.present_dirs[dirs] + "/" + filename
                # Removed files
                for filename in compare.left_only:
                    relative_before_filename = dirs[len(self.directoryBefore):] + "/" + filename
                    relative_dir = dirs + "/" + filename
                    if (relative_before_filename not in self.excluded_files 
                        and filename not in self.always_excluded 
                        and isdir(relative_dir) is False):
                        self.removed_files[dirs + "/" + filename] = self.present_dirs[dirs] + "/" + filename
                # Added files
                for filename in compare.right_only:
                    relative_after_filename = dirs[len(self.directoryAfter):] + "/" + filename
                    relative_dir = self.present_dirs[dirs] + "/" + filename
                    if (relative_after_filename not in self.excluded_files 
                        and filename not in self.always_excluded 
                        and isdir(relative_dir) is False):
                        self.added_files[dirs + "/" + filename] = self.present_dirs[dirs] + "/" + filename
    
    def compareFiles(self):
        """Check what the differences are"""
        for files in self.changed_files:
            # Open file objects
            file_before = open(files)
            file_after = open(self.changed_files[files])
            # Find differences
            diff = difflib.ndiff(file_before.readlines(), file_after.readlines())
            diff_list = list(diff)
            
            diff_unchanged = {}
            diff_before = {}
            diff_after = {}
            line_num = 0
            operator = ["-", "+", "?"]
            # Find where the changes occured
            for line in diff_list:
                if line[0:1] not in operator:
                    line_num = line_num + 1
                    diff_unchanged[str(line_num)] = line[2:]
                elif "-" in line[0:1]:
                    line_num = line_num + 1
                    diff_before[str(line_num)] = line[2:]
                elif "+" in line[0:1]:
                    diff_after[str(line_num + 1)] = line[2:]
            self.diff_unchanged[self.changed_files[files]] = diff_unchanged
            self.diff_before[files] = diff_before
            self.diff_after[self.changed_files[files]] = diff_after
            # Close file objects
            file_before.close()
            file_after.close()
    


class ChangeFiles:
    """ Can print out the information gotten from CompareDirectories
       and can make the changes if needed. """
    
    def __init__(self, compareObject):
        self.compareObject = compareObject
    
    def printChangedFiles(self):
        altered_files = {}
        altered_files_before = {}
        altered_files_after = {}
        # Files from before
        for filename in self.compareObject.diff_before:
            file_changes = self.compareObject.diff_before[filename]
            if file_changes:
                tmp_filename = filename[len(self.compareObject.directoryBefore):]
                altered_files[tmp_filename] = {"before":file_changes}
        # Files after
        for filename in self.compareObject.diff_after:
            file_changes = self.compareObject.diff_after[filename]
            if file_changes:
                tmp_filename = filename[len(self.compareObject.directoryAfter):]
                altered_files_after = {"after":file_changes}
                try:
                    altered_files[tmp_filename] = dict(altered_files[tmp_filename].items() + altered_files_after.items())
                except KeyError:
                    altered_files[tmp_filename] = altered_files_after
        # Put together the output
        altered_files_output = ""
        for filename, items in altered_files.iteritems():
            altered_files_output += """
                                    <tr id="filename">
                                        <td colspan="2">%s</td>
                                        <td>
                                            <form action="" method="POST">
                                            <input type="hidden" name="action" value="restore_file">
                                            <input type="hidden" name="filename" value="%s">
                                            <input type="submit" value="Restore">
                                            </form>
                                            <form action="" method="POST">
                                            <input type="hidden" name="action" value="alter_file">
                                            <input type="hidden" name="filename" value="%s">
                                            <input type="submit" value="Update">
                                            </form>
                                    </tr>\n""" % (filename, filename, filename)
            if "after" in items:
                altered_files_output += """<tr id="state"><td colspan="2">After</td></tr>\n"""
                i = 0
                for line, text in sorted(items["after"].iteritems()):
                    try:
                        if i != 0 and int(line) > 10 + i:
                            altered_files_output += """
                                                    <tr id="textAfter">
                                                        <td id="linenum">:</td>
                                                        <td colspan="2" id="text">&nbsp;&nbsp;.&nbsp;.&nbsp;.</td>
                                                    </tr>\n"""
                        altered_files_output += """
                                                <tr id="textAfter">
                                                    <td id="linenum">%s:</td>
                                                    <td colspan="2" id="text">%s</td>
                                                </tr>\n""" % (line, cgi.escape(text))
                    except UnicodeDecodeError:
                        pass
                    i = int(line)
            if "before" in items:
                altered_files_output += """<tr id="state"><td colspan="2">Before</td></tr>\n"""
                i = 0
                for line, text in sorted(items["before"].iteritems()):
                    try:
                        if i != 0 and int(line) > 10 + i:
                            altered_files_output += """
                                                    <tr id="textBefore">
                                                        <td id="linenum">:</td>
                                                        <td colspan="2" id="text">&nbsp;&nbsp;.&nbsp;.&nbsp;.</td>
                                                    </tr>\n"""
                        altered_files_output += """
                                                <tr id="textBefore">
                                                    <td id="linenum">%s:</td>
                                                    <td colspan="2" id="text">%s</td>
                                                </tr>\n""" % (line, cgi.escape(text))
                    except UnicodeDecodeError:
                        pass
                    i = int(line)
        return """
        <table id="changedFiles">
            %s
        </table>
        """ % altered_files_output
    
    def countChangedFiles(self):
        combined = {}
        before = self.compareObject.diff_before
        for filename in before:
            combined[filename[len(self.compareObject.directoryBefore):]] = ""
        after = self.compareObject.diff_after
        for filename in after:
            combined[filename[len(self.compareObject.directoryAfter):]] = ""
        return len(combined)
    
    def printChangedDirs(self):
        altered_output = ""
        # Added directories
        if self.compareObject.added_not_present_dirs or self.compareObject.added_files:
            altered_output += """<tr id="filename"><td colspan="3">New files and directories</td></tr>\n"""
        for directory in self.compareObject.added_not_present_dirs:
            tmp_directory = directory[len(self.compareObject.directoryAfter):]
            altered_output += """
                                <tr id="filename">
                                    <td colspan="2">%s</td>
                                    <td>
                                        <form action="" method="POST">
                                        <input type="hidden" name="action" value="remove_dir_dev_movedFiles">
                                        <input type="hidden" name="filename" value="%s">
                                        <input type="submit" value="Remove From Dev">
                                        </form>
                                        <form action="" method="POST">
                                        <input type="hidden" name="action" value="add_dir_movedFiles">
                                        <input type="hidden" name="filename" value="%s">
                                        <input type="submit" value="Add To Live">
                                        </form>
                                    </td>
                                </tr>\n""" % (tmp_directory, tmp_directory, tmp_directory)
        for filename in self.compareObject.added_files:
            tmp_filename = filename[len(self.compareObject.directoryAfter):]
            altered_output += """
                                <tr id="filename">
                                    <td colspan="2">%s</td>
                                    <td>
                                        <form action="" method="POST">
                                        <input type="hidden" name="action" value="remove_file_movedFiles">
                                        <input type="hidden" name="filename" value="%s">
                                        <input type="submit" value="Remove From Dev">
                                        <form action="" method="POST">
                                        <input type="hidden" name="action" value="add_file_movedFiles">
                                        <input type="hidden" name="filename" value="%s">
                                        <input type="submit" value="Add To Live">
                                        </form>
                                        </form>
                                    </td>
                                </tr>\n""" % (tmp_filename, tmp_filename, tmp_filename)
        # Removed directories
        if self.compareObject.removed_not_present_dirs:
            altered_output += """<tr id="filename"><td colspan="3">Removed Directories</td></tr>\n"""
        for directory in self.compareObject.removed_not_present_dirs:
            tmp_directory = directory[len(self.compareObject.directoryBefore):]
            altered_output += """
                                <tr id="filename">
                                    <td colspan="2">%s</td>
                                    <td>
                                        <form action="" method="POST">
                                        <input type="hidden" name="action" value="add_dir_live_movedFiles">
                                        <input type="hidden" name="filename" value="%s">
                                        <input type="submit" value="Restore To Dev">
                                        </form>
                                        <form action="" method="POST">
                                        <input type="hidden" name="action" value="remove_dir_live_movedFiles">
                                        <input type="hidden" name="filename" value="%s">
                                        <input type="submit" value="Remove From Live">
                                        </form>
                                    </td>
                                </tr>\n""" % (tmp_directory, tmp_directory, tmp_directory)
        if self.compareObject.removed_files:
            altered_output += """<tr id="filename"><td colspan="3">Removed Files</td></tr>\n"""
        for filename in self.compareObject.removed_files:
            tmp_filename = filename[len(self.compareObject.directoryBefore):]
            altered_output += """
                                <tr id="filename">
                                    <td colspan="2">%s</td>
                                    <td>
                                        <form action="" method="POST">
                                        <input type="hidden" name="action" value="add_file_live_movedFiles">
                                        <input type="hidden" name="filename" value="%s">
                                        <input type="submit" value="Restore To Dev">
                                        </form>
                                        <form action="" method="POST">
                                        <input type="hidden" name="action" value="remove_file_movedFiles">
                                        <input type="hidden" name="filename" value="%s">
                                        <input type="submit" value="Remove From Live">
                                        </form>
                                    </td>
                                </tr>\n""" % (tmp_filename, tmp_filename, tmp_filename)
        return """
        <table id="movedFiles">
            %s
        </table>
        """ % altered_output
    
    def countAddedDirs(self):
        return len(self.compareObject.added_not_present_dirs)
    
    def countAddedFiles(self):
        return len(self.compareObject.added_files)
    
    def countRemovedDirs(self):
        return len(self.compareObject.removed_not_present_dirs)
    
    def countRemovedFiles(self):
        return len(self.compareObject.removed_files)
    
    def countAll(self):
        count = self.countAddedDirs() + self.countAddedFiles() + self.countRemovedDirs() + self.countRemovedFiles() + self.countChangedFiles()
        return count
    
    def addDir(self, directory, *direction):
        live_dir = self.compareObject.directoryBefore + directory
        dev_dir = self.compareObject.directoryAfter + directory
        try:
            if "restore" in direction:
                shutil.copytree(live_dir, dev_dir)
            else:
                shutil.copytree(dev_dir, live_dir)
            return True
        except IOError:
            return False
    
    def removeDir(self, directory, *direction):
        live_dir = self.compareObject.directoryBefore + directory
        dev_dir = self.compareObject.directoryAfter + directory
        try:
            if "restore" in direction:
                shutil.rmtree(live_dir)
            else:
                shutil.rmtree(dev_dir)
            return True
        except IOError:
            return False
    
    def addFile(self, filename, *direction):
        live_file = self.compareObject.directoryBefore + filename
        dev_file = self.compareObject.directoryAfter + filename
        try:
            if "restore" in direction:
                shutil.copy2(live_file, dev_file)
            else:
                shutil.copy2(dev_file, live_file)
            return True
        except IOError:
            return False
    
    def removeFile(self, filename):
        live_file = self.compareObject.directoryBefore + filename
        try:
            os.remove(live_file)
            return True
        except IOError:
            return False
    
    def saveChangedFile(self, filename):
        live_file = self.compareObject.directoryBefore + filename
        dev_file = self.compareObject.directoryAfter + filename
        try:
            shutil.copyfile(dev_file, live_file)
            return True
        except IOError:
            return False
    
    def restoreChangedFile(self, filename):
        live_file = self.compareObject.directoryBefore + filename
        dev_file = self.compareObject.directoryAfter + filename
        try:
            shutil.copyfile(live_file, dev_file)
            return True
        except IOError:
            return False
    
    def updateAll(self):
        pass
    
    def restoreAll(self):
        pass
    


class HandleActions:
    """Handles requests and interaction with objects"""
    
    def __init__(self):
        """Sets up the initial database to be used for the bots information."""
        if using_sqlite:
            self.sqldatabase = "settings.sql"
            self.sqlcon = sqlite3.connect(self.sqldatabase)
            self.sqlcursor = self.sqlcon.cursor()
            self.sqlcursor.execute('CREATE TABLE IF NOT EXISTS settings (id INTEGER PRIMARY KEY, live_path VARCHAR(250), dev_path VARCHAR(250), excludedFiles TEXT, excludedDirs TEXT)')
            self.sqlcon.commit()
            self.sqlcursor.close()
            self.sqlcon.close()
    
    def setSettingsSQL(self, live_path, dev_path, excludedFiles, excludedDirs):
        try:
            self.sqlcon = sqlite3.connect(self.sqldatabase)
            self.sqlcursor = self.sqlcon.cursor()
            search_query = ['1']
            self.sqlcursor.execute('SELECT count(*) > 0 FROM (SELECT * FROM settings WHERE id=?)', search_query)
            if self.sqlcursor.fetchone()[0] == 0:
                insertquery = [live_path, dev_path, excludedFiles, excludedDirs]
                self.sqlcursor.execute('INSERT INTO settings VALUES (null,?,?,?,?)', insertquery)
                self.sqlcon.commit()
            else:
                updatequery = [live_path, dev_path, excludedFiles, excludedDirs, '1']
                self.sqlcursor.execute('UPDATE settings SET live_path=?, dev_path=?, excludedFiles=?, excludedDirs=? WHERE id=?', updatequery)
                self.sqlcon.commit()
            self.sqlcursor.close()
            self.sqlcon.close()
            return True
        except:
            pass
    
    def setSettingsPickle(self, live_path, dev_path, excludedFiles, excludedDirs):
        settings = {"live_path":live_path,
                    "dev_path":dev_path,
                    "excludedFiles":excludedFiles,
                    "excludedDirs":excludedDirs}
        pickle.dump(settings, open( "settings.p", "wb" ))
    
    def setSettings(self, live_path, dev_path, excludedFiles, excludedDirs):
        if using_sqlite:
            return self.setSettingsSQL(live_path, dev_path, excludedFiles, excludedDirs)
        else:
            return self.setSettingsPickle(live_path, dev_path, excludedFiles, excludedDirs)
    
    def getSettingsPickle(self):
        #live_path = "/home/chrules/public_html/_labs"
        #dev_path = "/home/chrules/public_html/_labs/_dev/site"
        settings = {}
        settings["live_path"] = ""
        settings["dev_path"] = ""
        settings["excludedFiles"] = ""
        settings["excludedDirs"] = ""
        try:
            settings = pickle.load(open( "settings.p", "rb" ))
        except:
            pass
        return settings
    
    def getSettingsSQL(self):
        try:
            self.sqlcon = sqlite3.connect(self.sqldatabase)
            self.sqlcursor = self.sqlcon.cursor()
            settings = {}
            settings["live_path"] = ""
            settings["dev_path"] = ""
            settings["excludedFiles"] = ""
            settings["excludedDirs"] = ""
            self.sqlcursor.execute('SELECT * FROM settings WHERE id = 1')
            for row in self.sqlcursor:
                settings["live_path"] = row[1]
                settings["dev_path"] = row[2]
                settings["excludedFiles"] = row[3]
                settings["excludedDirs"] = row[4]
            self.sqlcursor.close()
            self.sqlcon.close()
            return settings
        except:
            pass
    
    def getSettings(self):
        if using_sqlite:
            return self.getSettingsSQL()
        else:
            return self.getSettingsPickle()
    


class Appearance:
    """Handles default appearance behaviours"""
    
    def __init__(self):
        pass
    
    def topBar(self):
        topbar = """
        <div id="Menu">
            <div style="float:left;font-size:15px;padding:0 40px 0 20px;color:#fff;">TheDevShed</div>
            <div style="float:left;">
                <a href="index.py">Home</a>
                <a href="changes.py">Changed files and dirs</a>
                <a href="settings.py">Settings</a>
            </div>
        </div>
        """
        return topbar
    
    def headTag(self):
        header = """
        <meta charset="UTF-8">
    	<title>TheDevShed</title>
    	<link rel="stylesheet" type="text/css" media="screen, print, projection" href="../stylesheet.css">
    	"""
    	return header
    

