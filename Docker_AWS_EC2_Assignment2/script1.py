import os
from tabulate import tabulate
x = os.listdir("./home/data/")
l=[]
for i in x:
    if i[-3:]=="txt":
        l.append(i)
c=[]
maxi=0
maxif=[]
for i in l:
    filer = open("./home/data/"+i, "r")
    data = filer.read()
    words = data.split()
    c.append([i,len(words)])
    if len(words)>maxi:
        maxi=len(words)
        maxif=[]
        maxif.append(i)
    elif len(words)==maxi:
        maxif.append(i)
file1 = open("./home/output/result.txt", "w")
file1.write("{:<50} {:<25}".format('File Name','No.of Words'))
file1.write("\n")
#print(os.getcwd())
file1.write("--------------------------------------------------------------------------")
file1.write("\n")
for i in c:
    name,count=i
    file1.write("{:<50} {:<25}".format( name, count))
    file1.write("\n")
file1.write("{:<50} {:<25}".format('---------','-----------'))
file1.write("\n")
ts=0
for i in c:
    ts=ts+int(i[1])
file1.write("{:<50} {:<25}".format('Total count of words in all files',ts))
file1.write("\n")
file1.write('--------------------------------------------------------------------------')
file1.write("\n")
file1.write("{:<50} {:<25}".format('File Name with maximum word count','Word Count'))
file1.write("\n")
file1.write("{:<50} {:<25}".format('---------','-----------'))
file1.write("\n")
for i in maxif:
    file1.write("{:<50} {:<25}".format(i,maxi))
    file1.write("\n")
file1.write('--------------------------------------------------------------------------')
file1.write("\n")
file1.close()
f = open("./home/output/result.txt", "r")
print(f.read())
