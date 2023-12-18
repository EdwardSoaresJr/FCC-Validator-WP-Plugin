import sqlite3
import csv
import urllib.request
import zipfile
import os

#gmrs_URL = "https://data.fcc.gov/download/pub/uls/complete/l_gmrs.zip"
gmrs_ZIP = "./data/l_gmrs.zip"

gmrs_dir = "./data/l_gmrs/"
gmrs_HD = "./data/l_gmrs/HD.dat"
gmrs_EN = "./data/l_gmrs/EN.dat"

DB_filepath = "gmrs.sqlite3"


#print("Downloading GMRS ZIP from FCC")
#urllib.request.urlretrieve(gmrs_URL, gmrs_ZIP)

print("Extracting GMRS files from ZIP")
with zipfile.ZipFile(gmrs_ZIP, 'r') as zip_ref:
    zip_ref.extract("HD.dat", gmrs_dir)
    zip_ref.extract("EN.dat", gmrs_dir)

print("Creating SQLite DB in memory")
con = sqlite3.connect(':memory:')
cur = con.cursor()
cur.execute("CREATE TABLE hd (usid, callsign, status, expiration)")
cur.execute("CREATE TABLE en (usid, frn)")

csv.register_dialect('piper', delimiter='|', quoting=csv.QUOTE_NONE)

print("Importing GMRS")
print("  Processing HD")
with open(gmrs_HD, "r") as csvfile:
    good_lines = 0
    bad_lines = 0
    for row in csv.reader(csvfile, dialect='piper'):
        if len(row) == 59 and row[0] == "HD" and row[5] == 'A':
            cur.execute("INSERT INTO hd VALUES (?, ?, ?, ?)", (row[1], row[4], row[5], row[8]))
            good_lines += 1
        else:
            bad_lines += 1
print("    " + str(good_lines) + " / " + str(bad_lines))

print("  Bulk EN")
with open(gmrs_EN, "r") as csvfile:
    good_lines = 0
    bad_lines = 0
    for row in csv.reader(csvfile, dialect='piper'):
        if len(row) == 30 and row[0] == "EN":
            cur.execute("INSERT INTO en VALUES (?, ?)", (row[1], row[22]))
            good_lines += 1
        else:
            bad_lines += 1
print("    " + str(good_lines) + " / " + str(bad_lines))

# Delete previous sqlit file first.
if os.path.exists("gmrs.sqlite3"):
  print("Removing existing sqlite3 database")
  os.remove("gmrs.sqlite3")

print("Exporting data from SQLite in memory to", DB_filepath)
con.execute("ATTACH DATABASE '" + DB_filepath + "' AS clean;").fetchall()
print("  Inserting table into", DB_filepath)
con.execute("CREATE TABLE clean.licenses AS SELECT hd.usid,callsign,status,expiration,frn FROM hd INNER JOIN en ON hd.usid = en.usid").fetchall()
print("  Cleaning up")
con.execute("COMMIT;").fetchall()
con.execute("DETACH clean;").fetchall()
con.commit()
con.close()

print("Optimizing DB")
print("  Connecting")
con = sqlite3.connect(DB_filepath)

print("  Building indices")
con.execute("CREATE INDEX callsign ON licenses (callsign);").fetchall()
con.execute("CREATE INDEX frn ON licenses (frn);").fetchall()
con.execute("pragma journal_mode = delete;").fetchall()
con.execute("pragma page_size = 1024;").fetchall()

print("  Vacuum")
con.execute("vacuum;").fetchall()

print("  Cleaning up")
con.commit()
con.close()

print("Done")