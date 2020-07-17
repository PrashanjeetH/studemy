import os
old_word = str(input('Enter the OLD string\t'))
new_word = str(input('Enter the NEW string\t'))
os.listdir("./")
count=0
for i in os.listdir("./"):
  if 'php' in str(i):
    f = open(i,'r')
    filedata = f.read()
    f.close()
    if old_word in filedata:
      count= count+1
      print(count ,i)
      newdata = filedata.replace(old_word,new_word)

    f = open(i,'w')
    f.write(newdata)
    f.close()
input()
